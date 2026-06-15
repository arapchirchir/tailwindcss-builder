<?php

namespace Tecworld\TailwindBuilder\Tests\Feature;

use Illuminate\Support\Facades\File;
use Tecworld\TailwindBuilder\Tests\TestCase;

class InstallCommandTest extends TestCase
{
    public function test_install_command_publishes_files_and_registers_routes(): void
    {
        File::ensureDirectoryExists(base_path('routes'));
        File::put(base_path('routes/web.php'), "<?php\n");

        $this->artisan('tailwind-builder:install')
            ->assertExitCode(0);

        $this->assertFileExists(config_path('tailwind-builder.php'));
        $this->assertFileExists(base_path('routes/builder.php'));

        $webRoutes = File::get(base_path('routes/web.php'));

        $this->assertStringContainsString(
            "require __DIR__.'/builder.php';",
            $webRoutes
        );
    }
}

