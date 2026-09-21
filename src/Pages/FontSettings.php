<?php

namespace Sghazanfari\FilamentPersian\Pages;

use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Sghazanfari\FilamentPersian\Settings\FontManager;
use Sghazanfari\FilamentPersian\Support\WorkingHours;

class FontSettings extends Page
{
    protected static ?string $navigationLabel = 'تنظیمات ظاهری';
    protected static ?string $title = 'تنظیمات ظاهری';
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-cog-6-tooth';
    protected static ?int $navigationSort = 100;

    protected string $view = 'filament-persian::pages.font-settings';

    // تب فعال
    public string $tab = 'appearance';

    // تنظیمات ظاهری
    public ?string $selectedFont = null;
    public ?string $selectedColor = null;
    public bool $persianNumbers = true;

    // تنظیمات تاریخ
    public string $calendar = 'jalali';
    public string $dateFormat = 'Y/m/d H:i';
    public string $dateOnlyFormat = 'Y/m/d';
    public int $firstDayOfWeek = 6;

    // تنظیمات اعداد
    public string $thousandSeparator = '٬';
    public string $decimalSeparator = '٫';
    public string $currency = 'IRT';

    // ساعت کاری
    public array $workingHours = [];

    // صفحه ورود
    public string $loginTemplate = 'classic';
    public array $loginTexts = [];

    // پیش‌نمایش
    public string $previewText = 'سلام! این یک متن پیش‌نمایش است. ۱۲۳۴۵۶۷۸۹۰';

    // برای تشخیص تغییرات
    protected array $original = [];

    public function mount(): void
    {
        $this->selectedFont = FontManager::get('font');
        $this->selectedColor = FontManager::currentColorKey();
        $this->persianNumbers = FontManager::persianNumbersEnabled();

        $this->calendar = FontManager::calendar();
        $this->dateFormat = FontManager::dateFormat();
        $this->dateOnlyFormat = FontManager::dateOnlyFormat();
        $this->firstDayOfWeek = FontManager::firstDayOfWeek();

        $this->thousandSeparator = FontManager::thousandSeparator();
        $this->decimalSeparator = FontManager::decimalSeparator();
        $this->currency = FontManager::currency();

        $this->workingHours = WorkingHours::config();

        $this->loginTemplate = FontManager::loginTemplate();
        $this->loginTexts = FontManager::loginTexts();

        $this->original = $this->currentState();
    }

    protected function currentState(): array
    {
        return [
            'font' => $this->selectedFont,
            'color' => $this->selectedColor,
            'persian_numbers' => $this->persianNumbers,
            'calendar' => $this->calendar,
            'date_format' => $this->dateFormat,
            'date_only_format' => $this->dateOnlyFormat,
            'first_day_of_week' => $this->firstDayOfWeek,
            'thousand_separator' => $this->thousandSeparator,
            'decimal_separator' => $this->decimalSeparator,
            'currency' => $this->currency,
            'working_hours' => $this->workingHours,
            'login_template' => $this->loginTemplate,
            'login_texts' => $this->loginTexts,
        ];
    }

    // =========================================================
    // Properties
    // =========================================================

    public function getFontsProperty(): array
    {
        return FontManager::availableFonts();
    }

    public function getColorsProperty(): array
    {
        return FontManager::colorPalette();
    }

    public function getPresetsProperty(): array
    {
        return FontManager::presets();
    }

    public function getActivePresetProperty(): ?string
    {
        return FontManager::activePreset();
    }

    public function getCurrentFontFamilyProperty(): string
    {
        $font = FontManager::availableFonts()[$this->selectedFont] ?? null;
        return $font['family'] ?? 'Vazirmatn';
    }

    public function getCurrentColorValueProperty(): string
    {
        $colors = FontManager::colorPalette();
        return $colors[$this->selectedColor]['value'] ?? '#0ea5e9';
    }

    public function getIsDirtyProperty(): bool
    {
        return $this->currentState() !== $this->original;
    }

    public function getDayNamesProperty(): array
    {
        return [
            6 => 'شنبه',
            0 => 'یک‌شنبه',
            1 => 'دوشنبه',
            2 => 'سه‌شنبه',
            3 => 'چهارشنبه',
            4 => 'پنج‌شنبه',
            5 => 'جمعه',
        ];
    }

    public function getWeeklyHoursTotalProperty(): float
    {
        $total = 0.0;

        foreach ($this->workingHours as $schedule) {
            if (empty($schedule) || empty($schedule['start']) || empty($schedule['end'])) {
                continue;
            }

            try {
                $start = \Carbon\Carbon::parse($schedule['start']);
                $end = \Carbon\Carbon::parse($schedule['end']);
                $total += $start->diffInMinutes($end) / 60;
            } catch (\Throwable) {
                continue;
            }
        }

        return round($total, 1);
    }

    public function getLoginTemplatesProperty(): array
    {
        return FontManager::loginTemplates();
    }

    public function getLoginTextFieldsProperty(): array
    {
        return [
            'brand_line' => [
                'label' => 'زیرنویس برند (هدر)',
                'placeholder' => 'پنل مدیریت',
            ],
            'heading' => [
                'label' => 'عنوان اصلی فرم',
                'placeholder' => 'ورود به حساب',
            ],
            'subheading' => [
                'label' => 'زیرعنوان فرم',
                'placeholder' => 'برای ادامه، اطلاعات خود را وارد کنید',
            ],
            'footer' => [
                'label' => 'متن فوتر',
                'placeholder' => 'ساخته شده با ❤',
            ],
            'copyright_prefix' => [
                'label' => 'پیشوند کپی‌رایت',
                'placeholder' => '©',
            ],
        ];
    }

