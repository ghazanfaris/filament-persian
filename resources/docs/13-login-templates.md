# قالب‌های صفحه ورود

پکیج شامل **۱۰ قالب آماده** برای صفحه ورود است. همه قالب‌ها:

- ✅ راست‌چین (RTL)
- ✅ با فونت پویا (از تنظیمات ظاهری)
- ✅ رنگ primary پویا
- ✅ پشتیبانی از ورود دو مرحله‌ای (2FA)
- ✅ پشتیبانی از Remember Me
- ✅ پشتیبانی از فراموشی رمز
- ✅ پاسخگو (موبایل و دسکتاپ)
- ✅ قابلیت ویرایش متن‌ها

---

## فهرست قالب‌ها

| # | نام | توضیح |
|---|---|---|
| ۱ | `classic` | گرادیانت رنگی با کارت وسط صفحه |
| ۲ | `minimal` | ساده و تمیز، بدون حاشیه |
| ۳ | `split` | تصویر سمت راست، فرم سمت چپ |
| ۴ | `glass` | مدرن با پس‌زمینه تار (Glassmorphism) |
| ۵ | `gradient` | تمام صفحه گرادیانت رنگی |
| ۶ | `dark` | تم تاریک برای شب |
| ۷ | `neon` | درخشان با رنگ نئون |
| ۸ | `corporate` | رسمی با لوگو بزرگ |
| ۹ | `wave` | با موج تزئینی SVG |
| ۱۰ | `pattern` | الگوی هندسی تزئینی |

---

## فعال‌سازی

### پیش‌نیاز: ثبت `Login` در Panel

فایل `app/Providers/Filament/AdminPanelProvider.php` را باز کن:

```php
use Sghazanfari\FilamentPersian\Auth\Login;

public function panel(Panel $panel): Panel
{
    return $panel
        ->default()
        ->id('admin')
        ->path('admin')
        ->login(Login::class)   // ← این خط
        // ...
}
```

### انتخاب قالب از پنل (توصیه‌شده)

1. برو به **تنظیمات ظاهری**
2. تب **🔐 صفحه ورود**
3. یکی از ۱۰ قالب را انتخاب کن
4. **ذخیره تغییرات**
5. `Ctrl + F5` بزن و `/admin/login` را ببین

### انتخاب قالب از کد

```php
use Sghazanfari\FilamentPersian\Settings\FontManager;

// انتخاب قالب
FontManager::setLoginTemplate('split');

// قالب فعلی
FontManager::loginTemplate();   // 'split'

// فهرست قالب‌ها
FontManager::loginTemplates();
```

---

## ویرایش متن‌های صفحه ورود

### از پنل

**تنظیمات ظاهری** → تب **صفحه ورود** → بخش **✏️ متن‌های صفحه ورود**

پنج متن قابل ویرایش:

| کلید | توضیح | پیش‌فرض |
|---|---|---|
| `brand_line` | زیرنویس برند (در هدر) | «پنل مدیریت» |
| `heading` | عنوان اصلی فرم | «ورود به حساب» |
| `subheading` | زیرعنوان فرم | «برای ادامه، اطلاعات خود را وارد کنید» |
| `footer` | متن فوتر | «ساخته شده با ❤» |
| `copyright_prefix` | پیشوند کپی‌رایت | «©» |

### از کد

```php
use Sghazanfari\FilamentPersian\Settings\FontManager;

// تغییر یک متن
FontManager::setLoginText('heading', 'خوش آمدید');
FontManager::setLoginText('subheading', 'برای ادامه وارد شوید');
FontManager::setLoginText('footer', 'ساخته شده با عشق در ایران');

// تغییر همه با هم
FontManager::setLoginTexts([
    'brand_line' => 'پنل مدیریت من',
    'heading' => 'ورود',
    'subheading' => 'اطلاعات خود را وارد کنید',
    'footer' => '© ۱۴۰۵',
    'copyright_prefix' => '©',
]);

// همه متن‌ها
FontManager::loginTexts();

// یک متن
FontManager::loginText('heading');   // "ورود"

// بازنشانی به پیش‌فرض
FontManager::resetLoginTexts();
```

