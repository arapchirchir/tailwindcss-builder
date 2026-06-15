<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="light">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'Tailwind Builder' }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-base-200 text-base-content">
    <div class="min-h-screen">
        <div class="navbar border-b border-base-300 bg-base-100">
            <div class="flex-1">
                <a href="{{ route('tailwind-builder.dashboard') }}" class="btn btn-ghost text-xl">
                    Tailwind Builder
                </a>
            </div>

            <div class="flex-none">
                <a href="{{ route('tailwind-builder.pages.index') }}" class="btn btn-ghost btn-sm">
                    Pages
                </a>
            </div>
        </div>

        <main class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
            @if (session('status'))
                <div class="alert alert-success mb-6">
                    <span>{{ session('status') }}</span>
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-error mb-6">
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-error mb-6">
                    <div>
                        <div class="font-semibold">Please fix the following errors:</div>
                        <ul class="mt-2 list-inside list-disc">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            @yield('content')
        </main>
    </div>
</body>

</html>
