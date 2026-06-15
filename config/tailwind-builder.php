<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Route Prefix
    |--------------------------------------------------------------------------
    |
    | This prefix is used for the builder admin routes.
    |
    */

    'route_prefix' => env('TAILWIND_BUILDER_ROUTE_PREFIX', 'admin/builder'),

    /*
    |--------------------------------------------------------------------------
    | Builder Route Middleware
    |--------------------------------------------------------------------------
    |
    | These middleware are applied to the admin/editor routes.
    | Keep "web" and "auth" by default.
    |
    */

    'middleware' => ['web', 'auth'],

    /*
    |--------------------------------------------------------------------------
    | Public Page Middleware
    |--------------------------------------------------------------------------
    |
    | These middleware are applied to public rendered pages.
    |
    */

    'public_middleware' => ['web'],

    /*
    |--------------------------------------------------------------------------
    | Authorization Gate
    |--------------------------------------------------------------------------
    |
    | This gate controls who can access the builder.
    | The host app may override this gate in AppServiceProvider.
    |
    */

    'gate' => 'viewTailwindBuilder',

    /*
    |--------------------------------------------------------------------------
    | Allowed Emails
    |--------------------------------------------------------------------------
    |
    | Used by the default authorization gate.
    | In local environment, access is allowed by default.
    | In production, add allowed admin emails here or override the gate.
    |
    */

    'allowed_emails' => [
        // 'admin@example.com',
    ],

    /*
    |--------------------------------------------------------------------------
    | Database Tables
    |--------------------------------------------------------------------------
    |
    | These are the default table names used by the package.
    |
    */

    'tables' => [
        'pages' => 'twb_pages',
        'components' => 'twb_components',
        'assets' => 'twb_assets',
        'revisions' => 'twb_revisions',
        'settings' => 'twb_settings',
    ],

    /*
    |--------------------------------------------------------------------------
    | Storage
    |--------------------------------------------------------------------------
    |
    | Used for images and other uploaded builder assets.
    |
    */

    'storage_disk' => env('TAILWIND_BUILDER_STORAGE_DISK', 'public'),

    'storage_path' => env('TAILWIND_BUILDER_STORAGE_PATH', 'tailwind-builder'),

    /*
    |--------------------------------------------------------------------------
    | Public Page Rendering
    |--------------------------------------------------------------------------
    |
    | If enabled, the package can render published pages by slug.
    |
    */

    'enable_public_pages' => env('TAILWIND_BUILDER_ENABLE_PUBLIC_PAGES', false),

    'public_route_prefix' => env('TAILWIND_BUILDER_PUBLIC_ROUTE_PREFIX', 'pages'),
    /*
|--------------------------------------------------------------------------
| UI Preset
|--------------------------------------------------------------------------
|
| The builder admin UI is designed for Tailwind CSS and DaisyUI.
| The package will not automatically modify package.json or Tailwind files.
|
*/

    'ui' => [
        'preset' => env('TAILWIND_BUILDER_UI_PRESET', 'daisyui'),

        'require_daisyui' => env('TAILWIND_BUILDER_REQUIRE_DAISYUI', true),

        'icons' => env('TAILWIND_BUILDER_ICONS', 'inline'),
    ],

];
