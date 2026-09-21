<?php

namespace Sghazanfari\FilamentPersian\Tables\Columns;

use Filament\Tables\Columns\TextColumn;
use Sghazanfari\FilamentPersian\Support\JalaliConverter;

class JalaliDateColumn extends TextColumn
{
    protected string $jalaliFormat = 'Y/m/d';

    public function jalaliFormat(string $format): static
    {
        $this->jalaliFormat = $format;
        return $this;
    }

    public function withTime(bool $withSeconds = false): static
    {
        $this->jalaliFormat = $withSeconds ? 'Y/m/d H:i:s' : 'Y/m/d H:i';
        return $this;
    }

    public function human(): static
    {
        $this->jalaliFormat = 'human';
        return $this;
    }

    public function setUp(): void
    {
        parent::setUp();

        $this->formatStateUsing(function ($state) {
            if (blank($state)) {
                return null;
            }

            $conv = app(JalaliConverter::class);

            if ($this->jalaliFormat === 'human') {
                return $conv->diffForHumans($state);
            }

            return $conv->format($state, $this->jalaliFormat);
        });

        $this->alignRight();
    }
}