@props(['after' => null, 'heading' => null, 'subheading' => null])

@php
    use Filament\Support\Facades\FilamentView;
    use Filament\View\PanelsRenderHook;
    use Sghazanfari\FilamentPersian\Settings\FontManager;

    $livewire ??= null;
    $renderHookScopes = $livewire?->getRenderHookScopes();
    $primaryColor = FontManager::currentColor()['value'] ?? '#0ea5e9';
    $fontFamily = FontManager::currentFont()['family'] ?? 'Vazirmatn';
    $brandName = filament()->getBrandName() ?? config('app.name', 'پنل مدیریت');
    $texts = FontManager::loginTexts();
@endphp

<x-filament-panels::layout.base :livewire="$livewire">
    <div dir="rtl" style="
        min-height: 100vh;
        background: #050505;
        font-family: '{{ $fontFamily }}', system-ui, sans-serif;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 24px;
        position: relative;
        overflow: hidden;
    ">
        <div style="position: absolute; inset: 0; background-image: linear-gradient(rgba({{ $primaryColor }}0a 1px, transparent 1px), linear-gradient(90deg, rgba({{ $primaryColor }}0a 1px, transparent 1px)); background-size: 60px 60px;"></div>

        {{ FilamentView::renderHook(PanelsRenderHook::SIMPLE_LAYOUT_START, scopes: $renderHookScopes) }}

        <div style="position: relative; z-index: 1; width: 100%; max-width: 420px;">
            <div style="text-align: center; margin-bottom: 32px;">
                <div style="
                    display: inline-flex; align-items: center; justify-content: center;
                    width: 72px; height: 72px;
                    border: 2px solid {{ $primaryColor }};
                    border-radius: 16px;
                    background: rgba({{ $primaryColor }}10);
                    margin-bottom: 16px;
                    font-size: 32px;
                    box-shadow: 0 0 30px {{ $primaryColor }}80, inset 0 0 20px {{ $primaryColor }}40;
                    animation: neonPulse 2s ease-in-out infinite alternate;
                ">🇮🇷</div>
                <h1 style="
                    margin: 0 0 8px 0; font-size: 26px; font-weight: 800;
                    color: {{ $primaryColor }};
                    text-shadow: 0 0 10px {{ $primaryColor }}cc, 0 0 20px {{ $primaryColor }}80, 0 0 40px {{ $primaryColor }}40;
                    letter-spacing: 2px;
                ">{{ $brandName }}</h1>
                <p style="margin: 0; font-size: 13px; color: #6b7280;">{{ $texts['subheading'] }}</p>
            </div>

            <div style="
                background: #0a0a0a;
                border: 1px solid {{ $primaryColor }}40;
                border-radius: 16px;
                padding: 32px 28px;
                box-shadow: 0 0 40px {{ $primaryColor }}20, inset 0 1px 0 {{ $primaryColor }}20;
            ">
                <h2 style="margin: 0 0 24px 0; font-size: 16px; font-weight: 700; color: #d1d5db; text-align: center; letter-spacing: 1px;">{{ $texts['heading'] }}</h2>
                <main id="fi-main-content" tabindex="-1">{{ $slot }}</main>
            </div>

            <div style="margin-top: 24px; text-align: center; font-size: 11px; color: #4b5563;">
                {{ $texts['copyright_prefix'] }} {{ fa_digits(jalali_format(now(), 'Y')) }} — {{ $texts['footer'] }}
            </div>
        </div>

        {{ FilamentView::renderHook(PanelsRenderHook::SIMPLE_LAYOUT_END, scopes: $renderHookScopes) }}
    </div>

    <style>
        @keyframes neonPulse {
            from { box-shadow: 0 0 30px {{ $primaryColor }}80, inset 0 0 20px {{ $primaryColor }}40; }
            to { box-shadow: 0 0 50px {{ $primaryColor }}cc, inset 0 0 30px {{ $primaryColor }}60; }
        }
        .fi-simple-main .fi-fo-field-wrp label { color: #d1d5db !important; text-align: right !important; letter-spacing: 1px; }
        .fi-simple-main input,
        .fi-simple-main textarea {
            background: #050505 !important;
            border: 1px solid {{ $primaryColor }}40 !important;
            color: {{ $primaryColor }} !important;
            border-radius: 10px !important;
            padding: 12px 14px !important;
            font-family: monospace, '{{ $fontFamily }}' !important;
        }
        .fi-simple-main input::placeholder { color: #374151 !important; }
        .fi-simple-main input:focus,
        .fi-simple-main textarea:focus {
            border-color: {{ $primaryColor }} !important;
            box-shadow: 0 0 20px {{ $primaryColor }}60, inset 0 0 10px {{ $primaryColor }}20 !important;
        }
        [dir="rtl"] .fi-simple-main input,
        [dir="rtl"] .fi-simple-main textarea { text-align: right; }
        [dir="rtl"] .fi-simple-main .fi-btn { width: 100%; }
        .fi-simple-main .fi-btn {
            background: transparent !important;
            color: {{ $primaryColor }} !important;
            border: 2px solid {{ $primaryColor }} !important;
            font-weight: 700 !important;
            border-radius: 10px !important;
            padding: 12px !important;
            letter-spacing: 2px;
            text-shadow: 0 0 10px {{ $primaryColor }}cc;
            box-shadow: 0 0 20px {{ $primaryColor }}40, inset 0 0 10px {{ $primaryColor }}20;
            transition: all 0.2s;
        }
        .fi-simple-main .fi-btn:hover {
            background: {{ $primaryColor }}20 !important;
            box-shadow: 0 0 30px {{ $primaryColor }}80, inset 0 0 20px {{ $primaryColor }}40;
        }
        .fi-simple-main .fi-checkbox-input { background: #050505 !important; border-color: {{ $primaryColor }}40 !important; }
        .fi-simple-main a { color: {{ $primaryColor }} !important; text-shadow: 0 0 8px {{ $primaryColor }}80; }
    </style>
</x-filament-panels::layout.base>