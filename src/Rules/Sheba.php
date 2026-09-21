<?php

namespace Sghazanfari\FilamentPersian\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Sghazanfari\FilamentPersian\Support\JalaliConverter;

class Sheba implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $value = app(JalaliConverter::class)->toLatinDigits((string) $value);
        $value = strtoupper(preg_replace('/[\s\-]/', '', $value));
        $value = preg_replace('/^IR/', '', $value);

        if (! preg_match('/^\d{24}$/', $value)) {
            $fail('شبا باید ۲۴ رقم بعد از IR باشد.');
            return;
        }

        // الگوریتم IBAN
        $rearranged = substr($value, 2) . '1827' . substr($value, 0, 2);
        $mod = '';
        $len = strlen($rearranged);
        for ($i = 0; $i < $len; $i++) {
            $mod = (int) ($mod . $rearranged[$i]) % 97;
        }

        if ($mod !== 1) {
            $fail('شماره شبا وارد شده معتبر نیست.');
        }
    }
}