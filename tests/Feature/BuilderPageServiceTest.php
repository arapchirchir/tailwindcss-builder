<?php

namespace Tecworld\TailwindBuilder\Tests\Feature;

use Tecworld\TailwindBuilder\Models\BuilderPage;
use Tecworld\TailwindBuilder\Services\BuilderPageService;
use Tecworld\TailwindBuilder\Support\Enums\BuilderPageStatus;
use Tecworld\TailwindBuilder\Tests\TestCase;

class BuilderPageServiceTest extends TestCase
{
    public function test_it_creates_a_builder_page_with_unique_slug(): void
    {
        $service = app(BuilderPageService::class);

        $firstPage = $service->create([
            'title' => 'About Us',
        ]);

        $secondPage = $service->create([
            'title' => 'About Us',
        ]);

        $this->assertDatabaseCount(config('tailwind-builder.tables.pages'), 2);

        $this->assertSame('about-us', $firstPage->slug);
        $this->assertSame('about-us-2', $secondPage->slug);
    }

    public function test_it_can_publish_a_builder_page(): void
    {
        $service = app(BuilderPageService::class);

        $page = $service->create([
            'title' => 'Services',
        ]);

        $this->assertSame(BuilderPageStatus::DRAFT, $page->status);

        $service->publish($page);

        $page->refresh();

        $this->assertSame(BuilderPageStatus::PUBLISHED, $page->status);
        $this->assertNotNull($page->published_at);

        $this->assertTrue(
            BuilderPage::query()->published()->whereKey($page->id)->exists()
        );
    }
}
