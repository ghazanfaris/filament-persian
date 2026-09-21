# تنظیمات

پکیج دو راه برای تنظیمات ارائه می‌دهد:

1. **از پنل** — برای کاربران (UI زیبا)
2. **از فایل config** — برای توسعه‌دهندگان

## تنظیمات از پنل

بعد از نصب، در منوی پنل یک صفحه به نام **تنظیمات ظاهری** داری.

### تب ظاهر

- **فونت:** ۵ فونت فارسی آماده (اگر پوشه `public/fonts/` داشته باشی، خودکار شناسایی می‌شوند)
- **رنگ:** ۲۰ رنگ آماده
- **اعداد فارسی:** روشن / خاموش
- **پیش‌نمایش زنده:** تغییرات را فوری ببین

### تب تاریخ

- **تقویم پیش‌فرض:** شمسی یا میلادی
- **فرمت تاریخ + ساعت:** مثلاً `Y/m/d H:i` یا `j F Y H:i`
- **فرمت فقط تاریخ:** `Y/m/d` یا `j F Y`
- **اولین روز هفته:** شنبه، یک‌شنبه، ...

### تب اعداد

- **جداکننده هزارگان:** `٬` (فارسی) / `,` / فاصله / بدون
- **ممیز اعشار:** `٫` / `.` / `,`
- **واحد پول پیش‌فرض:** تومان / ریال / بدون

### تب قالب‌های آماده

چهار قالب یک‌کلیکی:

| قالب | فونت | رنگ | مناسب |
|---|---|---|---|
| **اداری** | Vazirmatn | آبی | پنل‌های مدیریتی |
| **فروشگاهی** | Estedad | سبز | فروشگاه آنلاین |
| **شرکتی** | Vazirmatn | بنفش | گزارش‌های رسمی |
| **مینیمال** | Sahel | خاکستری | ساده با ارقام لاتین |

با یک کلیک، همه تنظیمات اعمال می‌شود. سپس می‌توانی دلخواه ویرایش کنی.

## محل ذخیره

تنظیماتی که از پنل ذخیره می‌کنی در این فایل هستند:

```
storage/app/filament-persian/settings.json
```

مثال:

```json
{
    "font": "vazirmatn",
    "color": "sky",
    "persian_numbers": true,
    "calendar": "jalali",
    "date_format": "Y/m/d H:i",
    "date_only_format": "Y/m/d",
    "first_day_of_week": 6,
    "thousand_separator": "٬",
    "decimal_separator": "٫",
    "currency": "IRT",
    "active_preset": "admin"
}
```

## تنظیمات از config

فایل `config/filament-persian.php`:

```php
return [
    'enabled' => true,
    'locale' => 'fa',
    'fallback_locale' => 'en',
    'direction' => 'rtl',
    'timezone' => 'Asia/Tehran',

    'font' => [
        'family' => 'Vazirmatn',
    ],

    'calendar' => [
        'type' => 'jalali',
        'digits' => 'persian',
        'months' => [...],
        'weekdays' => [...],
        'format' => [
            'date' => 'Y/m/d',
            'datetime' => 'Y/m/d H:i',
            'time' => 'H:i',
        ],
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

    'theme' => [
        'primary_color' => '#0ea5e9',
        'radius' => '0.75rem',
    ],
];
```

## اولویت

اگر تنظیمات هم در `config` و هم در `settings.json` باشند:

**`settings.json` برنده است.**

چون کاربر از پنل تغییر داده، و این باید بالاترین اولویت را داشته باشد.

## API برنامه‌نویسی

اگر می‌خواهی از کد تنظیمات را عوض کنی:

```php
use Sghazanfari\FilamentPersian\Settings\FontManager;

// فونت
FontManager::setFont('estedad');
FontManager::currentFont();  // اطلاعات فونت فعلی

// رنگ
FontManager::setColor('violet');
FontManager::currentColorKey();   // 'violet'
FontManager::currentColor();      // ['name' => 'بنفش', 'value' => '#8b5cf6']

// اعداد فارسی
FontManager::setPersianNumbers(true);
FontManager::persianNumbersEnabled();  // true

// تقویم
FontManager::setCalendar('jalali');
FontManager::calendar();  // 'jalali'

// فرمت تاریخ
FontManager::setDateFormat('Y/m/d H:i');
FontManager::dateFormat();  // 'Y/m/d H:i'

FontManager::setDateOnlyFormat('j F Y');
FontManager::dateOnlyFormat();  // 'j F Y'

// اولین روز هفته
FontManager::setFirstDayOfWeek(6);  // شنبه
FontManager::firstDayOfWeek();      // 6

// جداکننده‌ها
FontManager::setThousandSeparator('٬');
FontManager::thousandSeparator();  // '٬'

FontManager::setDecimalSeparator('٫');
FontManager::decimalSeparator();  // '٫'

// واحد پول
FontManager::setCurrency('IRT');
FontManager::currency();  // 'IRT'

// قالب آماده
FontManager::applyPreset('ecommerce');
FontManager::activePreset();  // 'ecommerce'

// همه تنظیمات
FontManager::all();  // آرایه کامل
```

## فونت‌های سفارشی

اگر فونت دیگری داری:

### گام ۱ — پوشه بساز

```
public/fonts/my-font/
├── MyFont-Regular.woff2
├── MyFont-Regular.ttf
├── MyFont-Bold.woff2
├── MyFont-Bold.ttf
└── my-font.css
```

### گام ۲ — فایل CSS

`public/fonts/my-font/my-font.css`:

```css
@font-face {
    font-family: 'MyFont';
    src: url('./MyFont-Regular.woff2') format('woff2'),
         url('./MyFont-Regular.ttf') format('truetype');
    font-weight: 400;
    font-style: normal;
    font-display: swap;
}

@font-face {
    font-family: 'MyFont';
    src: url('./MyFont-Bold.woff2') format('woff2'),
         url('./MyFont-Bold.ttf') format('truetype');
    font-weight: 700;
    font-style: normal;
    font-display: swap;
}
```

### گام ۳ — خودکار شناسایی می‌شود

پکیج پوشه `public/fonts/` را اسکن می‌کند و هر پوشه‌ای که فایل `.css` با همان نام داشته باشد را می‌شناسد.

## تنظیمات در `.env`

```env
FILAMENT_PERSIAN_ENABLED=true
FILAMENT_PERSIAN_LOCALE=fa
FILAMENT_PERSIAN_DIRECTION=rtl
FILAMENT_PERSIAN_TIMEZONE=Asia/Tehran
```

## پاک کردن تنظیمات

اگر می‌خواهی همه تنظیمات به حالت پیش‌فرض برگردد:

```bash
rm storage/app/filament-persian/settings.json
```

یا از کد:

```php
use Sghazanfari\FilamentPersian\Settings\FontManager;

FontManager::set('font', null);
FontManager::set('color', null);
// ...
```

## نکته: multi-panel

اگر چند پنل داری (مثلاً `admin` و `user`)، تنظیمات مشترک است.

اگر می‌خواهی جدا باشند، بگو تا قابلیت multi-tenant اضافه کنیم.