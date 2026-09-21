<?php

namespace Sghazanfari\FilamentPersian\Forms\Components;

use Filament\Forms\Components\Select;
use Sghazanfari\FilamentPersian\Support\IranData;

class IranBankSelect extends Select
{
    public function setUp(): void
    {
        parent::setUp();

        $this
            ->label('بانک')
            ->options(fn () => IranData::banksForSelect())
            ->searchable()
            ->preload()
            ->native(false);
    }
}