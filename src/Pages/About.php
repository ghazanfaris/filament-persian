<?php

namespace Sghazanfari\FilamentPersian\Pages;

use Filament\Pages\Page;
use Sghazanfari\FilamentPersian\Settings\FontManager;

class About extends Page
{
    protected static ?string $navigationLabel = 'درباره';
    protected static ?string $title = 'درباره Filament Persian';
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-information-circle';
    protected static ?int $navigationSort = 99;

    protected string $view = 'filament-persian::pages.about';

    public function getVersionProperty(): string
    {
        return '1.0.0';
    }

    public function getCurrentFontProperty(): ?array
    {
        return FontManager::currentFont();
    }

    public function getCurrentColorProperty(): array
    {
        return FontManager::currentColor();
    }

    public function getStatsProperty(): array
    {
        return [
            ['value' => '۱۰', 'label' => 'قالب صفحه ورود', 'icon' => '🔐'],
            ['value' => '۳۰+', 'label' => 'کامپوننت آماده', 'icon' => '🧩'],
            ['value' => '۵۴', 'label' => 'تابع کمکی', 'icon' => '🛠️'],
            ['value' => '۱۱', 'label' => 'اعتبارسنجی ایرانی', 'icon' => '✅'],
            ['value' => '۳۱', 'label' => 'استان و ۳۱۵ شهر', 'icon' => '🗺️'],
            ['value' => '۳۰', 'label' => 'بانک ایرانی', 'icon' => '🏦'],
            ['value' => '۲۰', 'label' => 'رنگ آماده', 'icon' => '🎨'],
            ['value' => '۵', 'label' => 'فونت فارسی', 'icon' => '✍️'],
            ['value' => '۱۳', 'label' => 'فایل مستندات', 'icon' => '📖'],
        ];
    }

    public function getCategoriesProperty(): array
    {
        return [
            [
                'icon' => '📅',
                'title' => 'تقویم جلالی',
                'color' => '#0ea5e9',
                'items' => [
                    'کامپوننت JalaliDatePicker',
                    'ستون JalaliDateColumn',
                    'فیلتر JalaliDateFilter',
                    'تبدیل خودکار AutoJalaliDates',
                    'Macro withJalaliTimestamps',
                    'Trait HasJalaliDates',
                ],
            ],
            [
                'icon' => '✅',
                'title' => 'اعتبارسنجی ایرانی',
                'color' => '#10b981',
                'items' => [
                    'کد ملی با checksum',
                    'موبایل با پشتیبانی +98',
                    'شبا با الگوریتم IBAN',
                    'کد پستی ۱۰ رقمی',
                    'شماره کارت با تشخیص بانک',
                    'شناسه ملی حقوقی',
                    'شماره اقتصادی',
                    'پلاک خودرو',
                    'شماره بیمه تأمین اجتماعی',
                    'کد بورسی',
                    'شماره گذرنامه',
                ],
            ],
            [
                'icon' => '🗺️',
                'title' => 'داده‌های آماده ایران',
                'color' => '#8b5cf6',
                'items' => [
                    '۳۱ استان با مرکز',
                    '۳۱۵ شهر مهم',
                    '۳۰ بانک با پیشوند کارت',
                    '۲۲ منطقه شهرداری تهران',
                    'Select استان/شهر وابسته',
                    'تشخیص خودکار بانک',
                ],
            ],
            [
                'icon' => '⏰',
                'title' => 'تعطیلات و ساعت کاری',
                'color' => '#f59e0b',
                'items' => [
                    'تعطیلات شمسی ثابت',
                    'تعطیلات قمری (۱۴۰۳-۱۴۱۰)',
                    'تعطیلات هفتگی (جمعه)',
                    'ساعت کاری اداری',
                    'محاسبه روز کاری بعدی',
                    'تعداد روزهای کاری',
                ],
            ],
            [
                'icon' => '🔢',
                'title' => 'اعداد و مبالغ',
                'color' => '#ec4899',
                'items' => [
                    'PersianTextColumn',
                    'PersianMoneyColumn',
                    'PersianNumberInput',
                    'اعداد فارسی خودکار',
                    'جداکننده هزارگان',
                    'واحد پول تومان/ریال',
                ],
            ],
            [
                'icon' => '🔐',
                'title' => 'صفحه ورود',
                'color' => '#ef4444',
                'items' => [
                    '۱۰ قالب آماده',
                    'پشتیبانی از 2FA',
                    'ویرایش متن‌ها از پنل',
                    'رنگ و فونت پویا',
                    'RTL کامل',
                ],
            ],
            [
                'icon' => '📊',
                'title' => 'ویجت و نمودار',
                'color' => '#06b6d4',
                'items' => [
                    'PersianStatsWidget',
                    'PersianChartWidget',
                    'محور جلالی در نمودار',
                    'Tooltip فارسی',
                ],
            ],
            [
                'icon' => '🔍',
                'title' => 'جستجو',
                'color' => '#a855f7',
                'items' => [
                    'جستجوی ی/ک عربی',
                    'HasPersianGlobalSearch',
                    'Command Palette (Ctrl+K)',
                    'جستجو در منوها',
                ],
            ],
            [
                'icon' => '🎨',
                'title' => 'تنظیمات ظاهری',
                'color' => '#f97316',
                'items' => [
                    '۴ فونت فارسی نصب‌شده',
                    '۲۰ رنگ آماده',
                    'تب تاریخ',
                    'تب اعداد',
                    'تب ساعت کاری',
                    'تب صفحه ورود',
                    '۴ قالب آماده',
                ],
            ],
            [
                'icon' => '🖼️',
                'title' => 'پروفایل کاربر',
                'color' => '#14b8a6',
                'items' => [
                    'ویرایش نام و ایمیل',
                    'آپلود آواتار',
                    'تغییر رمز عبور',
                    'آواتار در منو',
                ],
            ],
        ];
    }

