# نصب

## پیش‌نیازها

قبل از شروع، مطمئن شو این‌ها نصب هستند:

```bash
php -v          # باید 8.2 یا بالاتر باشد
composer -V     # نسخه 2
```

## گام ۱ — نصب پکیج

```bash
composer require sghazanfari/filament-persian
```

> **نکته:** اگر در ایران هستی و به Packagist دسترسی نداری، از آینه استفاده کن:
> ```bash
> composer config -g repos.packagist composer https://mirrors.aliyun.com/composer/
> ```

## گام ۲ — اجرای دستور نصب

```bash
php artisan filament-persian:install
```

این دستور به‌طور خودکار:

1. فایل `config/filament-persian.php` را منتشر می‌کند
2. ترجمه‌های فارسی را در `lang/vendor/filament-persian` کپی می‌کند
3. فایل‌های JS و CSS را در `public/vendor/filament-persian/` می‌گذارد
4. پوشه `storage/app/filament-persian/` را می‌سازد
5. `.env` را با مقادیر پیش‌فرض به‌روزرسانی می‌کند

## گام ۳ — تنظیم `.env`

فایل `.env` را باز کن و این خطوط را اضافه کن:

```env
APP_LOCALE=fa
APP_FALLBACK_LOCALE=en
APP_TIMEZONE=Asia/Tehran
```

## گام ۴ — ثبت Plugin در Panel

فایل `app/Providers/Filament/AdminPanelProvider.php` را باز کن:

```php
use Sghazanfari\FilamentPersian\FilamentPersianPlugin;

public function panel(Panel $panel): Panel
{
    return $panel
        ->default()
        ->id('admin')
        ->path('admin')
        ->plugin(FilamentPersianPlugin::make());
}
```

## گام ۵ — پاک کردن کش

```bash
php artisan optimize:clear
```

## گام ۶ — بررسی نصب

```bash
php artisan filament-persian:doctor
```

اگر همه چیز درست باشد، خروجی سبز می‌بینی:

```
✓ PHP نسخه 8.3.x
✓ Laravel نسخه 13.x
✓ Filament نسخه v5.8.x
✓ فایل config لود شده
✓ پوشه تنظیمات آماده است
...
```

## بعد از نصب چه چیزی داری

بعد از نصب، این صفحات در پنل ظاهر می‌شوند:

| صفحه | مسیر | توضیح |
|---|---|---|
| مستندات | `/admin/documentation` | همین مستندات |
| درباره | `/admin/about` | معرفی پکیج |
| تنظیمات ظاهری | `/admin/font-settings` | فونت، رنگ، اعداد |
| پروفایل من | منوی کاربر | ویرایش پروفایل |

## مراحل بعدی

از سایدبار همین صفحه، می‌توانی مستندات هر بخش را ببینی:

- **تقویم جلالی** — چطور در فرم و جدول استفاده کنی
- **اعداد فارسی** — چطور اعداد را فارسی کنی
- **اعتبارسنجی‌ها** — کد ملی، موبایل، شبا
- **کامپوننت‌ها** — فهرست همه کامپوننت‌ها
- **تنظیمات** — چطور تنظیمات را تغییر بدهی

روی هر کدام در سایدبار سمت راست کلیک کن.

## عیب‌یابی

### خطای `Class not found`

```bash
composer dump-autoload
php artisan optimize:clear
```

### خطای `key too long` در MySQL

اگر MySQL قدیمی داری، در `app/Providers/AppServiceProvider.php`:

```php
use Illuminate\Support\Facades\Schema;

public function boot(): void
{
    Schema::defaultStringLength(191);
}
```

### ترجمه‌ها ظاهر نمی‌شوند

```bash
php artisan vendor:publish --tag=filament-persian-lang --force
php artisan optimize:clear
```