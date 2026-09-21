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
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
            background:
                linear-gradient(135deg, {{ $primaryColor }} 0%, #8b5cf6 50%, #ec4899 100%);
            font-family: '{{ $fontFamily }}', system-ui, sans-serif;
            position: relative;
            overflow: hidden;
        "
    >
        {{-- حباب‌های تزئینی --}}
        <div style="position: absolute; top: 10%; left: 10%; width: 300px; height: 300px; border-radius: 50%; background: rgba(255,255,255,0.15); filter: blur(80px);"></div>
        <div style="position: absolute; bottom: 10%; right: 10%; width: 400px; height: 400px; border-radius: 50%; background: rgba(255,255,255,0.1); filter: blur(80px);"></div>
        <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); width: 500px; height: 500px; border-radius: 50%; background: rgba(255,255,255,0.05); filter: blur(60px);"></div>

        {{ FilamentView::renderHook(PanelsRenderHook::SIMPLE_LAYOUT_START, scopes: $renderHookScopes) }}

        <div
            style="
                position: relative;
                z-index: 1;
                width: 100%;
                max-width: 440px;
                background: rgba(255, 255, 255, 0.15);
                backdrop-filter: blur(24px) saturate(180%);
                -webkit-backdrop-filter: blur(24px) saturate(180%);
                border: 1px solid rgba(255, 255, 255, 0.3);
                border-radius: 24px;
                padding: 40px 32px;
                box-shadow:
                    0 25px 50px -12px rgba(0, 0, 0, 0.25),
                    0 0 0 1px rgba(255, 255, 255, 0.1) inset;
            "
        >
            {{-- برند --}}
            <div style="text-align: center; margin-bottom: 28px;">
                @if($brandLogo)
                    <img src="{{ $brandLogo }}" alt="{{ $brandName }}" style="height: 56px; margin: 0 auto 16px; filter: brightness(0) invert(1);" />
                @else
                    <div style="
                        display: inline-flex;
                        align-items: center;
                        justify-content: center;
                        width: 68px;
                        height: 68px;
                        border-radius: 20px;
                        background: rgba(255, 255, 255, 0.25);
                        border: 1px solid rgba(255, 255, 255, 0.4);
                        margin-bottom: 16px;
                        font-size: 32px;
                        backdrop-filter: blur(10px);
                    ">🇮🇷</div>
                @endif

                <h1 style="
                    margin: 0 0 8px 0;
                    font-size: 24px;
                    font-weight: 800;
                    color: white;
                    letter-spacing: -0.3px;
                    text-shadow: 0 2px 8px rgba(0,0,0,0.15);
                ">{{ $brandName }}</h1>

                <p style="margin: 0; font-size: 13px; color: rgba(255,255,255,0.85);">
                    {{ $heading ?: 'برای ورود به پنل، وارد شوید' }}
                </p>
            </div>

            <main id="fi-main-content" tabindex="-1">
                {{ $slot }}
            </main>

            <div style="
                margin-top: 24px;
                padding-top: 16px;
                border-top: 1px solid rgba(255, 255, 255, 0.15);
                text-align: center;
                font-size: 11px;
                color: rgba(255, 255, 255, 0.7);
            ">
                © {{ fa_digits(jalali_format(now(), 'Y')) }} {{ $brandName }}
            </div>
        </div>

        {{ FilamentView::renderHook(PanelsRenderHook::SIMPLE_LAYOUT_END, scopes: $renderHookScopes) }}
    </div>

    <style>
        /* فرم داخل کارت شیشه‌ای */
        .fi-simple-main .fi-fo-field-wrp label {
            color: rgba(255, 255, 255, 0.9) !important;
            font-weight: 500 !important;
            text-align: right !important;
        }
        .fi-simple-main input,
        .fi-simple-main textarea {
            background: rgba(255, 255, 255, 0.15) !important;
            border: 1px solid rgba(255, 255, 255, 0.25) !important;
            color: white !important;
            border-radius: 12px !important;
            padding: 12px 14px !important;
            backdrop-filter: blur(10px);
        }
        .fi-simple-main input::placeholder,
        .fi-simple-main textarea::placeholder {
            color: rgba(255, 255, 255, 0.5) !important;
        }
        .fi-simple-main input:focus,
        .fi-simple-main textarea:focus {
            background: rgba(255, 255, 255, 0.22) !important;
            border-color: rgba(255, 255, 255, 0.6) !important;
            box-shadow: 0 0 0 3px rgba(255, 255, 255, 0.15) !important;
        }
        .fi-simple-main .fi-fo-field-wrp-error-message {
            color: #fecaca !important;
        }
        [dir="rtl"] .fi-simple-main input,
        [dir="rtl"] .fi-simple-main textarea { text-align: right; }
        [dir="rtl"] .fi-simple-main .fi-btn { width: 100%; }
        .fi-simple-main .fi-btn {
            background: rgba(255, 255, 255, 0.95) !important;
            color: #111827 !important;
            font-weight: 700 !important;
            border-radius: 12px !important;
            padding: 12px !important;
            border: none !important;
            box-shadow: 0 8px 24px rgba(0,0,0,0.15) !important;
        }
        .fi-simple-main .fi-btn:hover {
            background: white !important;
            transform: translateY(-1px);
            box-shadow: 0 12px 32px rgba(0,0,0,0.2) !important;
        }
        .fi-simple-main .fi-checkbox-input {
            background: rgba(255, 255, 255, 0.15) !important;
            border-color: rgba(255, 255, 255, 0.3) !important;
        }
        .fi-simple-main .fi-fo-field-wrp-hint,
        .fi-simple-main a {
            color: rgba(255, 255, 255, 0.85) !important;
        }
        .fi-simple-main a:hover {
            color: white !important;
        }
    </style>
</x-filament-panels::layout.base>