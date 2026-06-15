<?php

namespace Tecworld\TailwindBuilder\Services;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Tecworld\TailwindBuilder\Models\BuilderPage;
use Tecworld\TailwindBuilder\Models\BuilderRevision;
use Tecworld\TailwindBuilder\Support\Enums\BuilderPageStatus;
use Tecworld\TailwindBuilder\Support\Enums\BuilderPageType;

class BuilderPageService
{
    public function create(array $data, ?Authenticatable $user = null): BuilderPage
    {
        $title = trim((string) Arr::get($data, 'title'));
        $slug = $this->generateUniqueSlug(
            Arr::get($data, 'slug') ?: $title,
            Arr::get($data, 'type', BuilderPageType::PAGE)
        );

        return BuilderPage::query()->create([
            'title' => $title,
            'slug' => $slug,
            'type' => Arr::get($data, 'type', BuilderPageType::PAGE),
            'status' => Arr::get($data, 'status', BuilderPageStatus::DRAFT),
            'content_json' => Arr::get($data, 'content_json', $this->defaultPageContent()),
            'show_in_navigation' => (bool) Arr::get($data, 'show_in_navigation', false),
            'navigation_label' => Arr::get($data, 'navigation_label') ?: $title,
            'navigation_order' => (int) Arr::get($data, 'navigation_order', 0),
            'navigation_parent_id' => Arr::get($data, 'navigation_parent_id'),
            'seo_title' => Arr::get($data, 'seo_title'),
            'seo_description' => Arr::get($data, 'seo_description'),
            'seo_image' => Arr::get($data, 'seo_image'),
            'is_homepage' => (bool) Arr::get($data, 'is_homepage', false),
            'is_system' => (bool) Arr::get($data, 'is_system', false),
            'published_at' => Arr::get($data, 'status') === BuilderPageStatus::PUBLISHED ? now() : null,
            'created_by_id' => $this->userId($user),
            'updated_by_id' => $this->userId($user),
        ]);
    }

    public function update(BuilderPage $page, array $data, ?Authenticatable $user = null): BuilderPage
    {
        $this->createRevision($page, 'manual_save', $user);

        $title = trim((string) Arr::get($data, 'title', $page->title));
        $type = Arr::get($data, 'type', $page->type);

        $slug = Arr::has($data, 'slug')
            ? $this->generateUniqueSlug($data['slug'] ?: $title, $type, $page->id)
            : $page->slug;

        $status = Arr::get($data, 'status', $page->status);

        $page->forceFill([
            'title' => $title,
            'slug' => $slug,
            'type' => $type,
            'status' => $status,
            'content_json' => Arr::get($data, 'content_json', $page->content_json),
            'show_in_navigation' => (bool) Arr::get($data, 'show_in_navigation', $page->show_in_navigation),
            'navigation_label' => Arr::get($data, 'navigation_label', $page->navigation_label) ?: $title,
            'navigation_order' => (int) Arr::get($data, 'navigation_order', $page->navigation_order),
            'navigation_parent_id' => Arr::get($data, 'navigation_parent_id', $page->navigation_parent_id),
            'seo_title' => Arr::get($data, 'seo_title', $page->seo_title),
            'seo_description' => Arr::get($data, 'seo_description', $page->seo_description),
            'seo_image' => Arr::get($data, 'seo_image', $page->seo_image),
            'is_homepage' => (bool) Arr::get($data, 'is_homepage', $page->is_homepage),
            'updated_by_id' => $this->userId($user),
        ]);

        if ($status === BuilderPageStatus::PUBLISHED && ! $page->published_at) {
            $page->published_at = now();
        }

        if ($status !== BuilderPageStatus::PUBLISHED) {
            $page->published_at = null;
        }

        $page->save();

        return $page;
    }

    public function publish(BuilderPage $page, ?Authenticatable $user = null): BuilderPage
    {
        $this->createRevision($page, 'publish', $user);

        $page->forceFill([
            'status' => BuilderPageStatus::PUBLISHED,
            'published_at' => now(),
            'updated_by_id' => $this->userId($user),
        ])->save();

        return $page;
    }

    public function unpublish(BuilderPage $page, ?Authenticatable $user = null): BuilderPage
    {
        $this->createRevision($page, 'unpublish', $user);

        $page->forceFill([
            'status' => BuilderPageStatus::DRAFT,
            'published_at' => null,
            'updated_by_id' => $this->userId($user),
        ])->save();

        return $page;
    }

    public function archive(BuilderPage $page, ?Authenticatable $user = null): BuilderPage
    {
        $this->createRevision($page, 'archive', $user);

        $page->forceFill([
            'status' => BuilderPageStatus::ARCHIVED,
            'updated_by_id' => $this->userId($user),
        ])->save();

        return $page;
    }

    public function createRevision(BuilderPage $page, string $source = 'manual_save', ?Authenticatable $user = null): BuilderRevision
    {
        return BuilderRevision::query()->create([
            'revisionable_type' => 'page',
            'revisionable_id' => $page->id,
            'title' => $page->title,
            'content_json' => $page->content_json,
            'snapshot_json' => [
                'title' => $page->title,
                'slug' => $page->slug,
                'type' => $page->type,
                'status' => $page->status,
                'content_json' => $page->content_json,
                'show_in_navigation' => $page->show_in_navigation,
                'navigation_label' => $page->navigation_label,
                'navigation_order' => $page->navigation_order,
                'navigation_parent_id' => $page->navigation_parent_id,
                'seo_title' => $page->seo_title,
                'seo_description' => $page->seo_description,
                'seo_image' => $page->seo_image,
                'is_homepage' => $page->is_homepage,
                'published_at' => optional($page->published_at)->toISOString(),
            ],
            'source' => $source,
            'created_by_id' => $this->userId($user),
        ]);
    }

    public function generateUniqueSlug(string $value, string $type = BuilderPageType::PAGE, ?int $ignoreId = null): string
    {
        $baseSlug = Str::slug($value) ?: 'page';
        $slug = $baseSlug;
        $counter = 2;

        while ($this->slugExists($slug, $type, $ignoreId)) {
            $slug = $baseSlug.'-'.$counter;
            $counter++;
        }

        return $slug;
    }

    protected function slugExists(string $slug, string $type, ?int $ignoreId = null): bool
    {
        return BuilderPage::query()
            ->where('slug', $slug)
            ->where('type', $type)
            ->when($ignoreId, fn ($query) => $query->whereKeyNot($ignoreId))
            ->exists();
    }

    protected function defaultPageContent(): array
    {
        return [
            'type' => 'page',
            'version' => 1,
            'children' => [],
        ];
    }

    protected function userId(?Authenticatable $user): ?int
    {
        $id = $user?->getAuthIdentifier();

        return is_numeric($id) ? (int) $id : null;
    }
}
