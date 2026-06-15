<?php

namespace Tecworld\TailwindBuilder\Tests;

use Orchestra\Testbench\TestCase as Orchestra;
use Tecworld\TailwindBuilder\TailwindBuilderServiceProvider;

abstract class TestCase extends Orchestra
{
    protected function getPackageProviders($app): array
    {
        return [
            TailwindBuilderServiceProvider::class,
        ];
    }

    protected function defineEnvironment($app): void
    {
        $app['config']->set('database.default', 'testing');

        $app['config']->set('database.connections.testing', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);

        $app['config']->set('tailwind-builder.middleware', ['web']);
        $app['config']->set('tailwind-builder.allowed_emails', []);
    }

    protected function defineDatabaseMigrations(): void
    {
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
    }
}
