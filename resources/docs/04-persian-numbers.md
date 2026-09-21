# اعداد و مبالغ فارسی

## خودکار در کل پنل

اگر در **تنظیمات ظاهری** → تب **ظاهر**، سوییچ **اعداد فارسی** روشن باشد، همه اعداد لاتین در پنل به فارسی تبدیل می‌شوند:

- `1234` → `۱۲۳۴`
- شماره ID
- Pagination
- شمارنده‌ها
- Badge ها

### غیرفعال کردن برای یک عنصر

```html
<div data-no-persian>
    1234 (این عدد لاتین می‌ماند)
</div>
```

### غیرفعال کردن کامل

**تنظیمات ظاهری** → تب **ظاهر** → سوییچ را خاموش کن.

## ستون پول

```php
use Sghazanfari\FilamentPersian\Tables\Columns\PersianMoneyColumn;

PersianMoneyColumn::make('amount')
    ->label('مبلغ')
    ->currency('IRT')      // تومان
    ->decimals(0)          // بدون اعشار
    ->sortable(),

PersianMoneyColumn::make('price')
    ->label('قیمت')
    ->currency('IRR')      // ریال
    ->decimals(2),         // با ۲ رقم اعشار
```

### خروجی

| ورودی | خروجی |
|---|---|
| `1234567` با IRT | ۱٬۲۳۴٬۵۶۷ تومان |
| `48500.5` با IRT و 2 decimals | ۴۸٬۵۰۰٫۵۰ تومان |
| `null` | — |

## ستون متن با اعداد فارسی

```php
use Sghazanfari\FilamentPersian\Tables\Columns\PersianTextColumn;

// با جداکننده هزارگان
PersianTextColumn::make('id')
    ->label('شناسه')
    ->thousands(),         // ۱٬۲۳۴

// فقط تبدیل ارقام
PersianTextColumn::make('mobile')
    ->label('موبایل'),   // ۰۹۱۲۳۴۵۶۷۸۹

// نگه‌داشتن لاتین
PersianTextColumn::make('national_code')
    ->label('کد ملی')
    ->withoutPersianDigits(),  // 0084575948 (لاتین)
```

## ورودی عدد

```php
use Sghazanfari\FilamentPersian\Forms\Components\PersianNumberInput;

// عدد ساده
PersianNumberInput::make('quantity')
    ->label('تعداد'),

// مبلغ با واحد پول
PersianNumberInput::make('amount')
    ->label('مبلغ')
    ->money('IRT'),

// با اعشار
PersianNumberInput::make('rate')
    ->label('نرخ')
    ->decimals(2),
```

### رفتار

- موقع تایپ: خودکار با کاما فرمت می‌شود (`1,234,567`)
- ارقام فارسی قبول می‌کند (`۱۲۳۴۵۶۷`)
- در دیتابیس: عدد خام ذخیره می‌شود (`1234567`)
- در Edit: با فرمت نمایش داده می‌شود

## Helper Functions

```php
// تبدیل ارقام
fa_digits('1234');              // ۱۲۳۴
en_digits('۱۲۳۴');              // 1234
en_digits('١٢٣٤');              // 1234 (عربی)

// فرمت عدد
fa_number(1234567);             // ۱٬۲۳۴٬۵۶۷
fa_number(1234.56, 2);          // ۱٬۲۳۴٫۵۶

// پول
fa_money(48500000, 'IRT');      // ۴۸٬۵۰۰٬۰۰۰ تومان
fa_money(48500000, 'IRR');      // ۴۸٬۵۰۰٬۰۰۰ ریال
```

## تنظیمات

از **تنظیمات ظاهری** → تب **اعداد**:

| تنظیم | گزینه‌ها |
|---|---|
| جداکننده هزارگان | `٬` (فارسی) / `,` / فاصله / بدون |
| ممیز اعشار | `٫` / `.` / `,` |
| واحد پول | تومان / ریال / بدون |

## مثال کامل

```php
class InvoiceResource extends Resource
{
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                PersianTextColumn::make('invoice_number')
                    ->label('شماره فاکتور'),

                PersianMoneyColumn::make('subtotal')
                    ->label('جمع کل')
                    ->currency('IRT'),

                PersianMoneyColumn::make('tax')
                    ->label('مالیات')
                    ->currency('IRT'),

                PersianMoneyColumn::make('total')
                    ->label('قابل پرداخت')
                    ->currency('IRT'),

                PersianTextColumn::make('quantity')
                    ->label('تعداد')
                    ->thousands(),
            ]);
    }
}
```

## نکته: عملکرد

تبدیل اعداد در سرور (PHP) انجام می‌شود، نه در مرورگر. این باعث می‌شود:

- SEO بهتر (متن رندرشده)
- بدون FOUC (پرش محتوا)
- سازگار با Print

برای اعداد خودکار در UI (مثل Pagination)، از JS استفاده می‌شود.