    // =========================================================
    // Actions
    // =========================================================

    public function setTab(string $tab): void
    {
        $this->tab = $tab;
    }

    public function resetToOriginal(): void
    {
        $this->selectedFont = $this->original['font'];
        $this->selectedColor = $this->original['color'];
        $this->persianNumbers = $this->original['persian_numbers'];
        $this->calendar = $this->original['calendar'];
        $this->dateFormat = $this->original['date_format'];
        $this->dateOnlyFormat = $this->original['date_only_format'];
        $this->firstDayOfWeek = $this->original['first_day_of_week'];
        $this->thousandSeparator = $this->original['thousand_separator'];
        $this->decimalSeparator = $this->original['decimal_separator'];
        $this->currency = $this->original['currency'];
        $this->workingHours = $this->original['working_hours'];
        $this->loginTemplate = $this->original['login_template'];
        $this->loginTexts = $this->original['login_texts'];
    }

    public function applyPreset(string $key): void
    {
        $presets = FontManager::presets();
        if (! isset($presets[$key])) {
            return;
        }

        $settings = $presets[$key]['settings'];

        $this->selectedFont = $settings['font'] ?? $this->selectedFont;
        $this->selectedColor = $settings['color'] ?? $this->selectedColor;
        $this->persianNumbers = $settings['persian_numbers'] ?? $this->persianNumbers;
        $this->calendar = $settings['calendar'] ?? $this->calendar;
        $this->dateFormat = $settings['date_format'] ?? $this->dateFormat;
        $this->dateOnlyFormat = $settings['date_only_format'] ?? $this->dateOnlyFormat;
        $this->firstDayOfWeek = $settings['first_day_of_week'] ?? $this->firstDayOfWeek;
        $this->thousandSeparator = $settings['thousand_separator'] ?? $this->thousandSeparator;
        $this->decimalSeparator = $settings['decimal_separator'] ?? $this->decimalSeparator;
        $this->currency = $settings['currency'] ?? $this->currency;

        Notification::make()
            ->title("قالب «{$presets[$key]['name']}» اعمال شد")
            ->body('برای ذخیره، دکمه ذخیره تغییرات را بزنید.')
            ->info()
            ->send();
    }

    public function toggleWorkingDay(int $dayOfWeek): void
    {
        if (empty($this->workingHours[$dayOfWeek])) {
            $this->workingHours[$dayOfWeek] = [
                'start' => '08:00',
                'end' => '16:00',
            ];
        } else {
            $this->workingHours[$dayOfWeek] = null;
        }

        $this->normalizeWorkingHours();
    }

    public function resetWorkingHours(): void
    {
        $this->workingHours = WorkingHours::defaultConfig();

        Notification::make()
            ->title('ساعت کاری به حالت پیش‌فرض برگشت')
            ->info()
            ->send();
    }

    protected function normalizeWorkingHours(): void
    {
        $normalized = [];

        foreach ([6, 0, 1, 2, 3, 4, 5] as $dow) {
            $normalized[$dow] = $this->workingHours[$dow] ?? null;
        }

        $this->workingHours = $normalized;
    }

    public function selectLoginTemplate(string $key): void
    {
        $templates = FontManager::loginTemplates();

        if (! isset($templates[$key])) {
            return;
        }

        $this->loginTemplate = $key;

        Notification::make()
            ->title("قالب «{$templates[$key]['name']}» انتخاب شد")
            ->body('برای اعمال، دکمه ذخیره تغییرات را بزنید.')
            ->success()
            ->send();
    }

    public function resetLoginTexts(): void
    {
        $this->loginTexts = [
            'brand_line' => 'پنل مدیریت',
            'heading' => 'ورود به حساب',
            'subheading' => 'برای ادامه، اطلاعات خود را وارد کنید',
            'footer' => 'ساخته شده با ❤',
            'copyright_prefix' => '©',
        ];

        Notification::make()
            ->title('متن‌ها به پیش‌فرض برگشت')
            ->info()
            ->send();
    }

    public function save(): void
    {
        // ظاهر
        if ($this->selectedFont) {
            FontManager::setFont($this->selectedFont);
        }
        if ($this->selectedColor) {
            FontManager::setColor($this->selectedColor);
        }
        FontManager::setPersianNumbers($this->persianNumbers);

        // تاریخ
        FontManager::setCalendar($this->calendar);
        FontManager::setDateFormat($this->dateFormat);
        FontManager::setDateOnlyFormat($this->dateOnlyFormat);
        FontManager::setFirstDayOfWeek($this->firstDayOfWeek);

        // اعداد
        FontManager::setThousandSeparator($this->thousandSeparator);
        FontManager::setDecimalSeparator($this->decimalSeparator);
        FontManager::setCurrency($this->currency);

        // ساعت کاری
        $this->normalizeWorkingHours();
        WorkingHours::setConfig($this->workingHours);
        WorkingHours::clearCache();

        // صفحه ورود
        FontManager::setLoginTemplate($this->loginTemplate);
        FontManager::setLoginTexts($this->loginTexts);

        $this->original = $this->currentState();

        Notification::make()
            ->title('تنظیمات ذخیره شد')
            ->body('صفحه را Refresh کنید تا تغییرات اعمال شود.')
            ->success()
            ->send();
    }
}