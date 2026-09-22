<?php

namespace Sghazanfari\FilamentPersian;

use Filament\Contracts\Plugin;
use Filament\Panel;
use Filament\View\PanelsRenderHook;
use Illuminate\Support\Facades\Blade;
use Sghazanfari\FilamentPersian\Pages\About;
use Sghazanfari\FilamentPersian\Pages\ComponentsShowcase;
use Sghazanfari\FilamentPersian\Pages\Documentation;
use Sghazanfari\FilamentPersian\Pages\EditProfile;
use Sghazanfari\FilamentPersian\Pages\FontSettings;
use Sghazanfari\FilamentPersian\Settings\FontManager;

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

        // لود JS تقویم و CSS
        $panel->renderHook(
            PanelsRenderHook::HEAD_END,
            fn () => $this->renderHeadAssets()
        );

        // لود JS اعداد فارسی و Command Palette
        $panel->renderHook(
            PanelsRenderHook::BODY_END,
            fn () => $this->renderBodyAssets()
        );
    }

    public function boot(Panel $panel): void
    {
        //
    }

    /**
     * CSS و JS تقویم را در <head> تزریق می‌کند.
     */
    protected function renderHeadAssets(): string
    {
        $calendarJsPath = public_path('vendor/filament-persian/js/jalali-calendar.js');
        $commandPaletteJsPath = public_path('vendor/filament-persian/js/command-palette.js');
        $cssPath = base_path('packages/sghazanfari/filament-persian/resources/css/filament-persian.css');

        $calendarJs = file_exists($calendarJsPath) ? file_get_contents($calendarJsPath) : '';
        $commandPaletteJs = file_exists($commandPaletteJsPath) ? file_get_contents($commandPaletteJsPath) : '';
        $css = file_exists($cssPath) ? file_get_contents($cssPath) : '';

        $html = '';
        if ($css) {
            $html .= "<style>{$css}</style>";
        }
        if ($calendarJs) {
            $html .= "<script>{$calendarJs}</script>";
        }
        if ($commandPaletteJs) {
            $html .= "<script>{$commandPaletteJs}</script>";
        }

        return $html;
    }

    /**
     * JS اعداد فارسی و Command Palette HTML را در <body> تزریق می‌کند.
     */
    protected function renderBodyAssets(): string
    {
        $html = '';

        // Command Palette HTML
        $commandPaletteView = base_path('packages/sghazanfari/filament-persian/resources/views/command-palette.blade.php');
        if (file_exists($commandPaletteView)) {
            $html .= file_get_contents($commandPaletteView);
        }

        // اعداد فارسی
        if (FontManager::persianNumbersEnabled()) {
            $persianNumbersJs = public_path('vendor/filament-persian/js/persian-numbers.js');
            if (file_exists($persianNumbersJs)) {
                $js = file_get_contents($persianNumbersJs);
                $html .= "<script>{$js}</script>";
            }
        }

        return $html;
    }
}