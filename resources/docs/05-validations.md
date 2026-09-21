# اعتبارسنجی‌های ایرانی

پکیج شامل ۱۱ قاعده اعتبارسنجی استاندارد ایران است:

| # | اعتبارسنجی | کلاس | کامپوننت |
|---|---|---|---|
| ۱ | کد ملی | `NationalCode` | `IranianNationalCodeInput` |
| ۲ | موبایل | `Mobile` | `IranianMobileInput` |
| ۳ | شبا (IBAN) | `Sheba` | `IranianShebaInput` |
| ۴ | کد پستی | `PostalCode` | — |
| ۵ | شماره کارت بانکی | `BankCardNumber` | `IranianBankCardInput` |
| ۶ | شناسه ملی حقوقی | `LegalEntityNationalId` | `IranianLegalEntityIdInput` |
| ۷ | شماره اقتصادی | `EconomicCode` | `IranianEconomicCodeInput` |
| ۸ | پلاک خودرو | `VehiclePlate` | `IranianVehiclePlateInput` |
| ۹ | شماره بیمه تأمین اجتماعی | `SocialSecurityNumber` | `IranianSocialSecurityInput` |
| ۱۰ | کد بورسی | `StockExchangeCode` | `IranianStockExchangeCodeInput` |
| ۱۱ | شماره گذرنامه | `PassportNumber` | `IranianPassportInput` |

---

## ۱. کد ملی

### کامپوننت فرم

```php
use Sghazanfari\FilamentPersian\Forms\Components\IranianNationalCodeInput;

IranianNationalCodeInput::make('national_code')
    ->label('کد ملی'),
```

### استفاده در Rule

```php
use Sghazanfari\FilamentPersian\Rules\NationalCode;

TextInput::make('national_code')
    ->rule(new NationalCode())
    ->maxLength(10),
```

### الگوریتم

checksum استاندارد ۱۰ رقمی با اعتبارسنجی رقم کنترل. کدهای تکراری (`1111111111`) رد می‌شوند.

**مثال معتبر:** `0084575948`

### Helper

```php
is_valid_national_code('0084575948');  // true
```

---

## ۲. موبایل

### کامپوننت فرم

```php
use Sghazanfari\FilamentPersian\Forms\Components\IranianMobileInput;

IranianMobileInput::make('mobile')
    ->label('موبایل'),
```

### قابلیت‌ها

- `09123456789` ✅
- `+989123456789` ✅ (خودکار به `09...`)
- `00989123456789` ✅
- `989123456789` ✅
- `0912 345 6789` ✅ (حذف فاصله)
- `۰۹۱۲۳۴۵۶۷۸۹` ✅ (ارقام فارسی)

### Helper

```php
is_valid_mobile('09123456789');       // true
normalize_mobile('+98 912 345 6789'); // 09123456789
```

---

## ۳. شبا (IBAN)

### کامپوننت فرم

```php
use Sghazanfari\FilamentPersian\Forms\Components\IranianShebaInput;

IranianShebaInput::make('sheba')
    ->label('شماره شبا'),
```

### قابلیت‌ها

- `IR` خودکار اضافه می‌شود
- الگوریتم IBAN بین‌المللی

**مثال معتبر:** `IR820540102680020817909002`

### Helper

```php
is_valid_sheba('IR820540102680020817909002'); // true
normalize_sheba('820540102680020817909002');  // IR820...
```

---

## ۴. کد پستی

```php
use Sghazanfari\FilamentPersian\Rules\PostalCode;

TextInput::make('postal_code')
    ->rule(new PostalCode())
    ->maxLength(10),
```

### قواعد

- ۱۰ رقم
- کدهای تکراری رد می‌شوند

### Helper

```php
is_valid_postal_code('1234567890');  // true
```

---

## ۵. شماره کارت بانکی

### کامپوننت فرم

```php
use Sghazanfari\FilamentPersian\Forms\Components\IranianBankCardInput;

IranianBankCardInput::make('bank_card')
    ->label('شماره کارت'),
```

با ماسک خودکار `6037-9975-1234-5678`.

### استفاده در Rule

```php
use Sghazanfari\FilamentPersian\Rules\BankCardNumber;

TextInput::make('bank_card')
    ->rule(new BankCardNumber()),
```

### قابلیت‌ها

- ۱۶ رقم با جداکننده خودکار
- تشخیص بانک بر اساس پیشوند ۶ رقمی
- پشتیبانی از ۴۰+ پیشوند بانک ایرانی

### Helper

