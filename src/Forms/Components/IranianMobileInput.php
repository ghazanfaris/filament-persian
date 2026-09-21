<?php

namespace Sghazanfari\FilamentPersian\Forms\Components;

use Filament\Forms\Components\TextInput;
use Sghazanfari\FilamentPersian\Rules\Mobile;
use Sghazanfari\FilamentPersian\Support\JalaliConverter;

class IranianMobileInput extends TextInput
{
    public function setUp(): void
    {
        parent::setUp();

        $this
            ->rule(new Mobile())
            ->placeholder('۰۹۱۲۳۴۵۶۷۸۹')
            ->tel()
            ->maxLength(11)
            ->inputMode('numeric')
            ->live(onBlur: true)
            ->formatStateUsing(function (?string $state): ?string {
                return $state;
            })
            ->dehydrateStateUsing(function (?string $state): ?string {
                if (blank($state)) return null;
                return normalize_mobile($state) ?? $state;
            })
            ->extraInputAttributes([
                'x-on:input' => "
                    \$event.target.value = \$event.target.value
                        .replace(/[۰-۹]/g, d => '۰۱۲۳۴۵۶۷۸۹'.indexOf(d))
                        .replace(/[^0-9]/g, '')
                        .slice(0, 11);
                ",
                'dir' => 'ltr',
                'style' => 'text-align: left; font-family: monospace;',
            ]);
    }
}