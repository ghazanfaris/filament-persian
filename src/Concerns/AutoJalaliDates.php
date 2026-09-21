<?php

namespace Sghazanfari\FilamentPersian\Concerns;

use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Sghazanfari\FilamentPersian\Tables\Columns\JalaliDateColumn;

trait AutoJalaliDates
{
    /**
     * همه ستون‌های تاریخ مدل (بر اساس casts) را خودکار به جلالی تبدیل می‌کند.
     *
     * استفاده در Resource:
     *
     *     public static function table(Table $table): Table
     *     {
     *         return static::autoJalaliDates(EventsTable::configure($table));
     *     }
     *
     * گزینه‌ها:
     *     ->autoJalaliDates($table, withTime: false)   // همه فقط تاریخ
     *     ->autoJalaliDates($table, except: ['birthday'])  // نادیده بگیر
     */
    public static function autoJalaliDates(
        Table $table,
        bool $withTime = true,
        array $except = [],
    ): Table {
        $modelClass = static::getModel();

        if (! $modelClass || ! class_exists($modelClass)) {
            return $table;
        }

        $instance = new $modelClass;
        $casts = $instance->getCasts();

        // پیدا کردن ستون‌های تاریخ از casts
        $dateColumns = [];
        $datetimeColumns = [];

        foreach ($casts as $column => $cast) {
            if (in_array($column, $except, true)) {
                continue;
            }

            if (in_array($cast, ['date', 'immutable_date'], true)) {
                $dateColumns[] = $column;
            }

            if (in_array($cast, ['datetime', 'immutable_datetime', 'timestamp'], true)) {
                $datetimeColumns[] = $column;
            }
        }

        // همیشه created_at و updated_at را در نظر بگیر (Laravel خودکار cast می‌کند)
        foreach (['created_at', 'updated_at', 'deleted_at'] as $ts) {
            if (
                ! in_array($ts, $except, true)
                && ! in_array($ts, $dateColumns, true)
                && ! in_array($ts, $datetimeColumns, true)
                && $instance->hasCast($ts, ['datetime', 'immutable_datetime', 'timestamp'])
            ) {
                $datetimeColumns[] = $ts;
            }
        }

        $allDateColumns = array_unique(array_merge($dateColumns, $datetimeColumns));

        if (empty($allDateColumns)) {
            return $table;
        }

        // بازنویسی ستون‌ها
        $columns = $table->getColumns();
        $newColumns = [];

        foreach ($columns as $column) {
            $name = $column->getName();

            if (! in_array($name, $allDateColumns, true)) {
                $newColumns[] = $column;
                continue;
            }

            if ($column instanceof JalaliDateColumn) {
                // از قبل جلالی است — دست نزن
                $newColumns[] = $column;
                continue;
            }

            // تبدیل به JalaliDateColumn
            $jalali = JalaliDateColumn::make($name);

            // کپی label از ستون اصلی
            if ($column instanceof TextColumn) {
                try {
                    $label = $column->getLabel();
                    if ($label) {
                        $jalali->label($label);
                    }
                } catch (\Throwable) {
                    // نادیده بگیر
                }
            }

            // زمان‌دار است؟
            $isDatetime = in_array($name, $datetimeColumns, true);
            if ($isDatetime && $withTime) {
                $jalali->withTime();
            }

            $jalali->sortable()->toggleable();

            $newColumns[] = $jalali;
        }

        return $table->columns($newColumns);
    }
}