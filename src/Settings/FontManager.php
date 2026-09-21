<?php

namespace Sghazanfari\FilamentPersian\Settings;

class FontManager
{
    protected static array $cache = [];

    // =========================================================
    // فونت‌ها
    // =========================================================

    public static function availableFonts(): array
    {
        $fontsPath = public_path('fonts');
        if (! is_dir($fontsPath)) {
            return [];
        }

        $fonts = [];
        foreach (scandir($fontsPath) as $folder) {
            if ($folder === '.' || $folder === '..') {
                continue;
            }

            $folderPath = $fontsPath . DIRECTORY_SEPARATOR . $folder;
            if (! is_dir($folderPath)) {
                continue;
            }

            $cssFile = $folderPath . DIRECTORY_SEPARATOR . $folder . '.css';
            if (! file_exists($cssFile)) {
                continue;
            }

            $displayName = ucfirst($folder);

            $fonts[$folder] = [
                'name'   => $displayName,
                'family' => $displayName,
                'css'    => "fonts/{$folder}/{$folder}.css",
                'url'    => asset("fonts/{$folder}/{$folder}.css"),
            ];
        }

        return $fonts;
    }

    public static function currentFont(): ?array
    {
        $key = self::get('font');
        $fonts = self::availableFonts();

        if ($key && isset($fonts[$key])) {
            return $fonts[$key];
        }

        return $fonts ? reset($fonts) : null;
    }

    public static function setFont(string $key): bool
    {
        $fonts = self::availableFonts();
        if (! isset($fonts[$key])) {
            return false;
        }

        self::set('font', $key);
        return true;
    }

    // =========================================================
    // رنگ‌ها
    // =========================================================

    public static function colorPalette(): array
    {
        return [
            'sky'     => ['name' => 'آبی آسمانی', 'value' => '#0ea5e9'],
            'violet'  => ['name' => 'بنفش',       'value' => '#8b5cf6'],
            'emerald' => ['name' => 'سبز زمرد',   'value' => '#10b981'],
            'rose'    => ['name' => 'سرخ',        'value' => '#f43f5e'],
            'amber'   => ['name' => 'کهربایی',    'value' => '#f59e0b'],
            'orange'  => ['name' => 'نارنجی',     'value' => '#f97316'],
            'red'     => ['name' => 'قرمز',       'value' => '#ef4444'],
            'pink'    => ['name' => 'صورتی',      'value' => '#ec4899'],
            'fuchsia' => ['name' => 'ارغوانی',    'value' => '#d946ef'],
            'purple'  => ['name' => 'بنفش تیره',  'value' => '#a855f7'],
            'indigo'  => ['name' => 'نیلی',       'value' => '#6366f1'],
            'blue'    => ['name' => 'آبی',        'value' => '#3b82f6'],
            'cyan'    => ['name' => 'فیروزه‌ای',  'value' => '#06b6d4'],
            'teal'    => ['name' => 'سبز آبی',    'value' => '#14b8a6'],
            'green'   => ['name' => 'سبز',        'value' => '#22c55e'],
            'lime'    => ['name' => 'لیمویی',     'value' => '#84cc16'],
            'yellow'  => ['name' => 'زرد',        'value' => '#eab308'],
            'gray'    => ['name' => 'خاکستری',    'value' => '#6b7280'],
            'slate'   => ['name' => 'سنگي',       'value' => '#64748b'],
            'zinc'    => ['name' => 'روی',        'value' => '#71717a'],
        ];
    }

    public static function currentColor(): array
    {
        $key = self::get('color', 'sky');
        $palette = self::colorPalette();

        return $palette[$key] ?? $palette['sky'];
    }

    public static function currentColorKey(): string
    {
        return self::get('color', 'sky');
    }

    public static function setColor(string $key): bool
    {
        $palette = self::colorPalette();
        if (! isset($palette[$key])) {
            return false;
        }

        self::set('color', $key);
        return true;
    }

    // =========================================================
    // اعداد فارسی
    // =========================================================

    public static function persianNumbersEnabled(): bool
    {
        return (bool) self::get('persian_numbers', true);
    }

    public static function setPersianNumbers(bool $enabled): void
    {
        self::set('persian_numbers', $enabled);
    }