    public function getFeaturesProperty(): array
    {
        return [
            ['icon' => '📅', 'title' => 'تقویم جلالی', 'desc' => 'انتخاب و نمایش تاریخ شمسی در فرم‌ها، جداول و فیلترها'],
            ['icon' => '🔄', 'title' => 'تبدیل خودکار', 'desc' => 'ذخیره در دیتابیس میلادی، نمایش شمسی'],
            ['icon' => '🎨', 'title' => 'RTL کامل', 'desc' => 'راست‌چین حرفه‌ای همه کامپوننت‌ها'],
            ['icon' => '🔢', 'title' => 'اعداد فارسی', 'desc' => 'نمایش خودکار اعداد به فارسی با جداکننده'],
            ['icon' => '✅', 'title' => 'اعتبارسنجی ایرانی', 'desc' => 'کد ملی، موبایل، شبا، کد پستی، کارت بانکی'],
            ['icon' => '🧩', 'title' => 'کامپوننت‌های آماده', 'desc' => 'JalaliDatePicker، JalaliDateColumn و ۲۸ مورد دیگر'],
            ['icon' => '🔍', 'title' => 'جستجوی فارسی', 'desc' => 'ی/ک عربی و فارسی یکسان دیده می‌شوند'],
            ['icon' => '🖼️', 'title' => 'صفحه پروفایل', 'desc' => 'با آواتار، تغییر رمز، ویرایش اطلاعات'],
            ['icon' => '⚙️', 'title' => 'تنظیمات پنل', 'desc' => 'فونت، رنگ، اعداد، تقویم — همه از پنل'],
            ['icon' => '🌐', 'title' => 'Command Palette', 'desc' => 'با Ctrl+K جستجوی سریع'],
            ['icon' => '📊', 'title' => 'ویجت و نمودار', 'desc' => 'آمار و نمودار با محور جلالی'],
            ['icon' => '📖', 'title' => 'مستندات داخلی', 'desc' => '۱۳ فایل مستندات داخل پنل'],
            ['icon' => '🗺️', 'title' => 'داده‌های آماده', 'desc' => 'استان‌ها، شهرها، بانک‌ها، مناطق تهران'],
            ['icon' => '⏰', 'title' => 'تعطیلات و ساعت کاری', 'desc' => 'تعطیلات رسمی ایران و ساعت کاری اداری'],
            ['icon' => '🔐', 'title' => '۱۰ قالب صفحه ورود', 'desc' => 'از تنظیمات ظاهری انتخاب و ویرایش کن'],
        ];
    }

    public function getPackagesProperty(): array
    {
        return [
            ['name' => 'Filament', 'version' => '5.x', 'required' => true],
            ['name' => 'Laravel', 'version' => '11 - 13', 'required' => true],
            ['name' => 'PHP', 'version' => '8.2+', 'required' => true],
            ['name' => 'Spatie Permission', 'version' => '6.x', 'required' => false],
            ['name' => 'Filament Shield', 'version' => '4.x', 'required' => false],
        ];
    }
}