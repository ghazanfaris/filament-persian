<?php

namespace Sghazanfari\FilamentPersian\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Sghazanfari\FilamentPersian\Support\JalaliConverter;

class LegalEntityNationalId implements ValidationRule
{
    /**
     * وزن‌های استاندارد برای checksum.
     */
    protected const WEIGHTS = [2, 4, 6, 8, 10, 12, 14, 16, 18, 20];

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $id = app(JalaliConverter::class)->toLatinDigits((string) $value);
        $id = preg_replace('/[\s\-]/', '', $id);

        if (! preg_match('/^\d{11}$/', $id)) {
            $fail('شناسه ملی حقوقی باید ۱۱ رقم باشد.');
            return;
        }

        // کدهای تکراری
        if (preg_match('/^(\d)\1{10}$/', $id)) {
            $fail('شناسه ملی وارد شده معتبر نیست.');
            return;
        }

        // چک صفر بودن کامل
        if ($id === '00000000000') {
            $fail('شناسه ملی وارد شده معتبر نیست.');
            return;
        }

        // الگوریتم checksum
        $sum = 0;
        for ($i = 0; $i < 10; $i++) {
            $sum += ((int) $id[$i]) * self::WEIGHTS[$i];
        }

        $remainder = $sum % 11;
        $check = (int) $id[10];

        // remainder می‌تواند 0 تا 10 باشد. اگر 10 بود، کد در برخی منابع
        // قابل قبول است و در برخی نه. اینجا هر دو حالت را می‌پذیریم.
        $valid = ($remainder === $check) || ($remainder === 10 && $check === 0);

        if (! $valid) {
            $fail('شناسه ملی وارد شده معتبر نیست.');
        }
    }
}