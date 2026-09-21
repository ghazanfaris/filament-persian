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
            background: #ffffff;
            font-family: '{{ $fontFamily }}', system-ui, sans-serif;
        "
    >
        {{ FilamentView::renderHook(PanelsRenderHook::SIMPLE_LAYOUT_START, scopes: $renderHookScopes) }}

        <div style="width: 100%; max-width: 380px;">
            {{-- برند --}}
            <div style="text-align: center; margin-bottom: 40px;">
                @if($brandLogo)
                    <img src="{{ $brandLogo }}" alt="{{ $brandName }}" style="height: 48px; margin: 0 auto 16px;" />
                @else
                    <div style="
                        width: 48px;
                        height: 48px;
                        margin: 0 auto 20px;
                        border-radius: 12px;
                        background: {{ $primaryColor }};
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        color: white;
                        font-weight: 800;
                        font-size: 20px;
                    ">{{ mb_substr($brandName, 0, 1) }}</div>
                @endif
                <h1 style="margin: 0 0 6px 0; font-size: 24px; font-weight: 800; color: #111827;">{{ $brandName }}</h1>
                <p style="margin: 0; font-size: 13px; color: #6b7280;">{{ $heading ?: 'برای ادامه وارد شوید' }}</p>
            </div>

            <main id="fi-main-content" tabindex="-1">
                {{ $slot }}
            </main>

            <div style="margin-top: 32px; text-align: center; font-size: 11px; color: #9ca3af;">
                © {{ fa_digits(jalali_format(now(), 'Y')) }} {{ $brandName }}
            </div>
        </div>

        {{ FilamentView::renderHook(PanelsRenderHook::SIMPLE_LAYOUT_END, scopes: $renderHookScopes) }}
    </div>

    <style>
        [dir="rtl"] .fi-simple-main .fi-fo-field-wrp label { text-align: right !important; }
        [dir="rtl"] .fi-simple-main input,
        [dir="rtl"] .fi-simple-main textarea { text-align: right; }
        [dir="rtl"] .fi-simple-main .fi-btn { width: 100%; }
        .fi-simple-main input,
        .fi-simple-main textarea {
            border-radius: 10px !important;
            border: 1px solid #e5e7eb !important;
            padding: 12px 14px !important;
        }
        .fi-simple-main input:focus,
        .fi-simple-main textarea:focus {
            border-color: {{ $primaryColor }} !important;
            box-shadow: 0 0 0 3px {{ $primaryColor }}20 !important;
        }
    </style>
</x-filament-panels::layout.base>