# 🇮🇷 Filament Persian

> کامل‌ترین پکیج فارسی‌سازی برای Filament v5 — تقویم جلالی، RTL، اعتبارسنجی ایرانی، اعداد فارسی و ۱۰ قالب صفحه ورود.

[![Latest Version](https://img.shields.io/badge/version-1.0.0-blue.svg)](https://github.com/ghazanfaris/filament-persian/releases)
[![License](https://img.shields.io/badge/license-MIT-green.svg)](LICENSE.md)
[![PHP](https://img.shields.io/badge/PHP-%5E8.2-blue.svg)](https://php.net)
[![Laravel](https://img.shields.io/badge/Laravel-11%20%7C%2012%20%7C%2013-red.svg)](https://laravel.com)
[![Filament](https://img.shields.io/badge/Filament-%5E5.0-orange.svg)](https://filamentphp.com)

---

## ✨ چرا این پکیج؟

Filament یک فریم‌ورک فوق‌العاده برای پنل ادمین است، ولی برای زبان فارسی چند کمبود اساسی دارد:

- ❌ تقویم میلادی است، نه شمسی
- ❌ راست‌چین ناقص یا ناموجود است
- ❌ اعداد لاتین نمایش می‌دهد
- ❌ اعتبارسنجی‌های ایرانی ندارد
- ❌ جستجوی فارسی (ی/ک عربی) کار نمی‌کند
- ❌ صفحه لاگین فارسی حرفه‌ای ندارد

**این پکیج همه این‌ها را حل می‌کند** — یک‌جا، تمیز، حرفه‌ای.

---

## 📋 فهرست مطالب

- [ویژگی‌ها](#ویژگیها)
- [نصب](#نصب)
- [شروع سریع](#شروع-سریع)
- [مستندات](#مستندات)
- [مثال‌های کاربردی](#مثالهای-کاربردی)
- [پشتیبانی](#پشتیبانی)
- [لایسنس](#لایسنس)

---

## 🎯 ویژگی‌ها

### 📅 تقویم جلالی
- کامپوننت `JalaliDatePicker` برای فرم‌ها
- ستون `JalaliDateColumn` برای جداول
- فیلتر `JalaliDateFilter` برای بازه‌ها
- تبدیل خودکار با `AutoJalaliDates`
- تقویم JS مستقل (بدون وابستگی خارجی)

### ✅ اعتبارسنجی ایرانی
- کد ملی با checksum
- موبایل با پشتیبانی از `+98`, `0098`, `98`
- شبا با الگوریتم IBAN
- کد پستی ۱۰ رقمی
- شماره کارت با تشخیص بانک
- شناسه ملی حقوقی
- شماره اقتصادی
- پلاک خودرو ایران
- شماره بیمه تأمین اجتماعی
- کد بورسی
- شماره گذرنامه

### 🗺️ داده‌های آماده ایران
- ۳۱ استان با مرکز و اسلاگ
- ۳۱۵ شهر مهم
- ۳۰ بانک با پیشوند کارت و کد شبا
- ۲۲ منطقه شهرداری تهران
- Select استان/شهر وابسته

### ⏰ تعطیلات و ساعت کاری
- تعطیلات شمسی ثابت (نوروز، ۲۲ بهمن، ...)
- تعطیلات قمری (۱۴۰۳-۱۴۱۰)
- تعطیلات هفتگی (جمعه)
- ساعت کاری اداری قابل تنظیم
- محاسبه روز کاری بعدی

### 🔢 اعداد و مبالغ فارسی
- `PersianTextColumn` برای متن‌های عددی
- `PersianMoneyColumn` برای مبالغ
- `PersianNumberInput` با فرمت خودکار
- تبدیل خودکار ارقام در کل پنل

### 🎨 ظاهر و UX
- RTL کامل همه کامپوننت‌ها
- فونت‌های فارسی (Vazirmatn, Estedad, Sahel, Samim, Shabnam)
- ۲۰ رنگ آماده
- صفحه تنظیمات ظاهری با ۶ تب
- ۱۰ قالب صفحه ورود

### 🔐 صفحه ورود
- ۱۰ قالب آماده (classic, minimal, split, glass, gradient, dark, neon, corporate, wave, pattern)
- پشتیبانی از 2FA
- ویرایش متن‌ها از پنل
- رنگ و فونت پویا

### 📊 ویجت و نمودار
- `PersianStatsWidget` انتزاعی
- `PersianChartWidget` با محور جلالی
- Tooltip فارسی

### 🔍 جستجو
- `HasPersianGlobalSearch` با نرمال‌سازی ی/ک
- Command Palette با `Ctrl+K`

### 🖼️ پروفایل کاربر
- ویرایش نام، ایمیل، آواتار
- تغییر رمز عبور
- نمایش آواتار در منو

---

## 🚀 نصب

### پیش‌نیازها

- PHP 8.2+
- Laravel 11, 12 یا 13
- Filament 5.x

### گام ۱ — نصب پکیج

```bash
composer require sghazanfari/filament-persian
```

> **نکته:** اگر در ایران هستی و به Packagist دسترسی نداری:
> ```bash
> composer config -g repos.packagist composer https://mirrors.aliyun.com/composer/
> ```

### گام ۲ — اجرای دستور نصب

```bash
php artisan filament-persian:install
```

این دستور:
- فایل config را منتشر می‌کند
- ترجمه‌های فارسی را کپی می‌کند
- Asset های JS/CSS را در `public/vendor/filament-persian/` می‌گذارد
- پوشه `storage/app/filament-persian/` را می‌سازد

### گام ۳ — تنظیم `.env`

```env
APP_LOCALE=fa
APP_FALLBACK_LOCALE=en
APP_TIMEZONE=Asia/Tehran
```

### گام ۴ — ثبت Plugin

فایل `app/Providers/Filament/AdminPanelProvider.php`:

```php
use Sghazanfari\FilamentPersian\FilamentPersianPlugin;
use Sghazanfari\FilamentPersian\Auth\Login;

public function panel(Panel $panel): Panel
{
    return $panel
        ->default()
        ->id('admin')
        ->path('admin')
        ->login(Login::class)
        ->plugin(FilamentPersianPlugin::make())
        ->font('Vazirmatn', ...)
        // ...
}
```

### گام ۵ — درج شهرهای ایران

```bash
php artisan filament-persian:seed-cities
```

### گام ۶ — بررسی نصب

```bash
php artisan filament-persian:doctor
```

باید خروجی سبز ببینی.

---

## ⚡ شروع سریع

بعد از نصب، این کارها را انجام بده:

1. **تنظیم ظاهر**: پنل → تنظیمات ظاهری → فونت و رنگ را انتخاب کن
2. **انتخاب قالب ورود**: تنظیمات ظاهری → تب صفحه ورود → یکی از ۱۰ قالب
3. **مشاهده مستندات**: پنل → مستندات (۱۳ فایل راهنما)
4. **مشاهده کامپوننت‌ها**: پنل → کامپوننت‌های آماده (کد قابل کپی)

---

## 📚 مستندات

مستندات کامل به‌صورت داخلی در پنل قابل دسترسی است:

**پنل → مستندات**

۱۳ فایل مستندات:
- معرفی و نصب
- تقویم جلالی
- اعداد فارسی
- اعتبارسنجی‌ها
- کامپوننت‌ها
- ویجت‌ها
- جستجو
- تنظیمات
- تغییرات
- داده‌های ایران
- تعطیلات و ساعت کاری
- قالب‌های صفحه ورود

---

## 💡 مثال‌های کاربردی

### مثال ۱: فرم سفارش با تاریخ جلالی

```php
use Sghazanfari\FilamentPersian\Forms\Components\JalaliDatePicker;
use Sghazanfari\FilamentPersian\Forms\Components\IranianMobileInput;
use Sghazanfari\FilamentPersian\Forms\Components\PersianNumberInput;

JalaliDatePicker::make('delivery_date')
    ->label('تاریخ تحویل')
    ->withTime(),

IranianMobileInput::make('customer_mobile')
    ->label('موبایل مشتری'),

PersianNumberInput::make('total')
    ->label('مبلغ کل')
    ->money('IRT'),
```

### مثال ۲: جدول سفارش‌ها

```php
use Sghazanfari\FilamentPersian\Tables\Columns\JalaliDateColumn;
use Sghazanfari\FilamentPersian\Tables\Columns\PersianMoneyColumn;
use Sghazanfari\FilamentPersian\Concerns\AutoJalaliDates;

class OrderResource extends Resource
{
    use AutoJalaliDates;

    public static function table(Table $table): Table
    {
        return static::autoJalaliDates(
            $table->columns([
                PersianTextColumn::make('order_number')->label('شماره'),
                PersianMoneyColumn::make('total')->label('مبلغ')->currency('IRT'),
            ])
        );
    }
}
```

### مثال ۳: ویجت آمار

```php
use Sghazanfari\FilamentPersian\Widgets\PersianStatsWidget;

class DashboardStats extends PersianStatsWidget
{
    protected function getStats(): array
    {
        return [
            $this->makeNumberStat('کاربران', User::count()),
            $this->makeMoneyStat('درآمد', Order::sum('total')),
            $this->makeDateStat('امروز', now(), 'l j F Y'),
        ];
    }
}
```

### مثال ۴: جستجوی فارسی

```php
use Sghazanfari\FilamentPersian\Concerns\HasPersianGlobalSearch;

class EventResource extends Resource
{
    use HasPersianGlobalSearch;

    public static function getGloballySearchableAttributes(): array
    {
        return ['title', 'body'];
    }
}
```

### مثال ۵: بررسی تعطیلی

```php
is_iran_holiday('2025-03-21');     // true
iran_holiday_names('2025-03-21');  // ['جشن نوروز', 'عید نوروز']
next_working_day(now());            // روز کاری بعدی
```

### مثال ۶: Helper Functions

```php
jalali_format(now(), 'Y/m/d H:i');   // ۱۴۰۳/۰۵/۱۲ ۱۴:۳۰
fa_number(1234567);                  // ۱٬۲۳۴٬۵۶۷
fa_money(48500000, 'IRT');           // ۴۸٬۵۰۰٬۰۰۰ تومان
is_valid_national_code('0084575948'); // true
detect_bank_from_card('6037997512345678'); // "بانک ملی ایران"
```

---

## 🎨 پیکربندی

فایل `config/filament-persian.php`:

```php
return [
    'locale' => 'fa',
    'direction' => 'rtl',
    'timezone' => 'Asia/Tehran',

    'font' => [
        'family' => 'Vazirmatn',
    ],

    'calendar' => [
        'type' => 'jalali',
        'digits' => 'persian',
    ],

    'numbers' => [
        'convert_in_ui' => true,
        'thousand_separator' => '٬',
        'decimal_separator' => '٫',
        'currency' => [
            'default' => 'IRT',
            'symbols' => [
                'IRT' => 'تومان',
                'IRR' => 'ریال',
            ],
        ],
    ],
];
```

همه این‌ها از **تنظیمات ظاهری** در پنل هم قابل تغییر هستند.

---

## ⌨️ دستورات Artisan

```bash
# نصب و راه‌اندازی
php artisan filament-persian:install

# بررسی سلامت
php artisan filament-persian:doctor

# درج شهرهای ایران
php artisan filament-persian:seed-cities

# درج مجدد از صفر
php artisan filament-persian:seed-cities --fresh
```

---

## 🔐 امنیت

- تمام اعتبارسنجی‌ها با checksum استاندارد
- رعایت OWASP Top 10
- بدون وابستگی خارجی مشکوک
- لایسنس MIT

**برای گزارش مشکل امنیتی**: ایمیل بزن به `dr.sgh67@gmail.com`

---

## 🤝 مشارکت

Pull Request ها پذیرفته می‌شوند. برای تغییرات بزرگ، اول Issue باز کن.

```bash
git clone https://github.com/ghazanfaris/filament-persian.git
cd filament-persian
composer install
```

---

## 📄 لایسنس

MIT License. [LICENSE.md](LICENSE.md) را ببین.

---

## 🙏 تشکر

- [Filament](https://filamentphp.com) — فریم‌ورک فوق‌العاده
- [Vazirmatn](https://github.com/rastikerdar/vazirmatn) — فونت زیبا
- [Spatie](https://spatie.be) — پکیج‌های بی‌نظیر
- و همه کسانی که باگ گزارش دادند یا مشارکت کردند

---

## 🔗 لینک‌های مفید

- [GitHub](https://github.com/ghazanfaris/filament-persian)
- [Issues](https://github.com/ghazanfaris/filament-persian/issues)
- [Releases](https://github.com/ghazanfaris/filament-persian/releases)

---

<div align="center">

**ساخته شده با ❤️ برای جامعه فارسی‌زبان**

⭐ اگر این پکیج برایت مفید بود، در GitHub ستاره بده

</div>