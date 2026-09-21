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
    $brandLogo = filament()->getBrandLogo();
    $texts = FontManager::loginTexts();
@endphp

<x-filament-panels::layout.base :livewire="$livewire">
    <div dir="rtl" style="
        min-height: 100vh;
        background: #0a0a0a;
        font-family: '{{ $fontFamily }}', system-ui, sans-serif;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 24px;
        position: relative;
        overflow: hidden;
    ">
        {{-- هاله نورانی --}}
        <div style="position: absolute; top: -200px; right: -200px; width: 500px; height: 500px; border-radius: 50%; background: radial-gradient(circle, {{ $primaryColor }}25 0%, transparent 70%); filter: blur(80px);"></div>
        <div style="position: absolute; bottom: -200px; left: -200px; width: 500px; height: 500px; border-radius: 50%; background: radial-gradient(circle, {{ $primaryColor }}20 0%, transparent 70%); filter: blur(80px);"></div>

        {{ FilamentView::renderHook(PanelsRenderHook::SIMPLE_LAYOUT_START, scopes: $renderHookScopes) }}

        <div style="position: relative; z-index: 1; width: 100%; max-width: 420px;">
            <div style="text-align: center; margin-bottom: 32px;">
                @if($brandLogo)
                    <img src="{{ $brandLogo }}" alt="{{ $brandName }}" style="height: 56px; margin: 0 auto 16px; filter: brightness(0) invert(1);" />
                @else
                    <div style="
                        width: 68px; height: 68px; margin: 0 auto 16px;
                        border-radius: 18px;
                        background: linear-gradient(135deg, {{ $primaryColor }} 0%, {{ $primaryColor }}80 100%);
                        display: flex; align-items: center; justify-content: center;
                        font-size: 30px;
                        box-shadow: 0 0 40px {{ $primaryColor }}80;
                    ">🇮🇷</div>
                @endif
                <h1 style="margin: 0 0 6px 0; font-size: 22px; font-weight: 800; color: white;">{{ $brandName }}</h1>
                <p style="margin: 0; font-size: 13px; color: #6b7280;">{{ $texts['subheading'] }}</p>
            </div>

            <div style="background: #141414; border: 1px solid #1f1f1f; border-radius: 20px; padding: 32px 28px; box-shadow: 0 20px 60px rgba(0,0,0,0.5);">
                <h2 style="margin: 0 0 24px 0; font-size: 18px; font-weight: 700; color: white; text-align: center;">{{ $texts['heading'] }}</h2>
                <main id="fi-main-content" tabindex="-1">{{ $slot }}</main>
            </div>

            <div style="margin-top: 24px; text-align: center; font-size: 11px; color: #4b5563;">
                {{ $texts['copyright_prefix'] }} {{ fa_digits(jalali_format(now(), 'Y')) }} — {{ $texts['footer'] }}
            </div>
        </div>

        {{ FilamentView::renderHook(PanelsRenderHook::SIMPLE_LAYOUT_END, scopes: $renderHookScopes) }}
    </div>

    <style>
        .fi-simple-main .fi-fo-field-wrp label { color: #d1d5db !important; text-align: right !important; }
        .fi-simple-main input,
        .fi-simple-main textarea {
            background: #0a0a0a !important;
            border: 1px solid #2a2a2a !important;
            color: white !important;
            border-radius: 10px !important;
            padding: 12px 14px !important;
        }
        .fi-simple-main input::placeholder { color: #4b5563 !important; }
        .fi-simple-main input:focus,
        .fi-simple-main textarea:focus {
            border-color: {{ $primaryColor }} !important;
            box-shadow: 0 0 0 3px {{ $primaryColor }}30 !important;
        }
        [dir="rtl"] .fi-simple-main input,
        [dir="rtl"] .fi-simple-main textarea { text-align: right; }
        [dir="rtl"] .fi-simple-main .fi-btn { width: 100%; }
        .fi-simple-main .fi-btn {
            background: {{ $primaryColor }} !important;
            color: white !important;
            font-weight: 700 !important;
            border-radius: 10px !important;
            padding: 12px !important;
            border: none !important;
        }
        .fi-simple-main .fi-btn:hover { background: {{ $primaryColor }}dd !important; }
        .fi-simple-main .fi-checkbox-input { background: #0a0a0a !important; border-color: #2a2a2a !important; }
        .fi-simple-main a { color: {{ $primaryColor }} !important; }
        .fi-simple-main .fi-fo-field-wrp-error-message { color: #f87171 !important; }
    </style>
</x-filament-panels::layout.base>