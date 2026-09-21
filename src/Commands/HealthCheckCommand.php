<?php

namespace Sghazanfari\FilamentPersian\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;

class HealthCheckCommand extends Command
{
    protected $signature = 'filament-persian:health-check
                            {--detailed : نمایش جزئیات هر بخش}
                            {--json : خروجی به‌صورت JSON}';

    protected $description = 'بررسی جامع سلامت نصب و پیکربندی Filament Persian';

    protected array $results = [];
    protected int $passes = 0;
    protected int $warnings = 0;
    protected int $errors = 0;
    protected int $skipped = 0;

    public function handle(): int
    {
        if ($this->option('json')) {
            return $this->handleJson();
        }

        $this->renderHeader();

        // بخش‌ها
        $this->checkEnvironment();
        $this->checkPackageFiles();
        $this->checkConfiguration();
        $this->checkAssets();
        $this->checkDatabase();
        $this->checkDataFiles();
        $this->checkClassFiles();
        $this->checkFonts();
        $this->checkAuthentication();
        $this->checkDocumentation();
        $this->checkCommands();
        $this->checkPermissions();

        $this->renderSummary();

        return $this->errors > 0 ? self::FAILURE : self::SUCCESS;
    }

    // =========================================================
    // رندر
    // =========================================================

    protected function renderHeader(): void
    {
        $this->newLine();
        $this->line('  <fg=cyan;options=bold>🏥 Filament Persian — بررسی جامع سلامت</>');
        $this->line('  <fg=gray>نسخه 1.0.0</>');
        $this->newLine();
    }

    protected function section(string $title, string $icon): void
    {
        $this->newLine();
        $this->line("  <fg=yellow;options=bold>{$icon} {$title}</>");
        $this->line('  <fg=gray>' . str_repeat('─', 50) . '</>');
    }

    protected function passCheck(string $label, ?string $value = null): void
    {
        $this->passes++;
        $this->results[] = ['status' => 'pass', 'label' => $label, 'value' => $value];

        $suffix = $value ? " <fg=gray>({$value})</>" : '';
        $this->line("    <fg=green>✓</> {$label}{$suffix}");
    }

    protected function warnCheck(string $label, ?string $hint = null): void
    {
        $this->warnings++;
        $this->results[] = ['status' => 'warn', 'label' => $label, 'value' => $hint];

        $this->line("    <fg=yellow>⚠</> {$label}");
        if ($hint) {
            $this->line("      <fg=gray>↳ {$hint}</>");
        }
    }

    protected function failCheck(string $label, ?string $hint = null): void
    {
        $this->errors++;
        $this->results[] = ['status' => 'fail', 'label' => $label, 'value' => $hint];

        $this->line("    <fg=red>✗</> {$label}");
        if ($hint) {
            $this->line("      <fg=gray>↳ {$hint}</>");
        }
    }

    protected function skip(string $label): void
    {
        $this->skipped++;
        $this->results[] = ['status' => 'skip', 'label' => $label];

        $this->line("    <fg=gray>⊘ {$label}</>");
    }

    protected function renderSummary(): void
    {
        $this->newLine(2);
        $this->line('  <fg=cyan;options=bold>📊 خلاصه</>');
        $this->line('  ' . str_repeat('━', 50));
        $this->line(sprintf(
            '    <fg=green>✓ %d موفق</>    <fg=yellow>⚠ %d هشدار</>    <fg=red>✗ %d خطا</>    <fg=gray>⊘ %d رد شده</>',
            $this->passes,
            $this->warnings,
            $this->errors,
            $this->skipped,
        ));
        $this->line('  ' . str_repeat('━', 50));
        $this->newLine();

        if ($this->errors === 0 && $this->warnings === 0) {
            $this->line('  <fg=green;options=bold>🎉 همه چیز عالی است! پکیج آماده استفاده است.</>');
        } elseif ($this->errors === 0) {
            $this->line('  <fg=yellow;options=bold>✅ پکیج کار می‌کند ولی چند هشدار کوچک دارد.</>');
        } else {
            $this->line('  <fg=red;options=bold>❌ پکیج نیاز به رفع خطا دارد. پیام‌های بالا را ببین.</>');
        }

        $this->newLine();
    }

    // =========================================================
    // بخش ۱ — محیط
    // =========================================================

