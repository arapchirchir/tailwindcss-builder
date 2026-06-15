<?php

namespace Tecworld\TailwindBuilder\Http\Controllers;

use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Tecworld\TailwindBuilder\Http\Requests\StoreBuilderPageRequest;
use Tecworld\TailwindBuilder\Http\Requests\UpdateBuilderPageRequest;
use Tecworld\TailwindBuilder\Models\BuilderPage;
use Tecworld\TailwindBuilder\Services\BuilderPageService;
use Tecworld\TailwindBuilder\Support\Enums\BuilderPageStatus;
use Tecworld\TailwindBuilder\Support\Enums\BuilderPageType;

class BuilderPageController extends Controller
{
    public function __construct(
        protected BuilderPageService $pageService
    ) {}

    public function index(Request $request): View
    {
        $pages = BuilderPage::query()
            ->when(
                $request->filled('status'),
                fn ($query) => $query->where('status', $request->string('status')->toString())
            )
            ->when(
                $request->filled('type'),
                fn ($query) => $query->where('type', $request->string('type')->toString())
            )
            ->when(
                $request->filled('search'),
                function ($query) use ($request) {
                    $search = $request->string('search')->toString();

                    $query->where(function ($query) use ($search) {
                        $query->where('title', 'like', '%'.$search.'%')
                            ->orWhere('slug', 'like', '%'.$search.'%')
                            ->orWhere('navigation_label', 'like', '%'.$search.'%');
                    });
                }
            )
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('tailwind-builder::pages.index', [
            'pages' => $pages,
            'statuses' => BuilderPageStatus::values(),
            'types' => BuilderPageType::values(),
        ]);
    }

    public function create(): View
    {
        return view('tailwind-builder::pages.create', [
            'statuses' => BuilderPageStatus::values(),
            'types' => BuilderPageType::values(),
        ]);
    }

    public function store(StoreBuilderPageRequest $request): RedirectResponse
    {
        $page = $this->pageService->create(
            $request->validated(),
            $request->user()
        );

        return redirect()
            ->route('tailwind-builder.pages.edit', $page)
            ->with('status', 'Page created successfully.');
    }

    public function edit(BuilderPage $page): View
    {
        return view('tailwind-builder::pages.edit', [
            'page' => $page,
            'statuses' => BuilderPageStatus::values(),
            'types' => BuilderPageType::values(),
        ]);
    }

    public function update(UpdateBuilderPageRequest $request, BuilderPage $page): RedirectResponse
    {
        $this->pageService->update(
            $page,
            $request->validated(),
            $request->user()
        );

        return redirect()
            ->route('tailwind-builder.pages.edit', $page)
            ->with('status', 'Page updated successfully.');
    }

    public function destroy(Request $request, BuilderPage $page): RedirectResponse
    {
        if ($page->is_system) {
            return redirect()
                ->route('tailwind-builder.pages.index')
                ->with('error', 'System pages cannot be deleted.');
        }

        $this->pageService->createRevision($page, 'delete', $request->user());

        $page->delete();

        return redirect()
            ->route('tailwind-builder.pages.index')
            ->with('status', 'Page deleted successfully.');
    }
}
