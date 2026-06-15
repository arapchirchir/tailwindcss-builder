<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ $page->seo_title ?: $page->title }}</title>

    @if ($page->seo_description)
        <meta name="description" content="{{ $page->seo_description }}">
    @endif

    @if ($page->seo_image)
        <meta property="og:image" content="{{ $page->seo_image }}">
    @endif

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body>
    {!! $html !!}
</body>

</html>
