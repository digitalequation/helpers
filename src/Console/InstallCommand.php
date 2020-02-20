<?php

namespace DigitalEquation\Helpers\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Str;

class InstallCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'helpers:install {--force : Force Helper to install even it has been already installed}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install the Helper scaffolding into the application';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        if ($this->providersAlreadyInstalled() && !$this->option('force')) {
            $this->line('Extended Database Service Provider is already installed for this project.');
        } else {
            $this->comment('Publishing Extended Database Service Provider...');
            $this->callSilent('vendor:publish', ['--tag' => 'helpers-extended-db-provider']);

            $this->comment('Publishing Helper Service Provider...');
            $this->callSilent('vendor:publish', ['--tag' => 'helpers-provider']);

            $this->comment('Publishing Helper Configuration...');
            $this->callSilent('vendor:publish', ['--tag' => 'helpers-config']);

            $this->registerServiceProviders();

            $this->info('Helper scaffolding installed successfully.');
        }
    }

    private function registerServiceProviders()
    {
        list($namespace, $appConfig, $eol) = $this->providersConfig();

        file_put_contents(config_path('app.php'), str_replace(
            "Illuminate\Database\DatabaseServiceProvider::class," . $eol,
            "// Illuminate\Database\DatabaseServiceProvider::class," . $eol . "        {$namespace}\Providers\ExtendedDatabaseServiceProvider::class," . $eol,
            $appConfig
        ));

        file_put_contents(app_path('Providers/ExtendedDatabaseServiceProvider.php'), str_replace(
            "namespace App\Providers;",
            "namespace {$namespace}\Providers;",
            file_get_contents(app_path('Providers/ExtendedDatabaseServiceProvider.php'))
        ));

        file_put_contents(config_path('app.php'), str_replace(
            "{$namespace}\\Providers\EventServiceProvider::class," . $eol,
            "{$namespace}\\Providers\EventServiceProvider::class," . $eol . "        {$namespace}\Providers\HelpersServiceProvider::class," . $eol,
            $appConfig
        ));

        file_put_contents(app_path('Providers/HelpersServiceProvider.php'), str_replace(
            "namespace App\Providers;",
            "namespace {$namespace}\Providers;",
            file_get_contents(app_path('Providers/HelpersServiceProvider.php'))
        ));
    }

    /**
     * Determine if package service providers are already installed.
     *
     * @return bool
     */
    protected function providersAlreadyInstalled(): bool
    {
        list($namespace, $appConfig) = $this->providersConfig();

        return Str::contains($appConfig, $namespace . '\\Providers\\ExtendedDatabaseServiceProvider::class') ||
            Str::contains($appConfig, $namespace . '\\Providers\\HelpersServiceProvider::class');
    }

    /**
     * @return array
     */
    private function providersConfig(): array
    {
        $namespace = Str::replaceLast('\\', '', app()->getNamespace());
        $appConfig = file_get_contents(config_path('app.php'));

        $lineEndingCount = [
            "\r\n" => substr_count($appConfig, "\r\n"),
            "\r" => substr_count($appConfig, "\r"),
            "\n" => substr_count($appConfig, "\n"),
        ];

        $eol = array_keys($lineEndingCount, max($lineEndingCount))[0];

        return array($namespace, $appConfig, $eol);
    }
}