    // =========================================================
    // تقویم
    // =========================================================

    public static function calendar(): string
    {
        return self::get('calendar', 'jalali');
    }

    public static function setCalendar(string $value): void
    {
        if (! in_array($value, ['jalali', 'gregorian'], true)) {
            return;
        }
        self::set('calendar', $value);
    }

    public static function dateFormat(): string
    {
        return self::get('date_format', 'Y/m/d H:i');
    }

    public static function setDateFormat(string $value): void
    {
        self::set('date_format', $value);
    }

    public static function dateOnlyFormat(): string
    {
        return self::get('date_only_format', 'Y/m/d');
    }

    public static function setDateOnlyFormat(string $value): void
    {
        self::set('date_only_format', $value);
    }

    public static function firstDayOfWeek(): int
    {
        return (int) self::get('first_day_of_week', 6);
    }

    public static function setFirstDayOfWeek(int $value): void
    {
        if ($value < 0 || $value > 6) {
            return;
        }
        self::set('first_day_of_week', $value);
    }

    // =========================================================
    // اعداد و پول
    // =========================================================

    public static function thousandSeparator(): string
    {
        return self::get('thousand_separator', '٬');
    }

    public static function setThousandSeparator(string $value): void
    {
        self::set('thousand_separator', $value);
    }

    public static function decimalSeparator(): string
    {
        return self::get('decimal_separator', '٫');
    }

    public static function setDecimalSeparator(string $value): void
    {
        self::set('decimal_separator', $value);
    }

    public static function currency(): string
    {
        return self::get('currency', 'IRT');
    }

    public static function setCurrency(string $value): void
    {
        self::set('currency', $value);
    }

    // =========================================================
    // ساعت کاری
    // =========================================================

    public static function workingHours(): array
    {
        return self::get('working_hours', []);
    }

    public static function setWorkingHours(array $config): void
    {
        self::set('working_hours', $config);
    }

    // =========================================================
    // قالب صفحه ورود
    // =========================================================

    public static function loginTemplates(): array
    {
        return [
            'classic' => ['name' => 'کلاسیک', 'description' => 'گرادیانت رنگی با کارت وسط صفحه', 'icon' => '🎨'],
            'minimal' => ['name' => 'مینیمال', 'description' => 'ساده و تمیز، بدون حاشیه', 'icon' => '◻️'],
            'split' => ['name' => 'دوستونه', 'description' => 'تصویر سمت راست، فرم سمت چپ', 'icon' => '📐'],
            'glass' => ['name' => 'شیشه‌ای', 'description' => 'مدرن با پس‌زمینه تار', 'icon' => '💎'],
            'gradient' => ['name' => 'گرادیانت', 'description' => 'تمام صفحه گرادیانت رنگی', 'icon' => '🌈'],
            'dark' => ['name' => 'تیره', 'description' => 'تم تاریک برای شب', 'icon' => '🌙'],
            'neon' => ['name' => 'نئون', 'description' => 'درخشان با رنگ نئون', 'icon' => '⚡'],
            'corporate' => ['name' => 'شرکتی', 'description' => 'رسمی با لوگو بزرگ', 'icon' => '🏢'],
            'wave' => ['name' => 'موج', 'description' => 'با موج تزئینی SVG', 'icon' => '🌊'],
            'pattern' => ['name' => 'الگو', 'description' => 'الگوی هندسی تزئینی', 'icon' => '🔷'],
        ];
    }

    public static function loginTemplate(): string
    {
        return self::get('login_template', 'classic');
    }

    public static function setLoginTemplate(string $key): bool
    {
        $templates = self::loginTemplates();

        if (! isset($templates[$key])) {
            return false;
        }

        self::set('login_template', $key);
        return true;
    }

    // =========================================================
    // متن‌های صفحه ورود
    // =========================================================

    public static function loginTexts(): array
    {
        $defaults = [
            'brand_line' => 'پنل مدیریت',
            'heading' => 'ورود به حساب',
            'subheading' => 'برای ادامه، اطلاعات خود را وارد کنید',
            'footer' => 'ساخته شده با ❤',
            'copyright_prefix' => '©',
        ];

        $stored = self::get('login_texts', []);

        return array_merge($defaults, is_array($stored) ? $stored : []);
    }

