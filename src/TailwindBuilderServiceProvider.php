<?php

namespace Tecworld\TailwindBuilder;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Tecworld\TailwindBuilder\Console\InstallCommand;

class TailwindBuilderServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/tailwind-builder.php',
            'tailwind-builder'
        );
    }

    public function boot(): void
    {
        $this->registerAuthorizationGate();

        $this->loadViewsFrom(
            __DIR__.'/../resources/views',
            'tailwind-builder'
        );

        $this->publishes([
            __DIR__.'/../config/tailwind-builder.php' => config_path('tailwind-builder.php'),
            __DIR__.'/../database/migrations' => database_path('migrations'),
            __DIR__.'/../routes/builder.php' => base_path('routes/builder.php'),
        ], 'tailwind-builder');

        $this->publishes([
            __DIR__.'/../config/tailwind-builder.php' => config_path('tailwind-builder.php'),
        ], 'tailwind-builder-config');

        $this->publishes([
            __DIR__.'/../database/migrations' => database_path('migrations'),
        ], 'tailwind-builder-migrations');

        $this->publishes([
            __DIR__.'/../routes/builder.php' => base_path('routes/builder.php'),
        ], 'tailwind-builder-routes');

        if ($this->app->runningInConsole()) {
            $this->commands([
                InstallCommand::class,
            ]);
        }
    }

    protected function registerAuthorizationGate(): void
    {
        Gate::define('viewTailwindBuilder', function ($user): bool {
            $allowedEmails = config('tailwind-builder.allowed_emails', []);

            if (app()->environment('local') && empty($allowedEmails)) {
                return true;
            }

            if (! isset($user->email)) {
                return false;
            }

            return in_array($user->email, $allowedEmails, true);
        });
    }
}