    protected function checkEnvironment(): void
    {
        $this->section('محیط اجرا', '🌍');

        // PHP
        $phpVersion = PHP_VERSION;
        if (version_compare($phpVersion, '8.2.0', '>=')) {
            $this->passCheck('PHP', $phpVersion);
        } else {
            $this->failCheck('PHP', "نسخه {$phpVersion} — نیاز به 8.2+");
        }

        // Laravel
        $laravelVersion = app()->version();
        $this->passCheck('Laravel', $laravelVersion);

        // Filament
        try {
            $filamentVersion = \Composer\InstalledVersions::getVersion('filament/filament') ?? 'نامشخص';
            $this->passCheck('Filament', $filamentVersion);
        } catch (\Throwable) {
            $this->failCheck('Filament', 'قابل تشخیص نیست');
        }

        // Timezone
        $tz = config('app.timezone');
        if ($tz === 'Asia/Tehran') {
            $this->passCheck('Timezone', $tz);
        } else {
            $this->warnCheck('Timezone', "مقدار فعلی: {$tz} — پیشنهاد: Asia/Tehran");
        }

        // Locale
        $locale = config('app.locale');
        if ($locale === 'fa') {
            $this->passCheck('Locale', $locale);
        } else {
            $this->warnCheck('Locale', "مقدار فعلی: {$locale} — پیشنهاد: fa");
        }
    }

    // =========================================================
    // بخش ۲ — فایل‌های پکیج
    // =========================================================

    protected function checkPackageFiles(): void
    {
        $this->section('فایل‌های پکیج', '📦');

        $basePath = dirname(__DIR__, 2);
        $criticalFiles = [
            'composer.json' => 'Composer configuration',
            'README.md' => 'README',
            'LICENSE.md' => 'License',
            'config/filament-persian.php' => 'Config',
        ];

        foreach ($criticalFiles as $file => $label) {
            $path = $basePath . '/' . $file;
            if (File::exists($path)) {
                $this->passCheck($label);
            } else {
                $this->failCheck($label, "فایل یافت نشد: {$file}");
            }
        }
    }

    // =========================================================
    // بخش ۳ — پیکربندی
    // =========================================================

    protected function checkConfiguration(): void
    {
        $this->section('پیکربندی', '⚙️');

        if (config('filament-persian') === null) {
            $this->failCheck('Config لود شده', 'php artisan vendor:publish --tag=filament-persian-config');
            return;
        }
        $this->passCheck('Config لود شده');

        $checks = [
            'locale' => 'fa',
            'direction' => 'rtl',
        ];

        foreach ($checks as $key => $expected) {
            $value = config("filament-persian.{$key}");
            if ($value === $expected) {
                $this->passCheck("Config: {$key}", $value);
            } else {
                $this->warnCheck("Config: {$key}", "مقدار: {$value} — پیشنهاد: {$expected}");
            }
        }

        // پوشه تنظیمات
        $settingsDir = storage_path('app/filament-persian');
        if (File::isDirectory($settingsDir)) {
            if (File::isWritable($settingsDir)) {
                $this->passCheck('پوشه تنظیمات قابل نوشتن');
            } else {
                $this->failCheck('پوشه تنظیمات قابل نوشتن نیست', $settingsDir);
            }
        } else {
            $this->warnCheck('پوشه تنظیمات ساخته نشده', 'خودکار در اولین ذخیره ساخته می‌شود');
        }
    }

    // =========================================================
    // بخش ۴ — Asset ها
    // =========================================================

    protected function checkAssets(): void
    {
        $this->section('فایل‌های Asset (JS)', '📜');

        $files = [
            'jalali-calendar.js' => 'تقویم جلالی',
            'persian-numbers.js' => 'اعداد فارسی',
            'command-palette.js' => 'Command Palette',
        ];

        foreach ($files as $file => $label) {
            $path = public_path("vendor/filament-persian/js/{$file}");
            if (File::exists($path)) {
                $size = round(File::size($path) / 1024, 1);
                $this->passCheck($label, "{$size} KB");
            } else {
                $this->failCheck($label, "php artisan filament-persian:install");
            }
        }
    }

    // =========================================================
    // بخش ۵ — دیتابیس
    // =========================================================

    protected function checkDatabase(): void
    {
        $this->section('دیتابیس', '🗄️');

        try {
            if (Schema::hasTable('iran_cities')) {
                $count = \Sghazanfari\FilamentPersian\Models\IranCity::count();
                if ($count > 0) {
                    $this->passCheck('جدول iran_cities', "{$count} شهر");
                } else {
                    $this->warnCheck('جدول iran_cities خالی است', 'php artisan filament-persian:seed-cities');
                }
            } else {
                $this->failCheck('جدول iran_cities یافت نشد', 'php artisan migrate');
            }
        } catch (\Throwable $e) {
            $this->failCheck('اتصال به دیتابیس', $e->getMessage());
        }

        // جدول‌های Shield
        if (Schema::hasTable('roles') && Schema::hasTable('permissions')) {
            $this->passCheck('جدول‌های Spatie Permission');
        } else {
            $this->skip('Spatie Permission نصب نیست');
        }
    }

