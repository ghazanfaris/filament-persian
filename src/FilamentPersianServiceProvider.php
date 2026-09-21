<?php

namespace Sghazanfari\FilamentPersian;

use Illuminate\Support\ServiceProvider;
use Sghazanfari\FilamentPersian\Support\JalaliConverter;
use Sghazanfari\FilamentPersian\Macros\TableMacros;
use Sghazanfari\FilamentPersian\Commands\DoctorCommand;
use Sghazanfari\FilamentPersian\Commands\InstallCommand;
use Illuminate\Support\Facades\File;

use Sghazanfari\FilamentPersian\Commands\SeedCitiesCommand;
use Sghazanfari\FilamentPersian\Commands\HealthCheckCommand;

class FilamentPersianServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../config/filament-persian.php',
            'filament-persian'
        );
        $this->app->singleton(JalaliConverter::class);
    }

    public function boot(): void
    {
        // لود کردن ویوها
        $this->loadViewsFrom(
            __DIR__ . '/../resources/views',
            'filament-persian'
        );

        // انتشار config
        $this->publishes([
            __DIR__ . '/../config/filament-persian.php' => config_path('filament-persian.php'),
        ], 'filament-persian-config');

        // انتشار ویوها (اختیاری)
        $this->publishes([
            __DIR__ . '/../resources/views' => resource_path('views/vendor/filament-persian'),
        ], 'filament-persian-views');

        TableMacros::register();

        if ($this->app->runningInConsole()) {
            $this->commands([
                InstallCommand::class,
                DoctorCommand::class,
                SeedCitiesCommand::class,
                HealthCheckCommand::class,
            ]);
        }
        $this->registerMigrations();
    }

    protected function registerMigrations(): void
    {
        $migrationsPath = __DIR__ . '/../database/migrations';

        if (File::isDirectory($migrationsPath)) {
            $this->loadMigrationsFrom($migrationsPath);
        }
    }
}