```php
// بررسی
is_valid_bank_card('6037997512345678');  // true
is_valid_bank_card('1111111111111111');  // false

// تشخیص بانک
detect_bank_from_card('6037997512345678');       // "بانک ملی ایران"
detect_bank_from_card_full('6037997512345678');  // ['code' => 'melli', 'name' => 'بانک ملی ایران', ...]

// فرمت
format_bank_card('6037997512345678');  // "6037-9975-1234-5678"
```

---

## ۶. شناسه ملی حقوقی

### کامپوننت فرم

```php
use Sghazanfari\FilamentPersian\Forms\Components\IranianLegalEntityIdInput;

IranianLegalEntityIdInput::make('legal_id')
    ->label('شناسه ملی'),
```

### استفاده در Rule

```php
use Sghazanfari\FilamentPersian\Rules\LegalEntityNationalId;

TextInput::make('legal_id')
    ->rule(new LegalEntityNationalId()),
```

### الگوریتم

۱۱ رقمی با checksum استاندارد (وزن‌های `[2,4,6,8,10,12,14,16,18,20]`).

**مثال معتبر:** `10101754291`

### Helper

```php
is_valid_legal_entity_national_id('10101754291');   // true
is_valid_legal_entity_national_id('11111111111');   // false
```

---

## ۷. شماره اقتصادی (۱۴ رقمی)

### کامپوننت فرم

```php
use Sghazanfari\FilamentPersian\Forms\Components\IranianEconomicCodeInput;

IranianEconomicCodeInput::make('economic_code')
    ->label('شماره اقتصادی'),
```

### استفاده در Rule

```php
use Sghazanfari\FilamentPersian\Rules\EconomicCode;

TextInput::make('economic_code')
    ->rule(new EconomicCode())
    ->maxLength(14),
```

### الگوریتم

- ۱۴ رقم
- کدهای تکراری رد می‌شوند
- ⚠️ **اعتبارسنجی واقعی نیاز به API سازمان امور مالیاتی دارد**

### Helper

```php
is_valid_economic_code('41123456789012');   // true
is_valid_economic_code('11111111111111');   // false
```

---

## ۸. پلاک خودرو

### کامپوننت فرم

```php
use Sghazanfari\FilamentPersian\Forms\Components\IranianVehiclePlateInput;

IranianVehiclePlateInput::make('vehicle_plate')
    ->label('شماره پلاک'),
```

### استفاده در Rule

```php
use Sghazanfari\FilamentPersian\Rules\VehiclePlate;

TextInput::make('vehicle_plate')
    ->rule(new VehiclePlate()),
```

### الگوهای پشتیبانی‌شده

- `12ب345` (پلاک شخصی)
- `12ب345ایران67` (با کد ایران)
- `12ب34567` (فرمت خلاصه)
- `۱۲ ب ۳۴۵ ایران ۶۷` (با ارقام فارسی و فاصله)

### Helper

```php
is_valid_vehicle_plate('12ب345');           // true
is_valid_vehicle_plate('۱۲ب۳۴۵');           // true
is_valid_vehicle_plate('12ب345ایران67');    // true

format_vehicle_plate('12ب34567');           // "12ب345 ایران 67"
normalize_vehicle_plate('۱۲ ب ۳۴۵');         // "12ب345"
```

---

## ۹. شماره بیمه تأمین اجتماعی

### کامپوننت فرم

```php
use Sghazanfari\FilamentPersian\Forms\Components\IranianSocialSecurityInput;

IranianSocialSecurityInput::make('ssn')
    ->label('شماره بیمه'),
```

با ماسک خودکار `123-456789-0`.

### استفاده در Rule

```php
use Sghazanfari\FilamentPersian\Rules\SocialSecurityNumber;

TextInput::make('ssn')
    ->rule(new SocialSecurityNumber())
    ->maxLength(10),
```

### Helper

```php
is_valid_social_security_number('1234567890');   // true
is_valid_social_security_number('1111111111');   // false

format_social_security_number('1234567890');     // "123-456789-0"
```

---

## ۱۰. کد بورسی

### کامپوننت فرم

```php
use Sghazanfari\FilamentPersian\Forms\Components\IranianStockExchangeCodeInput;

IranianStockExchangeCodeInput::make('stock_code')
    ->label('کد بورسی'),
```

### استفاده در Rule

```php
use Sghazanfari\FilamentPersian\Rules\StockExchangeCode;

TextInput::make('stock_code')
    ->rule(new StockExchangeCode())
    ->maxLength(11),
```

### Helper

```php
is_valid_stock_exchange_code('12345678901');   // true
is_valid_stock_exchange_code('11111111111');   // false
```

> ⚠️ **اعتبارسنجی واقعی نیاز به API سازمان بورس یا سجام دارد**

---

## ۱۱. شماره گذرنامه

### کامپوننت فرم

