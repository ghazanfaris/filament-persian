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
        background: linear-gradient(135deg, {{ $primaryColor }} 0%, #8b5cf6 50%, #ec4899 100%);
        font-family: '{{ $fontFamily }}', system-ui, sans-serif;
        display: flex;
        flex-direction: column;
        padding: 24px;
        position: relative;
        overflow: hidden;
    ">
        <div style="position: absolute; inset: 0; background-image: radial-gradient(circle at 1px 1px, rgba(255,255,255,0.15) 1px, transparent 0); background-size: 40px 40px;"></div>

        {{ FilamentView::renderHook(PanelsRenderHook::SIMPLE_LAYOUT_START, scopes: $renderHookScopes) }}

        {{-- هدر بالای صفحه --}}
        <header style="position: relative; z-index: 1; display: flex; align-items: center; justify-content: space-between;">
            <div style="display: flex; align-items: center; gap: 10px;">
                @if($brandLogo)
                    <img src="{{ $brandLogo }}" alt="{{ $brandName }}" style="height: 36px;" />
                @else
                    <div style="width: 36px; height: 36px; border-radius: 10px; background: rgba(255,255,255,0.2); backdrop-filter: blur(10px); display: flex; align-items: center; justify-content: center; color: white; font-weight: 800;">{{ mb_substr($brandName, 0, 1) }}</div>
                @endif
                <span style="color: white; font-size: 15px; font-weight: 700;">{{ $brandName }}</span>
            </div>
            <span style="color: rgba(255,255,255,0.7); font-size: 12px;">{{ fa_digits(jalali_format(now(), 'Y/m/d')) }}</span>
        </header>

        {{-- فرم وسط --}}
        <div style="flex: 1; display: flex; align-items: center; justify-content: center; padding: 40px 0; position: relative; z-index: 1;">
            <div style="width: 100%; max-width: 420px;">
                <div style="text-align: center; margin-bottom: 32px;">
                    <h1 style="margin: 0 0 8px 0; font-size: 32px; font-weight: 800; color: white; letter-spacing: -0.5px; text-shadow: 0 4px 20px rgba(0,0,0,0.15);">
                        {{ $texts['heading'] }}
                    </h1>
                    <p style="margin: 0; font-size: 14px; color: rgba(255,255,255,0.85);">
                        {{ $texts['subheading'] }}
                    </p>
                </div>

                <div style="background: rgba(255,255,255,0.98); backdrop-filter: blur(20px); border-radius: 20px; padding: 32px 28px; box-shadow: 0 25px 50px -12px rgba(0,0,0,0.25);">
                    <main id="fi-main-content" tabindex="-1">{{ $slot }}</main>
                </div>
            </div>
        </div>

        {{-- فوتر --}}
        <footer style="position: relative; z-index: 1; text-align: center; color: rgba(255,255,255,0.7); font-size: 11px;">
            {{ $texts['copyright_prefix'] }} {{ fa_digits(jalali_format(now(), 'Y')) }} — {{ $texts['footer'] }}
        </footer>

        {{ FilamentView::renderHook(PanelsRenderHook::SIMPLE_LAYOUT_END, scopes: $renderHookScopes) }}
    </div>

    <style>
        [dir="rtl"] .fi-simple-main .fi-fo-field-wrp label { text-align: right !important; }
        [dir="rtl"] .fi-simple-main input,
        [dir="rtl"] .fi-simple-main textarea { text-align: right; }
        [dir="rtl"] .fi-simple-main .fi-btn { width: 100%; }
        .fi-simple-main input,
        .fi-simple-main textarea { border-radius: 10px !important; border: 1px solid #e5e7eb !important; padding: 12px 14px !important; }
        .fi-simple-main input:focus,
        .fi-simple-main textarea:focus { border-color: {{ $primaryColor }} !important; box-shadow: 0 0 0 3px {{ $primaryColor }}20 !important; }
    </style>
</x-filament-panels::layout.base>