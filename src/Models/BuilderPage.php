<?php

namespace Tecworld\TailwindBuilder\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Tecworld\TailwindBuilder\Support\Enums\BuilderPageStatus;

class BuilderPage extends Model
{
    use SoftDeletes;

    protected $guarded = [];

    protected $casts = [
        'content_json' => 'array',
        'show_in_navigation' => 'boolean',
        'is_homepage' => 'boolean',
        'is_system' => 'boolean',
        'published_at' => 'datetime',
    ];

    public function getTable(): string
    {
        return config('tailwind-builder.tables.pages', 'twb_pages');
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', BuilderPageStatus::PUBLISHED)
            ->whereNotNull('published_at');
    }

    public function scopeDraft(Builder $query): Builder
    {
        return $query->where('status', BuilderPageStatus::DRAFT);
    }

    public function scopeArchived(Builder $query): Builder
    {
        return $query->where('status', BuilderPageStatus::ARCHIVED);
    }

    public function scopeVisibleInNavigation(Builder $query): Builder
    {
        return $query->where('show_in_navigation', true);
    }

    public function scopeOrderedForNavigation(Builder $query): Builder
    {
        return $query->orderBy('navigation_order')
            ->orderBy('title');
    }

    public function scopeHomepage(Builder $query): Builder
    {
        return $query->where('is_homepage', true);
    }

    public function publish(): bool
    {
        return $this->forceFill([
            'status' => BuilderPageStatus::PUBLISHED,
            'published_at' => now(),
        ])->save();
    }

    public function unpublish(): bool
    {
        return $this->forceFill([
            'status' => BuilderPageStatus::DRAFT,
            'published_at' => null,
        ])->save();
    }

    public function archive(): bool
    {
        return $this->forceFill([
            'status' => BuilderPageStatus::ARCHIVED,
        ])->save();
    }
}