```php
use Sghazanfari\FilamentPersian\Forms\Components\IranianPassportInput;

IranianPassportInput::make('passport')
    ->label('شماره گذرنامه'),
```

### استفاده در Rule

```php
use Sghazanfari\FilamentPersian\Rules\PassportNumber;

TextInput::make('passport')
    ->rule(new PassportNumber()),
```

### الگوهای پشتیبانی‌شده

- `A12345678` (پاسپورت جدید)
- `123456789` (پاسپورت قدیمی)
- `AB1234567` (موارد خاص)

### Helper

```php
is_valid_passport_number('A12345678');   // true
is_valid_passport_number('123456789');   // true

format_passport_number('A12345678');     // "A - 12345678"
normalize_passport_number('a۱۲۳۴۵۶۷۸');  // "A12345678"
```

---

## جدول خلاصه Helper Functions

| تابع | توضیح |
|---|---|
| `is_valid_national_code($code)` | کد ملی |
| `is_valid_mobile($mobile)` | موبایل |
| `normalize_mobile($mobile)` | نرمال‌سازی موبایل |
| `is_valid_sheba($sheba)` | شبا |
| `normalize_sheba($sheba)` | نرمال‌سازی شبا |
| `is_valid_postal_code($code)` | کد پستی |
| `is_valid_bank_card($card)` | شماره کارت |
| `detect_bank_from_card($card)` | تشخیص بانک (رشته) |
| `detect_bank_from_card_full($card)` | تشخیص بانک (آرایه) |
| `format_bank_card($card)` | فرمت ۴-۴-۴-۴ |
| `is_valid_legal_entity_national_id($id)` | شناسه ملی حقوقی |
| `is_valid_economic_code($code)` | شماره اقتصادی |
| `is_valid_vehicle_plate($plate)` | پلاک خودرو |
| `format_vehicle_plate($plate)` | فرمت پلاک |
| `is_valid_social_security_number($ssn)` | شماره بیمه |
| `format_social_security_number($ssn)` | فرمت 3-6-1 |
| `is_valid_stock_exchange_code($code)` | کد بورسی |
| `is_valid_passport_number($passport)` | شماره گذرنامه |
| `format_passport_number($passport)` | فرمت A - 12345678 |

---

## مثال کامل: فرم ثبت‌نام کامل

```php
use Sghazanfari\FilamentPersian\Forms\Components\IranianNationalCodeInput;
use Sghazanfari\FilamentPersian\Forms\Components\IranianMobileInput;
use Sghazanfari\FilamentPersian\Forms\Components\IranianShebaInput;
use Sghazanfari\FilamentPersian\Forms\Components\IranianBankCardInput;
use Sghazanfari\FilamentPersian\Forms\Components\IranianLegalEntityIdInput;
use Sghazanfari\FilamentPersian\Forms\Components\IranianPassportInput;
use Sghazanfari\FilamentPersian\Forms\Components\IranianVehiclePlateInput;

class RegistrationForm
{
    public static function schema(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('نام و نام خانوادگی')
                    ->required(),

                IranianNationalCodeInput::make('national_code')
                    ->label('کد ملی')
                    ->required(),

                IranianMobileInput::make('mobile')
                    ->label('موبایل')
                    ->required(),

                IranianPassportInput::make('passport')
                    ->label('شماره گذرنامه (اختیاری)'),

                IranianShebaInput::make('sheba')
                    ->label('شماره شبا'),

                IranianBankCardInput::make('bank_card')
                    ->label('شماره کارت'),

                IranianLegalEntityIdInput::make('legal_id')
                    ->label('شناسه ملی حقوقی'),

                IranianVehiclePlateInput::make('vehicle_plate')
                    ->label('پلاک خودرو'),

                TextInput::make('postal_code')
                    ->label('کد پستی')
                    ->rule(new \Sghazanfari\FilamentPersian\Rules\PostalCode())
                    ->maxLength(10),
            ]);
    }
}
```

---

## نکته امنیتی

**checksum به‌تنهایی کافی نیست.** کد ملی با checksum درست می‌تواند واقعاً وجود نداشته باشد. برای احراز هویت واقعی:

- **کد ملی:** API ثبت احوال (با قرارداد سازمانی)
- **شماره اقتصادی:** API سازمان امور مالیاتی
- **کد بورسی:** API سازمان بورس یا سجام

پکیج فقط **ساختار** را چک می‌کند، نه **وجود واقعی**.

---

## افزودن اعتبارسنجی جدید

اگر اعتبارسنجی دیگری لازم داری:

1. یک کلاس در `Rules/` بساز
2. `ValidationRule` را implement کن
3. در `validate()` منطق را بنویس

یا به ما Issue بده تا اضافه کنیم.