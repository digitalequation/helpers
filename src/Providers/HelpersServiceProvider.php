<?php

namespace DigitalEquation\Helpers\Providers;

use DigitalEquation\Helpers\Console\{ConfigCommand, InstallCommand};
use DigitalEquation\Helpers\Facades\{Helpers, PaginateHelper, ResponseHelper, RoleHelper};
use Illuminate\Support\ServiceProvider;

class HelpersServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any package services.
     */
    public function boot(): void
    {
        if (!config('helpers.enabled')) {
            return;
        }

        $this->registerPublishing();
        $this->registerCommands();
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register(): void
    {
        // Automatically apply the package configuration
        $this->mergeConfigFrom(__DIR__ . '/../../config/helpers.php', 'helpers');

        // Register helpers facades
        $this->registerFacades();
    }

    /**
     * Register the package facades.
     */
    private function registerFacades(): void
    {
        $this->app->singleton('helpers', function () {
            return new Helpers;
        });

        $this->app->singleton('paginate-helper', function () {
            return new PaginateHelper;
        });

        $this->app->singleton('role-helper', function () {
            return new RoleHelper;
        });

        $this->app->singleton('response-helper', function () {
            return new ResponseHelper;
        });
    }

    /**
     * Register the package artisan commands.
     */
    private function registerCommands(): void
    {
        $this->commands([ConfigCommand::class, InstallCommand::class,]);
    }

    /**
     * Register the package's publishable resources.
     */
    private function registerPublishing(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([__DIR__ . '/../../config/helpers.php' => config_path('helpers.php'),], 'helpers-config');

            $this->publishes([__DIR__ . '/../../stubs/app/Providers/HelpersServiceProvider.stub' => app_path('Providers/HelpersServiceProvider.php'),], 'helpers-provider');

            $this->publishes([__DIR__ . '/../../stubs/app/Providers/ExtendedDatabaseServiceProvider.stub' => app_path('Providers/ExtendedDatabaseServiceProvider.php'),], 'helpers-extended-db-provider');
        }
    }
}