---

## توضیح هر قالب

### ۱. Classic (کلاسیک)

کارت سفید شناور وسط صفحه با گرادیانت رنگی در پس‌زمینه. مناسب برای اکثر پنل‌ها.

- لوگو یا آیکون در بالا
- نام برند
- فرم لاگین در کارت
- فوتر با سال شمسی

### ۲. Minimal (مینیمال)

بسیار ساده و تمیز. بدون حاشیه اضافی. مناسب برای پنل‌های حرفه‌ای که طراحی ساده می‌خواهند.

- همه چیز در یک ستون باریک
- بدون کادر
- فقط فرم + لوگو

### ۳. Split (دوستونه)

صفحه به دو نیمه تقسیم می‌شود:

- **نیمه راست:** گرادیانت رنگی + نام برند + شعار
- **نیمه چپ:** فرم لاگین

در موبایل، نیمه راست مخفی می‌شود.

### ۴. Glass (شیشه‌ای)

کارت با پس‌زمینه نیمه‌شفاف و blur. مدرن و زیبا. مناسب پنل‌های خلاقانه.

- فیلدها با پس‌زمینه شفاف
- متن سفید
- دکمه سفید

### ۵. Gradient (گرادیانت)

تمام صفحه با گرادیانت رنگی. کارت سفید وسط. 

- هدر بالا با نام برند و تاریخ شمسی
- فرم وسط
- فوتر پایین

### ۶. Dark (تیره)

تم تاریک کامل. مناسب برای پنل‌های developer یا پنل‌هایی که کاربر در شب استفاده می‌کند.

- پس‌زمینه `#0a0a0a`
- کارت تیره
- فیلدهای تاریک
- رنگ primary از تنظیمات

### ۷. Neon (نئون)

تم تاریک با افکت نئون. درخشان و مدرن.

- خطوط شبکه‌ای در پس‌زمینه
- متن با سایه درخشان
- دکمه‌های outline با هاله
- افکت pulse روی لوگو

### ۸. Corporate (شرکتی)

رسمی و اداری. مناسب برای پنل‌های سازمانی.

- هدر تیره با لوگو و تاریخ
- کارت سفید با border-top رنگی
- فوتر تیره
- طراحی رسمی

### ۹. Wave (موج)

با موج SVG تزئینی در بالا و پایین. مناسب پنل‌هایی که می‌خواهند حس ملایم و لطیف داشته باشند.

- پس‌زمینه آبی روشن
- موج‌های رنگی
- کارت سفید وسط

### ۱۰. Pattern (الگو)

با الگوی هندسی تزئینی. مناسب پنل‌های حرفه‌ای و مدرن.

- الگوی SVG با رنگ primary
- کارت سفید وسط
- طراحی تمیز

---

## پشتیبانی از 2FA

تمام ۱۰ قالب با **ورود دو مرحله‌ای** سازگارند. وقتی کاربر رمز را وارد می‌کند و 2FA فعال است، همان قالب نمایش داده می‌شود و کد یکبارمصرف خواسته می‌شود.

هیچ تنظیم اضافه‌ای نیاز نیست. **کافیست کلاس `Login` ثبت شود** و قالب انتخاب شود.

### تست 2FA

1. `filament/filament` باید 2FA داشته باشد
2. یک کاربر با 2FA بساز
3. خروج بزن
4. لاگین کن
5. بعد از رمز، باید کد 2FA در **همان قالب** خواسته شود

---

## پشتیبانی از سایر صفحه‌های Auth

قالب انتخابی روی این صفحه‌ها هم اعمال می‌شود:

- **فراموشی رمز** (`/admin/password-reset/request`)
- **بازنشانی رمز** (`/admin/password-reset/reset`)
- **تأیید ایمیل** (`/admin/email-verification/prompt`)
- **2FA** (`/admin/multi-factor-challenge`)
- **ثبت‌نام** (اگر فعال باشد)

**نکته:** چون همه این صفحه‌ها از `SimplePage` ارث می‌برند، همه از یک قالب استفاده می‌کنند.

---

## ساخت قالب جدید

