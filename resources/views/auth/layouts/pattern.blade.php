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

    // الگوی هندسی SVG
    $patternSvg = "data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='" . str_replace('#', '%23', $primaryColor) . "' fill-opacity='0.08'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E";
@endphp

<x-filament-panels::layout.base :livewire="$livewire">
    <div dir="rtl" style="
        min-height: 100vh;
        background: #fafbfc;
        background-image: url('{{ $patternSvg }}');
        font-family: '{{ $fontFamily }}', system-ui, sans-serif;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 24px;
        position: relative;
    ">
        <div style="position: absolute; inset: 0; background: linear-gradient(180deg, transparent 0%, rgba(250,251,252,0.6) 50%, transparent 100%);"></div>

        {{ FilamentView::renderHook(PanelsRenderHook::SIMPLE_LAYOUT_START, scopes: $renderHookScopes) }}

        <div style="position: relative; z-index: 1; width: 100%; max-width: 440px;">
            <div style="background: white; border-radius: 24px; padding: 40px 32px; box-shadow: 0 20px 60px rgba(15,23,42,0.1), 0 0 0 1px rgba(15,23,42,0.05);">
                <div style="text-align: center; margin-bottom: 28px;">
                    <div style="
                        display: inline-flex; align-items: center; justify-content: center;
                        width: 64px; height: 64px;
                        border-radius: 16px;
                        background: linear-gradient(135deg, {{ $primaryColor }} 0%, {{ $primaryColor }}aa 100%);
                        margin-bottom: 16px;
                        font-size: 28px;
                        box-shadow: 0 8px 24px {{ $primaryColor }}40;
                    ">🇮🇷</div>
                    <h1 style="margin: 0 0 6px 0; font-size: 22px; font-weight: 800; color: #111827;">{{ $brandName }}</h1>
                    <p style="margin: 0; font-size: 13px; color: #6b7280;">{{ $texts['subheading'] }}</p>
                </div>

                <main id="fi-main-content" tabindex="-1">{{ $slot }}</main>

                <div style="margin-top: 24px; padding-top: 20px; border-top: 1px solid #f3f4f6; text-align: center; font-size: 11px; color: #9ca3af;">
                    {{ $texts['copyright_prefix'] }} {{ fa_digits(jalali_format(now(), 'Y')) }} — {{ $texts['footer'] }}
                </div>
            </div>
        </div>

        {{ FilamentView::renderHook(PanelsRenderHook::SIMPLE_LAYOUT_END, scopes: $renderHookScopes) }}
    </div>

    <style>
        [dir="rtl"] .fi-simple-main .fi-fo-field-wrp label { text-align: right !important; color: #374151 !important; font-weight: 600 !important; }
        [dir="rtl"] .fi-simple-main input,
        [dir="rtl"] .fi-simple-main textarea { text-align: right; }
        [dir="rtl"] .fi-simple-main .fi-btn { width: 100%; }
        .fi-simple-main input,
        .fi-simple-main textarea { border-radius: 10px !important; border: 1px solid #e5e7eb !important; padding: 12px 14px !important; }
        .fi-simple-main input:focus,
        .fi-simple-main textarea:focus { border-color: {{ $primaryColor }} !important; box-shadow: 0 0 0 3px {{ $primaryColor }}20 !important; }
        .fi-simple-main .fi-btn { background: {{ $primaryColor }} !important; border-radius: 10px !important; padding: 12px !important; font-weight: 700 !important; }
    </style>
</x-filament-panels::layout.base>