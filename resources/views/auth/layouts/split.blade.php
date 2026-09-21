@props([
    'after' => null,
    'heading' => null,
    'subheading' => null,
])

@php
    use Filament\Support\Enums\Width;
    use Filament\Support\Facades\FilamentView;
    use Filament\View\PanelsRenderHook;
    use Sghazanfari\FilamentPersian\Settings\FontManager;

    $livewire ??= null;
    $renderHookScopes = $livewire?->getRenderHookScopes();
    $maxContentWidth ??= (filament()->getSimplePageMaxContentWidth() ?? Width::Large);

    if (is_string($maxContentWidth)) {
        $maxContentWidth = Width::tryFrom($maxContentWidth) ?? $maxContentWidth;
    }

    $primaryColor = FontManager::currentColor()['value'] ?? '#0ea5e9';
    $fontFamily = FontManager::currentFont()['family'] ?? 'Vazirmatn';
    $brandName = filament()->getBrandName() ?? config('app.name', 'پنل مدیریت');
    $brandLogo = filament()->getBrandLogo();
@endphp

<x-filament-panels::layout.base :livewire="$livewire">
    <div
        dir="rtl"
        style="
            min-height: 100vh;
            display: grid;
            grid-template-columns: 1fr 1fr;
            font-family: '{{ $fontFamily }}', system-ui, sans-serif;
            background: #ffffff;
        "
    >
        {{-- سمت راست: تصویر/گرادیانت --}}
        <div style="
            position: relative;
            overflow: hidden;
            background: linear-gradient(135deg, {{ $primaryColor }} 0%, {{ $primaryColor }}cc 50%, #1e293b 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px;
        ">
            {{-- شکل‌های تزئینی --}}
            <div style="position: absolute; top: -100px; right: -100px; width: 400px; height: 400px; border-radius: 50%; border: 40px solid rgba(255,255,255,0.08);"></div>
            <div style="position: absolute; bottom: -80px; left: -80px; width: 300px; height: 300px; border-radius: 50%; border: 30px solid rgba(255,255,255,0.06);"></div>
            <div style="position: absolute; top: 40%; left: 20%; width: 200px; height: 200px; border-radius: 50%; background: rgba(255,255,255,0.05); filter: blur(40px);"></div>

            <div style="position: relative; z-index: 1; text-align: center; color: white; max-width: 400px;">
                @if($brandLogo)
                    <img src="{{ $brandLogo }}" alt="{{ $brandName }}" style="height: 72px; margin-bottom: 24px;" />
                @else
                    <div style="
                        display: inline-flex;
                        align-items: center;
                        justify-content: center;
                        width: 90px;
                        height: 90px;
                        border-radius: 24px;
                        background: rgba(255,255,255,0.15);
                        backdrop-filter: blur(10px);
                        margin-bottom: 24px;
                        font-size: 44px;
                    ">🇮🇷</div>
                @endif

                <h2 style="margin: 0 0 12px 0; font-size: 32px; font-weight: 800; letter-spacing: -0.5px;">
                    {{ $brandName }}
                </h2>
                <p style="margin: 0; font-size: 15px; line-height: 1.7; opacity: 0.9;">
                    به پنل مدیریت خوش آمدید<br>
                    برای ادامه، اطلاعات خود را وارد کنید.
                </p>

                <div style="
                    margin-top: 40px;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    gap: 8px;
                    font-size: 12px;
                    opacity: 0.8;
                ">
                    <span>© {{ fa_digits(jalali_format(now(), 'Y')) }}</span>
                    <span>•</span>
                    <span>ساخته شده با ❤</span>
                </div>
            </div>
        </div>

        {{-- سمت چپ: فرم --}}
        <div style="
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px;
        ">
            <div style="width: 100%; max-width: 380px;">
                <div style="margin-bottom: 32px;">
                    <h1 style="margin: 0 0 8px 0; font-size: 24px; font-weight: 800; color: #111827;">
                        ورود به حساب
                    </h1>
                    <p style="margin: 0; font-size: 13px; color: #6b7280;">
                        {{ $heading ?: 'اطلاعات ورود خود را وارد کنید' }}
                    </p>
                </div>

                <main id="fi-main-content" tabindex="-1">
                    {{ $slot }}
                </main>
            </div>
        </div>

        {{ FilamentView::renderHook(PanelsRenderHook::SIMPLE_LAYOUT_END, scopes: $renderHookScopes) }}
    </div>

    <style>
        @media (max-width: 768px) {
            .fi-simple-layout-wrapper {
                grid-template-columns: 1fr !important;
            }
        }
        [dir="rtl"] .fi-simple-main .fi-fo-field-wrp label { text-align: right !important; }
        [dir="rtl"] .fi-simple-main input,
        [dir="rtl"] .fi-simple-main textarea { text-align: right; }
        [dir="rtl"] .fi-simple-main .fi-btn { width: 100%; }
        .fi-simple-main input,
        .fi-simple-main textarea {
            border-radius: 10px !important;
            border: 1px solid #e5e7eb !important;
            padding: 12px 14px !important;
            transition: all 0.15s !important;
        }
        .fi-simple-main input:focus,
        .fi-simple-main textarea:focus {
            border-color: {{ $primaryColor }} !important;
            box-shadow: 0 0 0 3px {{ $primaryColor }}20 !important;
        }
    </style>
</x-filament-panels::layout.base>