    // =========================================================
    // بخش ۶ — فایل‌های داده
    // =========================================================

    protected function checkDataFiles(): void
    {
        $this->section('فایل‌های داده ایران', '📊');

        $basePath = dirname(__DIR__, 2);
        $files = [
            'provinces.php' => 'استان‌ها',
            'cities.php' => 'شهرها',
            'banks.php' => 'بانک‌ها',
            'tehran_regions.php' => 'مناطق تهران',
            'solar_holidays.php' => 'تعطیلات شمسی',
            'lunar_holidays.php' => 'تعطیلات قمری',
        ];

        foreach ($files as $file => $label) {
            $path = "{$basePath}/resources/data/{$file}";
            if (File::exists($path)) {
                $data = require $path;
                $count = count($data);
                $this->passCheck($label, "{$count} مورد");
            } else {
                $this->failCheck($label, "فایل یافت نشد: {$file}");
            }
        }
    }

    // =========================================================
    // بخش ۷ — کلاس‌های اصلی
    // =========================================================

    protected function checkClassFiles(): void
    {
        $this->section('کلاس‌های اصلی', '🔧');

        $classes = [
            'JalaliConverter' => \Sghazanfari\FilamentPersian\Support\JalaliConverter::class,
            'IranData' => \Sghazanfari\FilamentPersian\Support\IranData::class,
            'IranHolidays' => \Sghazanfari\FilamentPersian\Support\IranHolidays::class,
            'WorkingHours' => \Sghazanfari\FilamentPersian\Support\WorkingHours::class,
            'PersianString' => \Sghazanfari\FilamentPersian\Support\PersianString::class,
            'FontManager' => \Sghazanfari\FilamentPersian\Settings\FontManager::class,
            'FilamentPersianPlugin' => \Sghazanfari\FilamentPersian\FilamentPersianPlugin::class,
            'Login' => \Sghazanfari\FilamentPersian\Auth\Login::class,
        ];

        foreach ($classes as $label => $class) {
            if (class_exists($class)) {
                $this->passCheck($label);
            } else {
                $this->failCheck($label, "کلاس یافت نشد: {$class}");
            }
        }

        // تست تبدیل تاریخ
        try {
            $conv = app(\Sghazanfari\FilamentPersian\Support\JalaliConverter::class);
            [$y, $m, $d] = $conv->toJalali(2024, 8, 2);

            if ($y === 1403 && $m === 5 && $d === 12) {
                $this->passCheck('تبدیل تاریخ جلالی');
            } else {
                $this->failCheck('تبدیل تاریخ جلالی', "خروجی: [{$y}, {$m}, {$d}]");
            }
        } catch (\Throwable $e) {
            $this->failCheck('تبدیل تاریخ جلالی', $e->getMessage());
        }

        // تست Helper
        if (function_exists('jalali')) {
            $this->passCheck('Helper Functions');
        } else {
            $this->failCheck('Helper Functions', 'composer dump-autoload');
        }
    }

    // =========================================================
    // بخش ۸ — فونت‌ها
    // =========================================================

    protected function checkFonts(): void
    {
        $this->section('فونت‌ها', '✍️');

        $fontsDir = public_path('fonts');
        if (! File::isDirectory($fontsDir)) {
            $this->warnCheck('پوشه فونت‌ها ساخته نشده', "public/fonts/ — می‌توانی فونت‌های فارسی بگذاری");
            return;
        }

        $fontDirs = File::directories($fontsDir);
        if (empty($fontDirs)) {
            $this->warnCheck('هیچ فونتی در public/fonts/ نیست');
            return;
        }

        $foundFonts = [];
        foreach ($fontDirs as $dir) {
            $name = basename($dir);
            $cssFile = $dir . '/' . $name . '.css';
            if (File::exists($cssFile)) {
                $foundFonts[] = $name;
            }
        }

        if (! empty($foundFonts)) {
            $this->passCheck('فونت‌های آماده', count($foundFonts) . ' عدد: ' . implode(', ', $foundFonts));
        } else {
            $this->warnCheck('هیچ فونت معتبری یافت نشد', 'برای هر فونت، فایل CSS با نام پوشه لازم است');
        }

        // فونت فعلی
        $currentFont = \Sghazanfari\FilamentPersian\Settings\FontManager::currentFont();
        if ($currentFont) {
            $this->passCheck('فونت فعلی', $currentFont['name']);
        } else {
            $this->warnCheck('هیچ فونتی انتخاب نشده');
        }
    }

