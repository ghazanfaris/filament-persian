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
        background: #f0f9ff;
        font-family: '{{ $fontFamily }}', system-ui, sans-serif;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 24px;
        position: relative;
        overflow: hidden;
    ">
        {{-- موج بالا --}}
        <svg viewBox="0 0 1440 320" style="position: absolute; top: 0; left: 0; width: 100%; height: 320px; opacity: 0.4;" preserveAspectRatio="none">
            <path fill="{{ $primaryColor }}" fill-opacity="0.3" d="M0,96L48,112C96,128,192,160,288,160C384,160,480,128,576,133.3C672,139,768,181,864,197.3C960,213,1056,203,1152,170.7C1248,139,1344,85,1392,58.7L1440,32L1440,0L1392,0C1344,0,1248,0,1152,0C1056,0,960,0,864,0C768,0,672,0,576,0C480,0,384,0,288,0C192,0,96,0,48,0L0,0Z"></path>
        </svg>

        {{-- موج پایین --}}
        <svg viewBox="0 0 1440 320" style="position: absolute; bottom: 0; left: 0; width: 100%; height: 320px; opacity: 0.4;" preserveAspectRatio="none">
            <path fill="{{ $primaryColor }}" fill-opacity="0.3" d="M0,224L48,213.3C96,203,192,181,288,181.3C384,181,480,203,576,224C672,245,768,267,864,250.7C960,235,1056,181,1152,165.3C1248,149,1344,171,1392,181.3L1440,192L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path>
        </svg>

        {{ FilamentView::renderHook(PanelsRenderHook::SIMPLE_LAYOUT_START, scopes: $renderHookScopes) }}

        <div style="position: relative; z-index: 1; width: 100%; max-width: 420px;">
            <div style="text-align: center; margin-bottom: 28px;">
                <div style="
                    display: inline-flex; align-items: center; justify-content: center;
                    width: 72px; height: 72px;
                    border-radius: 50%;
                    background: white;
                    box-shadow: 0 10px 40px {{ $primaryColor }}40;
                    margin-bottom: 16px;
                    font-size: 34px;
                ">🇮🇷</div>
                <h1 style="margin: 0 0 6px 0; font-size: 24px; font-weight: 800; color: #0c4a6e;">{{ $brandName }}</h1>
                <p style="margin: 0; font-size: 13px; color: #64748b;">{{ $texts['subheading'] }}</p>
            </div>

            <div style="background: white; border-radius: 20px; padding: 32px 28px; box-shadow: 0 20px 50px rgba(14,165,233,0.15);">
                <h2 style="margin: 0 0 22px 0; font-size: 17px; font-weight: 700; color: #0c4a6e; text-align: center;">{{ $texts['heading'] }}</h2>
                <main id="fi-main-content" tabindex="-1">{{ $slot }}</main>
            </div>

            <div style="margin-top: 24px; text-align: center; font-size: 11px; color: #64748b;">
                {{ $texts['copyright_prefix'] }} {{ fa_digits(jalali_format(now(), 'Y')) }} — {{ $texts['footer'] }}
            </div>
        </div>

        {{ FilamentView::renderHook(PanelsRenderHook::SIMPLE_LAYOUT_END, scopes: $renderHookScopes) }}
    </div>

    <style>
        [dir="rtl"] .fi-simple-main .fi-fo-field-wrp label { text-align: right !important; color: #0c4a6e !important; font-weight: 600 !important; }
        [dir="rtl"] .fi-simple-main input,
        [dir="rtl"] .fi-simple-main textarea { text-align: right; }
        [dir="rtl"] .fi-simple-main .fi-btn { width: 100%; }
        .fi-simple-main input,
        .fi-simple-main textarea { border-radius: 12px !important; border: 2px solid #e0f2fe !important; padding: 12px 14px !important; background: #f8fafc !important; }
        .fi-simple-main input:focus,
        .fi-simple-main textarea:focus { border-color: {{ $primaryColor }} !important; background: white !important; box-shadow: 0 0 0 3px {{ $primaryColor }}20 !important; }
        .fi-simple-main .fi-btn { background: {{ $primaryColor }} !important; border-radius: 12px !important; padding: 12px !important; font-weight: 700 !important; }
    </style>
</x-filament-panels::layout.base>