<?php

namespace Sghazanfari\FilamentPersian\Forms\Components;

use Filament\Forms\Components\TextInput;
use Sghazanfari\FilamentPersian\Rules\Sheba;

class IranianShebaInput extends TextInput
{
    public function setUp(): void
    {
        parent::setUp();

        $this
            ->rule(new Sheba())
            ->placeholder('IR۰۰۰۰۰۰۰۰۰۰۰۰۰۰۰۰۰۰۰۰۰۰۰')
            ->maxLength(26)
            ->dehydrateStateUsing(function (?string $state): ?string {
                if (blank($state)) return null;
                return normalize_sheba($state) ?? $state;
            })
            ->extraInputAttributes([
                'x-on:input' => "
                    let v = \$event.target.value.toUpperCase().replace(/[^0-9A-Z]/g, '');
                    if (! v.startsWith('IR')) v = 'IR' + v.replace(/^IR/, '');
                    \$event.target.value = v.slice(0, 26);
                ",
                'dir' => 'ltr',
                'style' => 'text-align: left; font-family: monospace; letter-spacing: 1px;',
            ]);
    }
}