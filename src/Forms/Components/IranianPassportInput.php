<?php

namespace Sghazanfari\FilamentPersian\Forms\Components;

use Filament\Forms\Components\TextInput;
use Sghazanfari\FilamentPersian\Rules\PassportNumber;
use Sghazanfari\FilamentPersian\Support\JalaliConverter;

class IranianPassportInput extends TextInput
{
    public function setUp(): void
    {
        parent::setUp();

        $this
            ->rule(new PassportNumber())
            ->placeholder('A12345678 یا 123456789')
            ->maxLength(10)
            ->dehydrateStateUsing(function (?string $state): ?string {
                if (blank($state)) return null;
                return app(JalaliConverter::class)->toLatinDigits($state);
            })
            ->extraInputAttributes([
                'x-on:input' => "
                    let v = \$event.target.value.toUpperCase()
                        .replace(/[۰-۹]/g, d => '۰۱۲۳۴۵۶۷۸۹'.indexOf(d))
                        .replace(/[^0-9A-Z]/g, '')
                        .slice(0, 10);
                    \$event.target.value = v;
                ",
                'dir' => 'ltr',
                'style' => 'text-align: left; font-family: monospace; letter-spacing: 2px;',
            ]);
    }
}