اگر یکی از ۱۰ قالب را نپسندیدی یا می‌خواهی قالب خودت را بسازی:

### گام ۱ — کلاس `Login`

کلاس `Login` در `packages/sghazanfari/filament-persian/src/Auth/Login.php` را ببین.

### گام ۲ — ساخت فایل Blade

یک فایل جدید بساز:

```
resources/views/vendor/filament-persian/auth/layouts/my-template.blade.php
```

از یکی از فایل‌های موجود در `packages/sghazanfari/filament-persian/resources/views/auth/layouts/` کپی کن و ویرایش کن.

### گام ۳ — ثبت قالب

فایل `FontManager.php` را باز کن و به `loginTemplates()` قالب جدید را اضافه کن:

```php
'my-template' => [
    'name' => 'قالب من',
    'description' => 'توضیح قالب',
    'icon' => '🎨',
],
```

### گام ۴ — فعال‌سازی

از **تنظیمات ظاهری** → تب **صفحه ورود** → قالب جدید را انتخاب کن.

---

## API برنامه‌نویسی

```php
use Sghazanfari\FilamentPersian\Settings\FontManager;

// ============ قالب‌ها ============

FontManager::loginTemplates();           // آرایه همه قالب‌ها
FontManager::loginTemplate();            // قالب فعلی
FontManager::setLoginTemplate('split'); // تغییر قالب

// ============ متن‌ها ============

FontManager::loginTexts();               // آرایه متن‌ها
FontManager::loginText('heading');       // یک متن
FontManager::setLoginText('heading', 'خوش آمدید');
FontManager::setLoginTexts([...]);
FontManager::resetLoginTexts();          // بازنشانی به پیش‌فرض
```

---

## محل ذخیره

قالب و متن‌ها در فایل تنظیمات ذخیره می‌شوند:

```
storage/app/filament-persian/settings.json
```

مثال:

```json
{
    "login_template": "split",
    "login_texts": {
        "brand_line": "پنل مدیریت من",
        "heading": "ورود به حساب",
        "subheading": "برای ادامه، اطلاعات خود را وارد کنید",
        "footer": "ساخته شده با ❤",
        "copyright_prefix": "©"
    }
}
```

---

## عیب‌یابی

### قالب اعمال نمی‌شود

**دلیل ۱:** کلاس `Login` در `AdminPanelProvider` ثبت نشده.

```php
// باید این را داشته باشی:
->login(\Sghazanfari\FilamentPersian\Auth\Login::class)
```

**دلیل ۲:** کش Laravel

```bash
php artisan optimize:clear
php artisan view:clear
```

### متن‌ها نمایش داده نمی‌شوند

اگر متن‌های پیش‌فرض Filament نمایش داده می‌شوند:

1. `AppServiceProvider` را چک کن
2. `lang/vendor/filament-panels/fa/auth/pages/login.php` را ببین

### 2FA کار نمی‌کند

اگر بعد از لاگین، صفحه 2FA به قالب پیش‌فرض Filament رفت:

1. مطمئن شو `->login(Login::class)` ثبت شده
2. اگر بازم مشکل بود، یک Issue باز کن

### کاراکترهای عربی در دیتابیس

اگر متن‌های فارسی نمایش درست نمی‌دهند:

```bash
php artisan filament-persian:doctor
```

---

## مثال کامل: پنل فروشگاهی

```php
use Sghazanfari\FilamentPersian\Auth\Login;
use Sghazanfari\FilamentPersian\Settings\FontManager;

// در AdminPanelProvider
->login(Login::class)

// در ServiceProvider (اختیاری)
FontManager::setLoginTemplate('split');
FontManager::setLoginText('brand_line', 'فروشگاه من');
FontManager::setLoginText('heading', 'ورود به فروشگاه');
FontManager::setLoginText('subheading', 'خرید خود را ادامه دهید');
FontManager::setLoginText('footer', 'پشتیبانی: ۰۲۱-۱۲۳۴۵۶۷۸');
```

---

## در ادامه

- [تنظیمات](settings)
- [کامپوننت‌ها](components)
- [تاریخ جلالی](jalali-calendar)