<?php

use Illuminate\Support\Facades\Route;
use Tecworld\TailwindBuilder\Http\Controllers\BuilderPageController;
use Tecworld\TailwindBuilder\Http\Controllers\RenderBuilderPageController;
use Tecworld\TailwindBuilder\Http\Middleware\AuthorizeTailwindBuilder;

Route::middleware(array_merge(
    config('tailwind-builder.middleware', ['web', 'auth']),
    [AuthorizeTailwindBuilder::class]
))
    ->prefix(config('tailwind-builder.route_prefix', 'admin/builder'))
    ->name('tailwind-builder.')
    ->group(function () {
        Route::get('/', [BuilderPageController::class, 'index'])->name('dashboard');

        Route::get('/pages', [BuilderPageController::class, 'index'])->name('pages.index');
        Route::get('/pages/create', [BuilderPageController::class, 'create'])->name('pages.create');
        Route::post('/pages', [BuilderPageController::class, 'store'])->name('pages.store');
        Route::get('/pages/{page}/edit', [BuilderPageController::class, 'edit'])->name('pages.edit');
        Route::put('/pages/{page}', [BuilderPageController::class, 'update'])->name('pages.update');
        Route::delete('/pages/{page}', [BuilderPageController::class, 'destroy'])->name('pages.destroy');
    });

/*
|--------------------------------------------------------------------------
| Optional Public Builder Page Rendering
|--------------------------------------------------------------------------
|
| This is intentionally prefixed to avoid overriding existing application
| routes like /about, /contact, /services, etc.
|
| Example:
| /pages/about
| /pages/contact
|
*/

if (config('tailwind-builder.enable_public_pages', false)) {
    Route::middleware(config('tailwind-builder.public_middleware', ['web']))
        ->prefix(config('tailwind-builder.public_route_prefix', 'pages'))
        ->group(function () {
            Route::get('/{slug}', RenderBuilderPageController::class)
                ->where('slug', '.*')
                ->name('tailwind-builder.render');
        });
}
