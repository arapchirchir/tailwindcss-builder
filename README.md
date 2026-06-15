# Tailwind CSS Builder

A developer-first Tailwind CSS page builder package for Laravel.

This package provides a foundation for creating and managing builder-driven pages, reusable components, assets, revisions, and settings.

The long-term goal is to provide an Elementor-like builder experience for Tailwind CSS and DaisyUI-based Laravel applications.

## Features

- Builder admin routes
- Install command
- Publishable config, migrations, and routes
- Separate `routes/builder.php` file
- Safe route registration into `routes/web.php`
- Page creation and management
- Navigation visibility support
- JSON-based page content
- Safe Tailwind renderer foundation
- Revision history foundation
- DaisyUI-ready admin views
- PHPUnit/Testbench test coverage

## Requirements

- PHP 8.2+
- Laravel 11 or 12
- Tailwind CSS
- DaisyUI recommended

## Installation

Install the package:

```bash
composer require tecworld/tailwind-builder
```

Publish package files:

```bash
php artisan tailwind-builder:install
```

Run migrations:

```bash
php artisan migrate
```

## Important Note

The install command must be run inside a Laravel application.

It will not work inside the package folder itself because a package does not have an `artisan` file.

Correct location:

```bash
cd /path/to/your-laravel-app
php artisan tailwind-builder:install
```

## DaisyUI Setup

The builder admin UI is designed for Tailwind CSS and DaisyUI.

Install DaisyUI:

```bash
npm i -D daisyui@latest
```

For Tailwind CSS v4, add this to your app CSS:

```css
@import "tailwindcss";
@plugin "daisyui";
```

For Tailwind CSS v3, configure DaisyUI in your Tailwind config according to the DaisyUI documentation.

## Routes

The installer publishes:

```txt
routes/builder.php
```

It also appends this line to `routes/web.php`:

```php
require __DIR__.'/builder.php';
```

This keeps builder routes separate from existing application routes.

The installer does not delete or replace your existing `routes/web.php` content.

## Admin URL

Default builder URL:

```txt
/admin/builder/pages
```

You can change the route prefix in:

```txt
config/tailwind-builder.php
```

Example:

```php
'route_prefix' => 'admin/builder',
```

## Authorization

Builder routes are protected by middleware and a gate.

Default middleware:

```php
['web', 'auth']
```

Default gate:

```php
viewTailwindBuilder
```

In local environments, access is allowed by default if no allowed emails are configured.

In production, add allowed emails in:

```php
'allowed_emails' => [
    'admin@example.com',
],
```

Or override the gate in your Laravel application:

```php
use Illuminate\Support\Facades\Gate;

public function boot(): void
{
    Gate::define('viewTailwindBuilder', function ($user) {
        return $user->is_admin === true;
    });
}
```

If you use Spatie Laravel Permission, you can do:

```php
use Illuminate\Support\Facades\Gate;

public function boot(): void
{
    Gate::define('viewTailwindBuilder', function ($user) {
        return $user->can('manage tailwind builder');
    });
}
```

## Configuration

After installation, the config file is published to:

```txt
config/tailwind-builder.php
```

Main options include:

```php
'route_prefix' => env('TAILWIND_BUILDER_ROUTE_PREFIX', 'admin/builder'),

'middleware' => ['web', 'auth'],

'public_middleware' => ['web'],

'gate' => 'viewTailwindBuilder',

'allowed_emails' => [
    // 'admin@example.com',
],

'tables' => [
    'pages' => 'twb_pages',
    'components' => 'twb_components',
    'assets' => 'twb_assets',
    'revisions' => 'twb_revisions',
    'settings' => 'twb_settings',
],

'storage_disk' => env('TAILWIND_BUILDER_STORAGE_DISK', 'public'),

'storage_path' => env('TAILWIND_BUILDER_STORAGE_PATH', 'tailwind-builder'),

'enable_public_pages' => env('TAILWIND_BUILDER_ENABLE_PUBLIC_PAGES', false),

'public_route_prefix' => env('TAILWIND_BUILDER_PUBLIC_ROUTE_PREFIX', 'pages'),

'ui' => [
    'preset' => env('TAILWIND_BUILDER_UI_PRESET', 'daisyui'),
    'require_daisyui' => env('TAILWIND_BUILDER_REQUIRE_DAISYUI', true),
    'icons' => env('TAILWIND_BUILDER_ICONS', 'inline'),
],
```

## Database Tables

The package currently creates:

```txt
twb_pages
twb_components
twb_assets
twb_revisions
twb_settings
```

### Pages

Pages are routeable documents.

Examples:

```txt
/home
/about
/services
/contact
```

Pages can be shown or hidden in navigation using:

```txt
show_in_navigation
navigation_label
navigation_order
navigation_parent_id
```

### Components

Components are reusable visual pieces.

Examples:

```txt
header
footer
section
element
global
```

Headers and footers are treated as reusable components, not pages.

### Assets

Assets store uploaded media metadata such as:

```txt
disk
path
filename
mime_type
size
alt_text
metadata_json
```

### Revisions

Revisions store edit history for pages and components.

### Settings

Settings store global builder options using key/value JSON.

## Public Page Rendering

Public page rendering is disabled by default to avoid overriding existing application routes.

Default:

```php
'enable_public_pages' => false,
```

If enabled, public builder pages are rendered under the configured prefix:

```php
'public_route_prefix' => 'pages',
```

Example URL:

```txt
/pages/about
```

This avoids conflicts with existing routes like:

```txt
/about
/contact
/services
```

## Current Builder Flow

The current foundation supports:

1. Create a page.
2. Set title, slug, type, status.
3. Enable or disable navigation visibility.
4. Set navigation label and order.
5. Set SEO title and description.
6. Edit temporary JSON content.
7. Publish or draft a page.
8. Render published pages through the renderer.

The visual drag-and-drop editor will be added after the foundation is stable.

## Development

Clone the repository:

```bash
git clone git@github.com:arapchirchir/tailwindcss-builder.git
cd tailwindcss-builder
```

Install dependencies:

```bash
composer install
```

Run tests:

```bash
vendor/bin/phpunit
```

Run formatting check:

```bash
vendor/bin/pint --test
```

Format code:

```bash
vendor/bin/pint
```

## Testing

The package uses:

```txt
orchestra/testbench
phpunit
laravel/pint
```

The current test suite verifies:

- Install command publishes files and registers routes.
- Builder page service creates unique slugs.
- Builder page service can publish pages.

## Git Notes

The `vendor/` directory is intentionally ignored.

Do not commit:

```txt
vendor/
.phpunit.result.cache
.pint.cache
.env
```

After cloning, run:

```bash
composer install
```

## Roadmap

Planned next features:

- Component management UI
- Asset upload manager
- Navigation builder
- Visual page editor canvas
- Pencil edit buttons
- Section/component library
- DaisyUI component presets
- Page preview mode
- Header and footer component assignment
- Global settings UI
- Restore from revisions
- Export rendered Tailwind HTML
- Optional layout/domain support in future versions

Future advanced architecture may include:

```txt
layouts
menus
menu_items
domain-specific layouts
aside/sidebar regions
theme presets
```

These are intentionally not part of the first foundation release.

## Current Status

This package is in early development.

The current version provides the production-ready foundation before the full visual editor is added.

## License

MIT