    public static function loginText(string $key, ?string $fallback = null): string
    {
        return self::loginTexts()[$key] ?? ($fallback ?? '');
    }

    public static function setLoginTexts(array $texts): void
    {
        self::set('login_texts', $texts);
    }

    public static function setLoginText(string $key, string $value): void
    {
        $texts = self::loginTexts();
        $texts[$key] = $value;
        self::setLoginTexts($texts);
    }

    public static function resetLoginTexts(): void
    {
        self::set('login_texts', null);
    }

    // =========================================================
    // قالب‌های آماده
    // =========================================================

    public static function presets(): array
    {
        return [
            'admin' => [
                'name' => 'اداری',
                'description' => 'مناسب پنل‌های مدیریتی',
                'icon' => '🏢',
                'settings' => [
                    'font' => 'vazirmatn',
                    'color' => 'sky',
                    'persian_numbers' => true,
                    'calendar' => 'jalali',
                    'date_format' => 'Y/m/d H:i',
                    'date_only_format' => 'Y/m/d',
                    'first_day_of_week' => 6,
                    'thousand_separator' => '٬',
                    'decimal_separator' => '٫',
                    'currency' => 'IRT',
                ],
            ],
            'ecommerce' => [
                'name' => 'فروشگاهی',
                'description' => 'مناسب فروشگاه‌های آنلاین',
                'icon' => '🛒',
                'settings' => [
                    'font' => 'estedad',
                    'color' => 'emerald',
                    'persian_numbers' => true,
                    'calendar' => 'jalali',
                    'date_format' => 'Y/m/d H:i',
                    'date_only_format' => 'Y/m/d',
                    'first_day_of_week' => 6,
                    'thousand_separator' => '٬',
                    'decimal_separator' => '٫',
                    'currency' => 'IRT',
                ],
            ],
            'corporate' => [
                'name' => 'شرکتی',
                'description' => 'مناسب گزارش‌های رسمی',
                'icon' => '🏛️',
                'settings' => [
                    'font' => 'vazirmatn',
                    'color' => 'violet',
                    'persian_numbers' => true,
                    'calendar' => 'jalali',
                    'date_format' => 'j F Y',
                    'date_only_format' => 'j F Y',
                    'first_day_of_week' => 6,
                    'thousand_separator' => '٬',
                    'decimal_separator' => '٫',
                    'currency' => 'IRR',
                ],
            ],
            'minimal' => [
                'name' => 'مینیمال',
                'description' => 'ساده و شیک با ارقام لاتین',
                'icon' => '◻️',
                'settings' => [
                    'font' => 'sahel',
                    'color' => 'gray',
                    'persian_numbers' => false,
                    'calendar' => 'gregorian',
                    'date_format' => 'Y-m-d H:i',
                    'date_only_format' => 'Y-m-d',
                    'first_day_of_week' => 1,
                    'thousand_separator' => ',',
                    'decimal_separator' => '.',
                    'currency' => 'IRT',
                ],
            ],
        ];
    }

    public static function activePreset(): ?string
    {
        return self::get('active_preset');
    }

    public static function applyPreset(string $key): bool
    {
        $presets = self::presets();
        if (! isset($presets[$key])) {
            return false;
        }

        $data = self::all();
        foreach ($presets[$key]['settings'] as $k => $v) {
            $data[$k] = $v;
        }
        $data['active_preset'] = $key;

        self::write($data);
        return true;
    }

    // =========================================================
    // مدیریت فایل تنظیمات
    // =========================================================

    public static function settingsPath(): string
    {
        return storage_path('app/filament-persian/settings.json');
    }

    public static function all(): array
    {
        $path = self::settingsPath();
        if (! file_exists($path)) {
            return [];
        }

        $content = file_get_contents($path);
        $data = json_decode($content, true);

        return is_array($data) ? $data : [];
    }

    public static function set(string $key, mixed $value): void
    {
        $data = self::all();
        $data[$key] = $value;
        self::write($data);
    }

    public static function get(string $key, mixed $default = null): mixed
    {
        return self::all()[$key] ?? $default;
    }

    protected static function write(array $data): void
    {
        $path = self::settingsPath();
        $dir = dirname($path);

        if (! is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        file_put_contents(
            $path,
            json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)
        );
    }
}