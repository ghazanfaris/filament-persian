<?php

namespace Sghazanfari\FilamentPersian\Forms\Components;

use Filament\Forms\Components\TextInput;
use Sghazanfari\FilamentPersian\Rules\VehiclePlate;
use Sghazanfari\FilamentPersian\Support\JalaliConverter;

class IranianVehiclePlateInput extends TextInput
{
    public function setUp(): void
    {
        parent::setUp();

        $this
            ->rule(new VehiclePlate())
            ->placeholder('۱۲ب۳۴۵ ایران ۶۷')
            ->maxLength(30)
            ->dehydrateStateUsing(function (?string $state): ?string {
                if (blank($state)) return null;
                return app(JalaliConverter::class)->toLatinDigits($state);
            })
            ->extraInputAttributes([
                'dir' => 'rtl',
                'style' => 'text-align: center; font-family: inherit; letter-spacing: 1px;',
            ]);
    }
}