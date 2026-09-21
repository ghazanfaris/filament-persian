<?php

namespace Sghazanfari\FilamentPersian\Pages;

use Filament\Pages\Page;
use Sghazanfari\FilamentPersian\Settings\FontManager;

class ComponentsShowcase extends Page
{
    protected static ?string $navigationLabel = 'کامپوننت‌های آماده';
    protected static ?string $title = 'کامپوننت‌های آماده';
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-squares-2x2';
    protected static ?int $navigationSort = 97;

    protected string $view = 'filament-persian::pages.components-showcase';

    public function getComponentsProperty(): array
    {
        return [
            [
                'group' => '📅 تاریخ و تقویم',
                'icon' => '📅',
                'items' => [
                    [
                        'name' => 'JalaliDatePicker',
                        'title' => 'انتخابگر تاریخ شمسی',
                        'desc' => 'تاریخ بدون ساعت',
                        'code' => "JalaliDatePicker::make('birthday')\n    ->label('تاریخ تولد'),",
                    ],
                    [
                        'name' => 'JalaliDatePicker (withTime)',
                        'title' => 'تاریخ + ساعت',
                        'desc' => 'با نمایش ساعت و دقیقه',
                        'code' => "JalaliDatePicker::make('event_date')\n    ->label('تاریخ رویداد')\n    ->withTime(),",
                    ],
                    [
                        'name' => 'JalaliDatePicker (withSeconds)',
                        'title' => 'تاریخ + ساعت + ثانیه',
                        'desc' => 'برای زمان دقیق',
                        'code' => "JalaliDatePicker::make('created_at')\n    ->label('زمان دقیق')\n    ->withTime(withSeconds: true),",
                    ],
                    [
                        'name' => 'JalaliDateColumn',
                        'title' => 'ستون جدول جلالی',
                        'desc' => 'با نمایش ساعت',
                        'code' => "JalaliDateColumn::make('created_at')\n    ->label('تاریخ ایجاد')\n    ->withTime()\n    ->sortable(),",
                    ],
                    [
                        'name' => 'JalaliDateColumn (human)',
                        'title' => 'تاریخ انسانی',
                        'desc' => '«۳ روز پیش»',
                        'code' => "JalaliDateColumn::make('published_at')\n    ->label('انتشار')\n    ->human(),",
                    ],
                    [
                        'name' => 'JalaliDateFilter',
                        'title' => 'فیلتر بازه تاریخ',
                        'desc' => 'از تاریخ تا تاریخ',
                        'code' => "JalaliDateFilter::make('created_range')\n    ->column('created_at')\n    ->label('بازه')\n    ->withTime(),",
                    ],
                ],
            ],

            [
                'group' => '✅ ورودی‌های ایرانی',
                'icon' => '✅',
                'items' => [
                    [
                        'name' => 'IranianMobileInput',
                        'title' => 'ورودی موبایل',
                        'desc' => 'پشتیبانی از +98 و ارقام فارسی',
                        'code' => "IranianMobileInput::make('mobile')\n    ->label('موبایل'),",
                    ],
                    [
                        'name' => 'IranianNationalCodeInput',
                        'title' => 'ورودی کد ملی',
                        'desc' => '۱۰ رقمی با checksum',
                        'code' => "IranianNationalCodeInput::make('national_code')\n    ->label('کد ملی'),",
                    ],
                    [
                        'name' => 'IranianShebaInput',
                        'title' => 'ورودی شبا',
                        'desc' => 'با الگوریتم IBAN',
                        'code' => "IranianShebaInput::make('sheba')\n    ->label('شماره شبا'),",
                    ],
                    [
                        'name' => 'IranianBankCardInput',
                        'title' => 'شماره کارت بانکی',
                        'desc' => 'ماسک ۴-۴-۴-۴ و تشخیص بانک',
                        'code' => "IranianBankCardInput::make('bank_card')\n    ->label('شماره کارت'),",
                    ],
                    [
                        'name' => 'IranianLegalEntityIdInput',
                        'title' => 'شناسه ملی حقوقی',
                        'desc' => '۱۱ رقمی با checksum',
                        'code' => "IranianLegalEntityIdInput::make('legal_id')\n    ->label('شناسه ملی'),",
                    ],
                    [
                        'name' => 'IranianEconomicCodeInput',
                        'title' => 'شماره اقتصادی',
                        'desc' => '۱۴ رقمی',
                        'code' => "IranianEconomicCodeInput::make('economic_code')\n    ->label('شماره اقتصادی'),",
                    ],
                    [
                        'name' => 'IranianVehiclePlateInput',
                        'title' => 'پلاک خودرو',
                        'desc' => 'پشتیبانی از ۳ فرمت',
                        'code' => "IranianVehiclePlateInput::make('vehicle_plate')\n    ->label('شماره پلاک'),",
                    ],
                    [
                        'name' => 'IranianSocialSecurityInput',
                        'title' => 'شماره بیمه تأمین اجتماعی',
                        'desc' => 'با فرمت 123-456789-0',
                        'code' => "IranianSocialSecurityInput::make('ssn')\n    ->label('شماره بیمه'),",
                    ],
                    [
                        'name' => 'IranianStockExchangeCodeInput',
                        'title' => 'کد بورسی',
                        'desc' => '۱۱ رقمی',
                        'code' => "IranianStockExchangeCodeInput::make('stock_code')\n    ->label('کد بورسی'),",
                    ],
                    [
                        'name' => 'IranianPassportInput',
                        'title' => 'شماره گذرنامه',
                        'desc' => 'پشتیبانی از ۳ الگو',
                        'code' => "IranianPassportInput::make('passport')\n    ->label('شماره گذرنامه'),",
                    ],
                ],
            ],

            [
                'group' => '🔢 اعداد و مبالغ',
                'icon' => '🔢',
                'items' => [
                    [
                        'name' => 'PersianNumberInput',
                        'title' => 'ورودی عدد',
                        'desc' => 'با جداکننده هزارگان خودکار',
                        'code' => "PersianNumberInput::make('quantity')\n    ->label('تعداد'),",
                    ],
                    [
                        'name' => 'PersianNumberInput (money)',
                        'title' => 'ورودی مبلغ',
                        'desc' => 'با واحد پول تومان/ریال',
                        'code' => "PersianNumberInput::make('amount')\n    ->label('مبلغ')\n    ->money('IRT'),",
                    ],
                    [
                        'name' => 'PersianMoneyColumn',
                        'title' => 'ستون مبلغ',
                        'desc' => 'نمایش با واحد پول',
                        'code' => "PersianMoneyColumn::make('amount')\n    ->label('مبلغ')\n    ->currency('IRT'),",
                    ],
                    [
                        'name' => 'PersianTextColumn',
                        'title' => 'ستون متن با اعداد فارسی',
                        'desc' => 'با جداکننده هزارگان',
                        'code' => "PersianTextColumn::make('id')\n    ->label('شناسه')\n    ->thousands(),",
                    ],
                ],
            ],

            [
                'group' => '🗺️ داده‌های آماده ایران',
                'icon' => '🗺️',
                'items' => [
                    [
                        'name' => 'IranProvinceSelect',
                        'title' => 'انتخاب استان',
                        'desc' => '۳۱ استان ایران',
                        'code' => "IranProvinceSelect::make('province_id')\n    ->label('استان')\n    ->resetCityField('city_id'),",
                    ],
                    [
                        'name' => 'IranCitySelect',
                        'title' => 'انتخاب شهر',
                        'desc' => 'وابسته به استان — ۳۱۵ شهر',
                        'code' => "IranCitySelect::make('city_id')\n    ->label('شهر')\n    ->provinceField('province_id'),",
                    ],
                    [
                        'name' => 'IranBankSelect',
                        'title' => 'انتخاب بانک',
                        'desc' => '۳۰ بانک ایرانی',
                        'code' => "IranBankSelect::make('bank_code')\n    ->label('بانک'),",
                    ],
                ],
            ],

            [
                'group' => '🔍 جستجو',
                'icon' => '🔍',
                'items' => [
                    [
                        'name' => 'HasPersianGlobalSearch',
                        'title' => 'جستجوی فارسی',
                        'desc' => 'ی/ک عربی و فارسی یکسان',
                        'code' => "class EventResource extends Resource\n{\n    use HasPersianGlobalSearch;\n\n    public static function getGloballySearchableAttributes(): array\n    {\n        return ['title', 'body'];\n    }\n}",
                    ],
                    [
                        'name' => 'Command Palette',
                        'title' => 'جستجوی سریع',
                        'desc' => 'با Ctrl+K',
                        'code' => "// به‌طور خودکار فعال است\n// کافیست Ctrl+K بزنید",
                    ],
                ],
            ],

            [
                'group' => '📊 ویجت و نمودار',
                'icon' => '📊',
                'items' => [
                    [
                        'name' => 'PersianStatsWidget',
                        'title' => 'ویجت آمار',
                        'desc' => 'کارت‌های آماری با اعداد فارسی',
                        'code' => "class DashboardStats extends PersianStatsWidget\n{\n    protected function getStats(): array\n    {\n        return [\n            \$this->makeNumberStat('کاربران', User::count()),\n            \$this->makeMoneyStat('درآمد', 48_500_000),\n        ];\n    }\n}",
                    ],
                    [
                        'name' => 'PersianChartWidget',
                        'title' => 'نمودار با محور جلالی',
                        'desc' => 'محور X با تاریخ شمسی',
                        'code' => "class EventsChart extends PersianChartWidget\n{\n    protected ?string \$heading = 'رویدادها';\n    protected int|string|array \$columnSpan = 'full';\n\n    protected function getData(): array\n    {\n        [\$labels, \$dates] = \$this->lastNDays(30);\n        // ...\n        return ['datasets' => [...], 'labels' => \$labels];\n    }\n}",
                    ],
                ],
            ],

            [
                'group' => '🧩 Traits و Macros',
                'icon' => '🧩',
                'items' => [
                    [
                        'name' => 'AutoJalaliDates',
                        'title' => 'تبدیل خودکار',
                        'desc' => 'همه ستون‌های تاریخ خودکار جلالی',
                        'code' => "class EventResource extends Resource\n{\n    use AutoJalaliDates;\n\n    public static function table(Table \$table): Table\n    {\n        return static::autoJalaliDates(\n            EventsTable::configure(\$table)\n        );\n    }\n}",
                    ],
                    [
                        'name' => 'withJalaliTimestamps',
                        'title' => 'Macro جدول',
                        'desc' => 'created_at و updated_at خودکار',
                        'code' => "Table::make()\n    ->columns([...])\n    ->withJalaliTimestamps(),",
                    ],
                    [
                        'name' => 'HasJalaliDates',
                        'title' => 'Trait مدل',
                        'desc' => 'متدهای کمکی برای مدل',
                        'code' => "class User extends Authenticatable\n{\n    use HasJalaliDates;\n}\n\n\$user->jalali('created_at');\n\$user->jalaliHuman('created_at');",
                    ],
                ],
            ],
        ];
    }

