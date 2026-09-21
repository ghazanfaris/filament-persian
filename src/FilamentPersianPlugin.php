<?php

namespace Sghazanfari\FilamentPersian;

use Filament\Contracts\Plugin;
use Filament\Panel;
use Sghazanfari\FilamentPersian\Pages\About;
use Sghazanfari\FilamentPersian\Pages\Documentation;
use Sghazanfari\FilamentPersian\Pages\EditProfile;
use Sghazanfari\FilamentPersian\Pages\FontSettings;
use Sghazanfari\FilamentPersian\Pages\ComponentsShowcase;

class FilamentPersianPlugin implements Plugin
{
    public static function make(): static
    {
        return app(static::class);
    }

    public function getId(): string
    {
        return 'filament-persian';
    }

    public function register(Panel $panel): void
    {
        $panel->pages([
            Documentation::class,
            ComponentsShowcase::class, 
            About::class,
            FontSettings::class,
            EditProfile::class,
        ]);
    }

    public function boot(Panel $panel): void
    {
        //
    }
}