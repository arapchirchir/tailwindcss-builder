<?php

namespace Tecworld\TailwindBuilder\Http\Controllers;

use Illuminate\Contracts\View\View;
use Illuminate\Routing\Controller;
use Tecworld\TailwindBuilder\Models\BuilderPage;
use Tecworld\TailwindBuilder\Renderer\TailwindRenderer;

class RenderBuilderPageController extends Controller
{
    public function __invoke(string $slug, TailwindRenderer $renderer): View
    {
        $page = BuilderPage::query()
            ->published()
            ->where('slug', $slug)
            ->firstOrFail();

        return view('tailwind-builder::builder.render', [
            'page' => $page,
            'html' => $renderer->render($page->content_json ?? []),
        ]);
    }
}