    // =========================================================
    // بخش ۹ — احراز هویت
    // =========================================================

    protected function checkAuthentication(): void
    {
        $this->section('احراز هویت و صفحه ورود', '🔐');

        // قالب ورود
        $template = \Sghazanfari\FilamentPersian\Settings\FontManager::loginTemplate();
        $templates = \Sghazanfari\FilamentPersian\Settings\FontManager::loginTemplates();

        if (isset($templates[$template])) {
            $this->passCheck('قالب صفحه ورود', $templates[$template]['name']);
        } else {
            $this->warnCheck('قالب نامعتبر', $template);
        }

        // فایل‌های Blade قالب‌ها
        $basePath = dirname(__DIR__, 2);
        $templatesDir = "{$basePath}/resources/views/auth/layouts";
        if (File::isDirectory($templatesDir)) {
            $count = count(File::files($templatesDir));
            $this->passCheck('فایل‌های قالب ورود', "{$count} قالب");
        } else {
            $this->failCheck('پوشه قالب‌های ورود یافت نشد');
        }
    }

    // =========================================================
    // بخش ۱۰ — مستندات
    // =========================================================

    protected function checkDocumentation(): void
    {
        $this->section('مستندات', '📖');

        $basePath = dirname(__DIR__, 2);
        $docsDir = "{$basePath}/resources/docs";

        if (! File::isDirectory($docsDir)) {
            $this->failCheck('پوشه مستندات یافت نشد');
            return;
        }

        $docs = File::files($docsDir);
        if (count($docs) > 0) {
            $this->passCheck('فایل‌های مستندات', count($docs) . ' فایل');
        } else {
            $this->warnCheck('پوشه مستندات خالی است');
        }
    }

    // =========================================================
    // بخش ۱۱ — دستورات
    // =========================================================

    protected function checkCommands(): void
    {
        $this->section('دستورات Artisan', '⌨️');

        $commands = [
            'filament-persian:install',
            'filament-persian:doctor',
            'filament-persian:seed-cities',
            'filament-persian:health-check',
        ];

        foreach ($commands as $command) {
            if (array_key_exists($command, \Illuminate\Support\Facades\Artisan::all())) {
                $this->passCheck($command);
            } else {
                $this->failCheck($command, 'ثبت نشده');
            }
        }
    }

    // =========================================================
    // بخش ۱۲ — مجوزها
    // =========================================================

    protected function checkPermissions(): void
    {
        $this->section('مجوزها و دسترسی', '🔒');

        // Storage
        $storageDir = storage_path('app/filament-persian');
        if (File::isDirectory($storageDir) && File::isWritable($storageDir)) {
            $this->passCheck('Storage قابل نوشتن');
        } else {
            $this->warnCheck('Storage قابل نوشتن نیست', $storageDir);
        }

        // Public
        $publicDir = public_path('vendor/filament-persian');
        if (File::isDirectory($publicDir)) {
            $this->passCheck('پوشه Public منتشر شده');
        } else {
            $this->warnCheck('Public منتشر نشده', 'php artisan filament-persian:install');
        }

        // Shield (اختیاری)
        if (class_exists(\BezhanSalleh\FilamentShield\FilamentShieldPlugin::class)) {
            $this->passCheck('Filament Shield نصب شده');
        } else {
            $this->skip('Filament Shield نصب نیست (اختیاری)');
        }
    }

    // =========================================================
    // JSON output
    // =========================================================

    protected function handleJson(): int
    {
        // فقط چک‌ها را اجرا کن بدون render
        $this->checkEnvironment();
        $this->checkConfiguration();
        $this->checkAssets();
        $this->checkDatabase();
        $this->checkDataFiles();
        $this->checkClassFiles();
        $this->checkFonts();
        $this->checkAuthentication();
        $this->checkDocumentation();
        $this->checkCommands();

        $output = [
            'summary' => [
                'passes' => $this->passes,
                'warnings' => $this->warnings,
                'errors' => $this->errors,
                'skipped' => $this->skipped,
                'status' => $this->errors > 0 ? 'failed' : ($this->warnings > 0 ? 'warning' : 'passed'),
            ],
            'checks' => $this->results,
        ];

        $this->line(json_encode($output, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

        return $this->errors > 0 ? self::FAILURE : self::SUCCESS;
    }
}