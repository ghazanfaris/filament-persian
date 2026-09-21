<?php

namespace Sghazanfari\FilamentPersian\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class DoctorCommand extends Command
{
    protected $signature = 'filament-persian:doctor';

    protected $description = 'بررسی سلامت نصب Filament Persian';

    protected int $errors = 0;
    protected int $warnings = 0;
    protected int $passes = 0;

    public function handle(): int
    {
        $this->newLine();
        $this->line('<fg=cyan;options=bold>🏥 بررسی سلامت Filament Persian</>');
        $this->newLine();

        $this->checkPhpVersion();
        $this->checkLaravelVersion();
        $this->checkFilamentVersion();
        $this->checkConfig();
        $this->checkStorageFolder();
        $this->checkLocale();
        $this->checkTimezone();
        $this->checkAssets();
        $this->checkDocsFiles();
        $this->checkHelpers();
        $this->checkJalaliConverter();

        $this->newLine();
        $this->line('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━');
        $this->line(sprintf(
            '  <fg=green>✓ %d</> موفق   <fg=yellow>⚠ %d</> هشدار   <fg=red>✗ %d</> خطا',
            $this->passes,
            $this->warnings,
            $this->errors,
        ));
        $this->line('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━');
        $this->newLine();

        return $this->errors > 0 ? self::FAILURE : self::SUCCESS;
    }

    protected function passCheck(string $message): void
    {
        $this->passes++;
        $this->line("  <fg=green>✓</> {$message}");
    }

    protected function warnCheck(string $message, ?string $hint = null): void
    {
        $this->warnings++;
        $this->line("  <fg=yellow>⚠</> {$message}");
        if ($hint) {
            $this->line("     <fg=gray>↳ {$hint}</>");
        }
    }

    protected function failCheck(string $message, ?string $hint = null): void
    {
        $this->errors++;
        $this->line("  <fg=red>✗</> {$message}");
        if ($hint) {
            $this->line("     <fg=gray>↳ {$hint}</>");
        }
    }

    protected function checkPhpVersion(): void
    {
        $version = PHP_VERSION;
        if (version_compare($version, '8.2.0', '>=')) {
            $this->passCheck("PHP نسخه {$version}");
        } else {
            $this->failCheck("PHP نسخه {$version} — نیاز به 8.2+");
        }
    }

    protected function checkLaravelVersion(): void
    {
        $version = app()->version();
        $this->passCheck("Laravel نسخه {$version}");
    }

    protected function checkFilamentVersion(): void
    {
        if (! class_exists(\Filament\Panel::class)) {
            $this->failCheck('Filament نصب نیست');
            return;
        }

        try {
            $version = \Composer\InstalledVersions::getVersion('filament/filament') ?? 'نامشخص';
            $this->passCheck("Filament نسخه {$version}");
        } catch (\Throwable) {
            $this->warnCheck('نسخه Filament قابل تشخیص نیست');
        }
    }

    protected function checkConfig(): void
    {
        if (config('filament-persian') === null) {
            $this->failCheck(
                'فایل config لود نشده',
                'php artisan vendor:publish --tag=filament-persian-config'
            );
            return;
        }

        $this->passCheck('فایل config لود شده');

        $locale = config('filament-persian.locale');
        if ($locale === 'fa') {
            $this->passCheck("زبان پکیج: {$locale}");
        } else {
            $this->warnCheck("زبان پکیج: {$locale} (پیشنهاد: fa)");
        }
    }

    protected function checkStorageFolder(): void
    {
        $dir = storage_path('app/filament-persian');

        if (! File::isDirectory($dir)) {
            $this->warnCheck(
                'پوشه تنظیمات ساخته نشده',
                'خودکار در اولین ذخیره ساخته می‌شود'
            );
            return;
        }

        if (! File::isWritable($dir)) {
            $this->failCheck("پوشه تنظیمات قابل نوشتن نیست: {$dir}");
            return;
        }

        $this->passCheck('پوشه تنظیمات آماده است');
    }

    protected function checkLocale(): void
    {
        $appLocale = config('app.locale');
        if ($appLocale === 'fa') {
            $this->passCheck("App locale: {$appLocale}");
        } else {
            $this->warnCheck(
                "App locale: {$appLocale}",
                'در .env تنظیم کنید: APP_LOCALE=fa'
            );
        }
    }

    protected function checkTimezone(): void
    {
        $tz = config('app.timezone');
        if ($tz === 'Asia/Tehran') {
            $this->passCheck("Timezone: {$tz}");
        } else {
            $this->warnCheck(
                "Timezone: {$tz}",
                'در .env تنظیم کنید: APP_TIMEZONE=Asia/Tehran'
            );
        }
    }

    protected function checkAssets(): void
    {
        $files = [
            'jalali-calendar.js',
            'persian-numbers.js',
            'command-palette.js',
        ];

        $missing = [];
        foreach ($files as $file) {
            $path = public_path('vendor/filament-persian/js/' . $file);
            if (! File::exists($path)) {
                $missing[] = $file;
            }
        }

        if (empty($missing)) {
            $this->passCheck('Asset ها منتشر شده‌اند');
        } else {
            $this->warnCheck(
                'Asset های زیر منتشر نشده: ' . implode(', ', $missing),
                'php artisan filament-persian:install'
            );
        }
    }

    protected function checkDocsFiles(): void
    {
        $dir = __DIR__ . '/../../resources/docs';

        if (! File::isDirectory($dir)) {
            $this->failCheck('پوشه مستندات پیدا نشد');
            return;
        }

        $count = count(File::files($dir));
        if ($count > 0) {
            $this->passCheck("فایل‌های مستندات: {$count} فایل");
        } else {
            $this->warnCheck('پوشه مستندات خالی است');
        }
    }

    protected function checkHelpers(): void
    {
        if (function_exists('jalali')) {
            $this->passCheck('Helper ها لود شده‌اند');
        } else {
            $this->failCheck('Helper ها لود نشده‌اند', 'composer dump-autoload');
        }
    }

    protected function checkJalaliConverter(): void
    {
        try {
            $conv = app(\Sghazanfari\FilamentPersian\Support\JalaliConverter::class);
            [$y, $m, $d] = $conv->toJalali(2024, 8, 2);

            if ($y === 1403 && $m === 5 && $d === 12) {
                $this->passCheck('تبدیل تاریخ جلالی درست کار می‌کند');
            } else {
                $this->failCheck("تبدیل تاریخ اشتباه: [{$y}, {$m}, {$d}]");
            }
        } catch (\Throwable $e) {
            $this->failCheck('خطا در JalaliConverter: ' . $e->getMessage());
        }
    }
}