    public function getLoginTemplatesProperty(): array
    {
        return FontManager::loginTemplates();
    }

    public function getCurrentLoginTemplateProperty(): string
    {
        return FontManager::loginTemplate();
    }

    public function getCurrentLoginTextsProperty(): array
    {
        return FontManager::loginTexts();
    }

    public function getCurrentColorProperty(): array
    {
        return FontManager::currentColor();
    }

    public function getCurrentFontProperty(): ?array
    {
        return FontManager::currentFont();
    }

    public function getHelpersProperty(): array
    {
        return [
            // تاریخ
            ['code' => "jalali(now())", 'desc' => 'تاریخ جلالی به‌صورت آرایه', 'group' => 'تاریخ'],
            ['code' => "jalali_format(now(), 'Y/m/d H:i')", 'desc' => 'فرمت تاریخ', 'group' => 'تاریخ'],
            ['code' => "jalali_now()", 'desc' => 'تاریخ و ساعت الان', 'group' => 'تاریخ'],
            ['code' => "to_jalali_carbon(1403, 5, 12)", 'desc' => 'تبدیل جلالی به Carbon', 'group' => 'تاریخ'],

            // اعداد
            ['code' => "fa_digits('1234')", 'desc' => 'ارقام فارسی', 'group' => 'اعداد'],
            ['code' => "en_digits('۱۲۳۴')", 'desc' => 'ارقام لاتین', 'group' => 'اعداد'],
            ['code' => "fa_number(1234567)", 'desc' => 'عدد با جداکننده', 'group' => 'اعداد'],
            ['code' => "fa_money(48500000, 'IRT')", 'desc' => 'مبلغ با واحد پول', 'group' => 'اعداد'],

            // اعتبارسنجی
            ['code' => "is_valid_national_code('0084575948')", 'desc' => 'بررسی کد ملی', 'group' => 'اعتبارسنجی'],
            ['code' => "is_valid_mobile('09123456789')", 'desc' => 'بررسی موبایل', 'group' => 'اعتبارسنجی'],
            ['code' => "is_valid_sheba('IR82...')", 'desc' => 'بررسی شبا', 'group' => 'اعتبارسنجی'],
            ['code' => "is_valid_postal_code('1234567890')", 'desc' => 'بررسی کد پستی', 'group' => 'اعتبارسنجی'],
            ['code' => "is_valid_bank_card('6037...')", 'desc' => 'بررسی شماره کارت', 'group' => 'اعتبارسنجی'],
            ['code' => "detect_bank_from_card('60379975...')", 'desc' => 'تشخیص بانک از کارت', 'group' => 'اعتبارسنجی'],
            ['code' => "format_bank_card('6037997512345678')", 'desc' => 'فرمت ۴-۴-۴-۴ کارت', 'group' => 'اعتبارسنجی'],
            ['code' => "is_valid_legal_entity_national_id('10101754291')", 'desc' => 'بررسی شناسه ملی حقوقی', 'group' => 'اعتبارسنجی'],
            ['code' => "is_valid_economic_code('41123456789012')", 'desc' => 'بررسی شماره اقتصادی', 'group' => 'اعتبارسنجی'],
            ['code' => "is_valid_vehicle_plate('12ب345')", 'desc' => 'بررسی پلاک خودرو', 'group' => 'اعتبارسنجی'],
            ['code' => "format_vehicle_plate('12ب34567')", 'desc' => 'فرمت پلاک', 'group' => 'اعتبارسنجی'],
            ['code' => "is_valid_social_security_number('1234567890')", 'desc' => 'بررسی شماره بیمه', 'group' => 'اعتبارسنجی'],
            ['code' => "format_social_security_number('1234567890')", 'desc' => 'فرمت 123-456789-0', 'group' => 'اعتبارسنجی'],
            ['code' => "is_valid_stock_exchange_code('12345678901')", 'desc' => 'بررسی کد بورسی', 'group' => 'اعتبارسنجی'],
            ['code' => "is_valid_passport_number('A12345678')", 'desc' => 'بررسی شماره گذرنامه', 'group' => 'اعتبارسنجی'],
            ['code' => "format_passport_number('A12345678')", 'desc' => 'فرمت A - 12345678', 'group' => 'اعتبارسنجی'],

            // داده‌های آماده ایران
            ['code' => "iran_provinces()", 'desc' => '۳۱ استان ایران', 'group' => 'داده‌های ایران'],
            ['code' => "iran_province(8)", 'desc' => 'اطلاعات استان', 'group' => 'داده‌های ایران'],
            ['code' => "iran_provinces_for_select()", 'desc' => 'آماده Select', 'group' => 'داده‌های ایران'],
            ['code' => "iran_cities(8)", 'desc' => 'شهرهای یک استان', 'group' => 'داده‌های ایران'],
            ['code' => "iran_cities_for_select(8)", 'desc' => 'شهرها آماده Select', 'group' => 'داده‌های ایران'],
            ['code' => "iran_city(1)", 'desc' => 'اطلاعات یک شهر', 'group' => 'داده‌های ایران'],
            ['code' => "iran_banks()", 'desc' => '۳۰ بانک ایرانی', 'group' => 'داده‌های ایران'],
            ['code' => "iran_bank('melli')", 'desc' => 'اطلاعات یک بانک', 'group' => 'داده‌های ایران'],
            ['code' => "iran_banks_for_select()", 'desc' => 'بانک‌ها آماده Select', 'group' => 'داده‌های ایران'],
            ['code' => "tehran_regions()", 'desc' => '۲۲ منطقه تهران', 'group' => 'داده‌های ایران'],
            ['code' => "tehran_regions_for_select()", 'desc' => 'مناطق آماده Select', 'group' => 'داده‌های ایران'],

            // تعطیلات و ساعت کاری
            ['code' => "is_iran_holiday(now())", 'desc' => 'بررسی تعطیلی', 'group' => 'تعطیلات و ساعت کاری'],
            ['code' => "iran_holiday_names(now())", 'desc' => 'نام مناسبت‌ها', 'group' => 'تعطیلات و ساعت کاری'],
            ['code' => "next_working_day(now())", 'desc' => 'روز کاری بعدی', 'group' => 'تعطیلات و ساعت کاری'],
            ['code' => "working_days_between(\$from, \$until)", 'desc' => 'تعداد روزهای کاری', 'group' => 'تعطیلات و ساعت کاری'],
            ['code' => "is_working_time()", 'desc' => 'بررسی ساعت کاری', 'group' => 'تعطیلات و ساعت کاری'],
            ['code' => "weekly_schedule()", 'desc' => 'برنامه هفتگی', 'group' => 'تعطیلات و ساعت کاری'],
            ['code' => "working_hours_weekly_total()", 'desc' => 'مجموع ساعت هفتگی', 'group' => 'تعطیلات و ساعت کاری'],
        ];
    }
}