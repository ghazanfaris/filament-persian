@props([
    'after' => null,
    'heading' => null,
    'subheading' => null,
])

@php
    use Filament\Livewire\SimpleUserMenu;
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
                radial-gradient(circle at 20% 20%, {{ $primaryColor }}33 0%, transparent 50%),
                radial-gradient(circle at 80% 80%, {{ $primaryColor }}22 0%, transparent 50%),
                linear-gradient(135deg, #f8fafc 0%, #e0e7ff 100%);
            font-family: '{{ $fontFamily }}', system-ui, sans-serif;
            position: relative;
            overflow: hidden;
        "
    >
        {{-- حباب‌های تزئینی --}}
        <div style="position: absolute; top: -150px; right: -150px; width: 500px; height: 500px; border-radius: 50%; background: radial-gradient(circle, {{ $primaryColor }}30 0%, transparent 70%); filter: blur(60px); pointer-events: none;"></div>
        <div style="position: absolute; bottom: -150px; left: -150px; width: 500px; height: 500px; border-radius: 50%; background: radial-gradient(circle, {{ $primaryColor }}30 0%, transparent 70%); filter: blur(60px); pointer-events: none;"></div>

        {{ FilamentView::renderHook(PanelsRenderHook::SIMPLE_LAYOUT_START, scopes: $renderHookScopes) }}

        <div
            style="
                position: relative;
                z-index: 1;
                width: 100%;
                max-width: 440px;
                background: rgba(255, 255, 255, 0.95);
                backdrop-filter: blur(20px);
                border-radius: 24px;
                box-shadow:
                    0 20px 60px rgba(0, 0, 0, 0.08),
                    0 0 0 1px rgba(255, 255, 255, 0.5) inset;
                padding: 40px 32px;
                animation: authCardIn 0.4s ease-out;
            "
        >
            {{-- هدر: لوگو / برند --}}
            <div style="text-align: center; margin-bottom: 28px;">
                @if($brandLogo)
                    <div style="display: flex; justify-content: center; margin-bottom: 16px;">
                        <img
                            src="{{ $brandLogo }}"
                            alt="{{ $brandName }}"
                            style="height: 56px; max-width: 200px; object-fit: contain;"
                        />
                    </div>
                @else
                    <div style="
                        display: inline-flex;
                        align-items: center;
                        justify-content: center;
                        width: 64px;
                        height: 64px;
                        border-radius: 20px;
                        background: linear-gradient(135deg, {{ $primaryColor }} 0%, {{ $primaryColor }}cc 100%);
                        margin-bottom: 16px;
                        box-shadow: 0 8px 24px {{ $primaryColor }}40;
                        font-size: 30px;
                        line-height: 1;
                    ">🇮🇷</div>
                @endif

                <h1 style="
                    margin: 0;
                    font-size: 22px;
                    font-weight: 800;
                    color: #111827;
                    letter-spacing: -0.3px;
                ">
                    {{ $brandName }}
                </h1>

                @if($heading || true)
                    <p style="
                        margin: 8px 0 0 0;
                        font-size: 13px;
                        color: #6b7280;
                    ">
                        {{ $heading ?: 'برای ورود به پنل، اطلاعات خود را وارد کنید' }}
                    </p>
                @endif
            </div>

            {{-- فرم --}}
            <main id="fi-main-content" tabindex="-1">
                {{ $slot }}
            </main>

            {{-- فوتر --}}
            <div style="
                margin-top: 28px;
                padding-top: 20px;
                border-top: 1px solid #f3f4f6;
                display: flex;
                align-items: center;
                justify-content: space-between;
                font-size: 11px;
                color: #9ca3af;
            ">
                <span>
                    © {{ fa_digits(jalali_format(now(), 'Y')) }}
                </span>
                <span style="display: flex; align-items: center; gap: 4px;">
                    ساخته شده با
                    <span style="color: {{ $primaryColor }};">❤</span>
                </span>
            </div>
        </div>

        {{ FilamentView::renderHook(PanelsRenderHook::SIMPLE_LAYOUT_END, scopes: $renderHookScopes) }}
    </div>

    <style>
        @keyframes authCardIn {
            from {
                opacity: 0;
                transform: translateY(20px) scale(0.98);
            }
            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        /* بهبود فیلدهای فرم داخل کارت */
        [dir="rtl"] .fi-simple-main .fi-fo-field-wrp label {
            text-align: right !important;
        }

        [dir="rtl"] .fi-simple-main input,
        [dir="rtl"] .fi-simple-main textarea {
            text-align: right;
        }

        [dir="rtl"] .fi-simple-main .fi-btn {
            width: 100%;
        }
    </style>
</x-filament-panels::layout.base>