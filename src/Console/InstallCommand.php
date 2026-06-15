<?php

namespace Tecworld\TailwindBuilder\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class InstallCommand extends Command
{
    protected $signature = 'tailwind-builder:install
                            {--force : Overwrite existing published files}
                            {--no-routes : Do not automatically append builder route registration to routes/web.php}';

    protected $description = 'Install the Tailwind Builder package';

    public function handle(): int
    {
        $this->info('Installing Tailwind Builder...');

        $publishOptions = [
            '--tag' => 'tailwind-builder',
        ];

        if ($this->option('force')) {
            $publishOptions['--force'] = true;
        }

        $this->call('vendor:publish', $publishOptions);

        if (! $this->option('no-routes')) {
            $this->appendBuilderRoutesRequire();
        } else {
            $this->components->warn('Skipped automatic route registration. Add this manually to routes/web.php:');
            $this->line("require __DIR__.'/builder.php';");
        }

        $this->newLine();

        $this->info('Tailwind Builder installed successfully.');

        $this->components->info('Next steps:');
        $this->line('php artisan migrate');

        $this->newLine();

        if (config('tailwind-builder.ui.require_daisyui', true)) {
            $this->components->info('DaisyUI setup:');
            $this->line('npm i -D daisyui@latest');
            $this->line('Then add DaisyUI to your Tailwind CSS setup.');
            $this->line('For Tailwind v4: add @plugin "daisyui"; to your app CSS.');
        }

        $this->newLine();

        $this->components->warn(
            'For production, set allowed_emails in config/tailwind-builder.php or override the viewTailwindBuilder gate.'
        );

        return self::SUCCESS;
    }

    protected function appendBuilderRoutesRequire(): void
    {
        $webRoutesPath = base_path('routes/web.php');
        $builderRoutesPath = base_path('routes/builder.php');
        $builderRequire = "require __DIR__.'/builder.php';";

        if (! File::exists($builderRoutesPath)) {
            $this->components->warn('routes/builder.php was not found. Publish routes or run the installer again with --force.');

            return;
        }

        if (! File::exists($webRoutesPath)) {
            File::ensureDirectoryExists(dirname($webRoutesPath));
            File::put($webRoutesPath, "<?php\n\n".$builderRequire."\n");

            $this->components->info('Created routes/web.php and registered builder routes.');

            return;
        }

        $contents = File::get($webRoutesPath);

        if (str_contains($contents, $builderRequire)) {
            $this->components->info('Builder routes are already registered in routes/web.php.');

            return;
        }

        $backupPath = $webRoutesPath.'.backup-'.now()->format('YmdHis');

        File::copy($webRoutesPath, $backupPath);

        $append = PHP_EOL.PHP_EOL.'// Tailwind Builder routes'.PHP_EOL.$builderRequire.PHP_EOL;

        File::append($webRoutesPath, $append);

        $this->components->info('Added builder route registration to routes/web.php.');
        $this->line('Backup created at: '.$backupPath);
    }
}
