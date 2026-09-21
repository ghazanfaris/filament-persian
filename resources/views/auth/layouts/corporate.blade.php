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
        background: #f8fafc;
        font-family: '{{ $fontFamily }}', system-ui, sans-serif;
        display: flex;
        flex-direction: column;
    ">
        {{-- هدر شرکتی --}}
        <header style="
            background: #0f172a;
            padding: 16px 32px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 3px solid {{ $primaryColor }};
        ">
            <div style="display: flex; align-items: center; gap: 12px;">
                @if($brandLogo)
                    <img src="{{ $brandLogo }}" alt="{{ $brandName }}" style="height: 44px;" />
                @else
                    <div style="width: 44px; height: 44px; border-radius: 8px; background: {{ $primaryColor }}; display: flex; align-items: center; justify-content: center; color: white; font-weight: 800; font-size: 20px;">{{ mb_substr($brandName, 0, 1) }}</div>
                @endif
                <div>
                    <div style="color: white; font-size: 16px; font-weight: 700;">{{ $brandName }}</div>
                    <div style="color: #94a3b8; font-size: 11px;">{{ $texts['brand_line'] }}</div>
                </div>
            </div>
            <span style="color: #94a3b8; font-size: 11px;">{{ fa_digits(jalali_format(now(), 'l j F Y')) }}</span>
        </header>

        {{ FilamentView::renderHook(PanelsRenderHook::SIMPLE_LAYOUT_START, scopes: $renderHookScopes) }}

        {{-- محتوا --}}
        <div style="flex: 1; display: flex; align-items: center; justify-content: center; padding: 40px 24px;">
            <div style="width: 100%; max-width: 440px;">
                <div style="background: white; border-radius: 16px; padding: 40px 32px; box-shadow: 0 4px 20px rgba(15,23,42,0.08); border-top: 4px solid {{ $primaryColor }};">
                    <div style="text-align: center; margin-bottom: 28px;">
                        <h1 style="margin: 0 0 8px 0; font-size: 22px; font-weight: 800; color: #0f172a;">{{ $texts['heading'] }}</h1>
                        <p style="margin: 0; font-size: 13px; color: #64748b;">{{ $texts['subheading'] }}</p>
                    </div>
                    <main id="fi-main-content" tabindex="-1">{{ $slot }}</main>
                </div>
            </div>
        </div>

        {{-- فوتر --}}
        <footer style="background: #0f172a; padding: 14px 32px; text-align: center; color: #94a3b8; font-size: 11px;">
            {{ $texts['copyright_prefix'] }} {{ fa_digits(jalali_format(now(), 'Y')) }} — {{ $texts['footer'] }}
        </footer>

        {{ FilamentView::renderHook(PanelsRenderHook::SIMPLE_LAYOUT_END, scopes: $renderHookScopes) }}
    </div>

    <style>
        [dir="rtl"] .fi-simple-main .fi-fo-field-wrp label { text-align: right !important; color: #334155 !important; font-weight: 600 !important; }
        [dir="rtl"] .fi-simple-main input,
        [dir="rtl"] .fi-simple-main textarea { text-align: right; }
        [dir="rtl"] .fi-simple-main .fi-btn { width: 100%; }
        .fi-simple-main input,
        .fi-simple-main textarea { border-radius: 8px !important; border: 1px solid #cbd5e1 !important; padding: 12px 14px !important; }
        .fi-simple-main input:focus,
        .fi-simple-main textarea:focus { border-color: {{ $primaryColor }} !important; box-shadow: 0 0 0 3px {{ $primaryColor }}20 !important; }
        .fi-simple-main .fi-btn { background: {{ $primaryColor }} !important; border-radius: 8px !important; padding: 12px !important; font-weight: 700 !important; }
    </style>
</x-filament-panels::layout.base>