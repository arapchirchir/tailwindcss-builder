<?php

namespace Tecworld\TailwindBuilder\Renderer;

use Illuminate\Support\Arr;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Str;

class TailwindRenderer
{
    protected array $allowedNodeTypes = [
        'page',
        'section',
        'container',
        'grid',
        'column',
        'heading',
        'paragraph',
        'text',
        'button',
        'image',
        'spacer',
    ];

    protected array $allowedHtmlTags = [
        'page' => 'div',
        'section' => 'section',
        'container' => 'div',
        'grid' => 'div',
        'column' => 'div',
        'heading' => 'h1',
        'paragraph' => 'p',
        'text' => 'span',
        'button' => 'a',
        'image' => 'img',
        'spacer' => 'div',
    ];

    public function render(?array $content): HtmlString
    {
        if (empty($content)) {
            return new HtmlString('');
        }

        return new HtmlString($this->renderNode($content));
    }

    protected function renderNode(array $node): string
    {
        $type = (string) Arr::get($node, 'type', 'container');

        if (! in_array($type, $this->allowedNodeTypes, true)) {
            return '';
        }

        return match ($type) {
            'heading' => $this->renderHeading($node),
            'paragraph' => $this->renderParagraph($node),
            'text' => $this->renderText($node),
            'button' => $this->renderButton($node),
            'image' => $this->renderImage($node),
            'spacer' => $this->renderSpacer($node),
            default => $this->renderWrapper($node, $this->allowedHtmlTags[$type] ?? 'div'),
        };
    }

    protected function renderWrapper(array $node, string $tag = 'div'): string
    {
        $classes = $this->classes($node);
        $children = $this->children($node);

        return sprintf(
            '<%1$s class="%2$s">%3$s</%1$s>',
            e($tag),
            e($classes),
            $children
        );
    }

    protected function renderHeading(array $node): string
    {
        $level = (int) Arr::get($node, 'props.level', 1);
        $level = min(max($level, 1), 6);

        return sprintf(
            '<h%1$d class="%2$s">%3$s</h%1$d>',
            $level,
            e($this->classes($node)),
            e((string) Arr::get($node, 'props.text', ''))
        );
    }

    protected function renderParagraph(array $node): string
    {
        return sprintf(
            '<p class="%s">%s</p>',
            e($this->classes($node)),
            e((string) Arr::get($node, 'props.text', ''))
        );
    }

    protected function renderText(array $node): string
    {
        return sprintf(
            '<span class="%s">%s</span>',
            e($this->classes($node)),
            e((string) Arr::get($node, 'props.text', ''))
        );
    }

    protected function renderButton(array $node): string
    {
        $href = $this->safeUrl((string) Arr::get($node, 'props.href', '#'));

        return sprintf(
            '<a href="%s" class="%s">%s</a>',
            e($href),
            e($this->classes($node)),
            e((string) Arr::get($node, 'props.text', 'Button'))
        );
    }

    protected function renderImage(array $node): string
    {
        $src = $this->safeUrl((string) Arr::get($node, 'props.src', ''));
        $alt = (string) Arr::get($node, 'props.alt', '');

        if ($src === '') {
            return '';
        }

        return sprintf(
            '<img src="%s" alt="%s" class="%s">',
            e($src),
            e($alt),
            e($this->classes($node))
        );
    }

    protected function renderSpacer(array $node): string
    {
        return sprintf(
            '<div aria-hidden="true" class="%s"></div>',
            e($this->classes($node))
        );
    }

    protected function children(array $node): string
    {
        $children = Arr::get($node, 'children', []);

        if (! is_array($children)) {
            return '';
        }

        return collect($children)
            ->filter(fn ($child) => is_array($child))
            ->map(fn ($child) => $this->renderNode($child))
            ->implode('');
    }

    protected function classes(array $node): string
    {
        $classes = Arr::get($node, 'classes', '');

        if (is_array($classes)) {
            $classes = implode(' ', $classes);
        }

        $classes = (string) $classes;

        /*
         * Basic safety: class attributes should not contain quotes or angle brackets.
         * This does not validate every Tailwind class yet, but prevents attribute breaking.
         */
        $classes = str_replace(['"', "'", '<', '>'], '', $classes);

        return trim(preg_replace('/\s+/', ' ', $classes) ?? '');
    }

    protected function safeUrl(string $url): string
    {
        $url = trim($url);

        if ($url === '') {
            return '';
        }

        if (Str::startsWith($url, ['http://', 'https://', '/', '#', 'mailto:', 'tel:'])) {
            return $url;
        }

        return '#';
    }
}
