<?php

namespace Sghazanfari\FilamentPersian\Macros;

use Filament\Tables\Table;
use Sghazanfari\FilamentPersian\Tables\Columns\JalaliDateColumn;

class TableMacros
{
    public static function register(): void
    {
        /**
         * تبدیل خودکار ستون‌های created_at و updated_at به جلالی.
         *
         * استفاده:
         *   ->columns([...])
         *   ->withJalaliTimestamps()
         *
         * گزینه‌ها:
         *   ->withJalaliTimestamps(withUpdatedAt: false)  // فقط created_at
         *   ->withJalaliTimestamps(withTime: false)       // فقط تاریخ، بدون ساعت
         */
        Table::macro('withJalaliTimestamps', function (
            bool $withUpdatedAt = true,
            bool $withTime = true,
            bool $withCreatedAt = true,
        ) {
            /** @var Table $this */
            $existing = $this->getColumns();
            $existingNames = array_map(fn ($c) => $c->getName(), $existing);

            $newColumns = $existing;

            if ($withCreatedAt && ! in_array('created_at', $existingNames)) {
                $column = JalaliDateColumn::make('created_at')
                    ->label('تاریخ ایجاد')
                    ->sortable()
                    ->toggleable();

                if ($withTime) {
                    $column->withTime();
                }

                $newColumns[] = $column;
            }

            if ($withUpdatedAt && ! in_array('updated_at', $existingNames)) {
                $column = JalaliDateColumn::make('updated_at')
                    ->label('آخرین ویرایش')
                    ->sortable()
                    ->toggleable();

                if ($withTime) {
                    $column->withTime();
                }

                $newColumns[] = $column;
            }

            return $this->columns($newColumns);
        });
    }
}