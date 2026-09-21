<x-filament-panels::page>
    <div style="display: flex; flex-direction: column; gap: 20px;">

        {{-- ============ هدر ============ --}}
        <div style="
            background: linear-gradient(135deg, {{ $this->currentColor['value'] ?? '#0ea5e9' }} 0%, #8b5cf6 50%, #ec4899 100%);
            border-radius: 16px;
            padding: 40px;
            color: white;
            position: relative;
            overflow: hidden;
        ">
            <div style="position: relative; z-index: 1;">
                <div style="display: flex; align-items: center; gap: 20px; margin-bottom: 16px; flex-wrap: wrap;">
                    <div style="font-size: 56px; line-height: 1;">🇮🇷</div>
                    <div>
                        <h1 style="margin: 0; font-size: 32px; font-weight: 800; color: white; letter-spacing: -0.5px;">
                            Filament Persian
                        </h1>
                        <div style="font-size: 14px; opacity: 0.9; margin-top: 6px;">
                            فارسی‌سازی کامل و حرفه‌ای Filament برای زبان فارسی
                        </div>
                    </div>
                </div>

                <div style="display: flex; gap: 10px; flex-wrap: wrap; margin-top: 24px; font-size: 12px;">
                    <span style="background: rgba(255,255,255,0.2); padding: 6px 14px; border-radius: 999px;">
                        نسخه <strong>{{ $this->version }}</strong>
                    </span>
                    <span style="background: rgba(255,255,255,0.2); padding: 6px 14px; border-radius: 999px;">
                        فونت <strong>{{ $this->currentFont['name'] ?? 'Vazirmatn' }}</strong>
                    </span>
                    <span style="background: rgba(255,255,255,0.2); padding: 6px 14px; border-radius: 999px;">
                        رنگ <strong>{{ $this->currentColor['name'] ?? 'آبی' }}</strong>
                    </span>
                </div>
            </div>
            <div style="position: absolute; top: -80px; left: -80px; font-size: 320px; opacity: 0.06; transform: rotate(-15deg); line-height: 1;">📅</div>
        </div>

        {{-- ============ آمار ============ --}}
        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(160px, 1fr)); gap: 12px;">
            @foreach($this->stats as $stat)
                <div style="
                    background: white;
                    border-radius: 12px;
                    border: 1px solid #e5e7eb;
                    padding: 20px 16px;
                    text-align: center;
                    transition: all 0.15s;
                "
                onmouseover="this.style.transform='translateY(-3px)'; this.style.boxShadow='0 12px 24px rgba(0,0,0,0.08)';"
                onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none';"
                >
                    <div style="font-size: 28px; margin-bottom: 8px;">{{ $stat['icon'] }}</div>
                    <div style="font-size: 24px; font-weight: 800; color: {{ $this->currentColor['value'] ?? '#0ea5e9' }}; margin-bottom: 4px;">
                        {{ $stat['value'] }}
                    </div>
                    <div style="font-size: 11px; color: #6b7280; font-weight: 600;">
                        {{ $stat['label'] }}
                    </div>
                </div>
            @endforeach
        </div>

        {{-- ============ دسته‌بندی قابلیت‌ها ============ --}}
        <div style="background: white; border-radius: 14px; border: 1px solid #e5e7eb; overflow: hidden;">
            <div style="padding: 20px; border-bottom: 1px solid #f3f4f6;">
                <h2 style="margin: 0; font-size: 18px; font-weight: 800; color: #111827;">
                    🎯 قابلیت‌ها به تفکیک دسته
                </h2>
                <p style="margin: 6px 0 0 0; font-size: 13px; color: #6b7280;">
                    هر آنچه این پکیج برای فارسی‌سازی Filament فراهم می‌کند
                </p>
            </div>

            <div style="padding: 20px; display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 16px;">
                @foreach($this->categories as $category)
                    <div style="
                        border: 1px solid #e5e7eb;
                        border-radius: 12px;
                        overflow: hidden;
                        background: white;
                        transition: all 0.15s;
                    "
                    onmouseover="this.style.borderColor='{{ $category['color'] }}'; this.style.boxShadow='0 8px 20px {{ $category['color'] }}15';"
                    onmouseout="this.style.borderColor='#e5e7eb'; this.style.boxShadow='none';"
                    >
                        <div style="padding: 14px 16px; background: {{ $category['color'] }}10; border-bottom: 2px solid {{ $category['color'] }}30;">
                            <div style="display: flex; align-items: center; gap: 10px;">
                                <div style="font-size: 24px;">{{ $category['icon'] }}</div>
                                <div>
                                    <div style="font-size: 14px; font-weight: 800; color: #111827;">
                                        {{ $category['title'] }}
                                    </div>
                                    <div style="font-size: 10px; color: {{ $category['color'] }}; font-weight: 700;">
                                        {{ count($category['items']) }} مورد
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div style="padding: 12px 16px 16px 16px;">
                            <ul style="margin: 0; padding: 0; list-style: none;">
                                @foreach($category['items'] as $item)
                                    <li style="
                                        display: flex;
                                        align-items: flex-start;
                                        gap: 8px;
                                        padding: 5px 0;
                                        font-size: 12px;
                                        color: #4b5563;
                                        line-height: 1.6;
                                    ">
                                        <span style="color: {{ $category['color'] }}; flex-shrink: 0; margin-top: 2px;">✓</span>
                                        <span>{{ $item }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- ============ ویژگی‌های کلیدی (کارت‌های کوچک) ============ --}}
        <div style="background: white; border-radius: 14px; border: 1px solid #e5e7eb; overflow: hidden;">
            <div style="padding: 20px; border-bottom: 1px solid #f3f4f6;">
                <h2 style="margin: 0; font-size: 18px; font-weight: 800; color: #111827;">
                    ✨ ویژگی‌های کلیدی
                </h2>
                <p style="margin: 6px 0 0 0; font-size: 13px; color: #6b7280;">
                    {{ count($this->features) }} ویژگی برجسته در یک نگاه
                </p>
            </div>

            <div style="padding: 20px; display: grid; grid-template-columns: repeat(auto-fill, minmax(260px, 1fr)); gap: 12px;">
                @foreach($this->features as $feature)
                    <div style="
                        display: flex;
                        gap: 12px;
                        padding: 14px;
                        background: #f9fafb;
                        border-radius: 10px;
                        border: 1px solid #f3f4f6;
                        transition: all 0.15s;
                    "
                    onmouseover="this.style.borderColor='{{ $this->currentColor['value'] ?? '#0ea5e9' }}'; this.style.background='#f0f9ff';"
                    onmouseout="this.style.borderColor='#f3f4f6'; this.style.background='#f9fafb';"
                    >
                        <div style="font-size: 24px; flex-shrink: 0;">{{ $feature['icon'] }}</div>
                        <div>
                            <div style="font-size: 13px; font-weight: 700; color: #111827; margin-bottom: 3px;">
                                {{ $feature['title'] }}
                            </div>
                            <div style="font-size: 11px; color: #6b7280; line-height: 1.6;">
                                {{ $feature['desc'] }}
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- ============ سازگاری ============ --}}
        <div style="background: white; border-radius: 14px; border: 1px solid #e5e7eb; overflow: hidden;">
            <div style="padding: 20px; border-bottom: 1px solid #f3f4f6;">
                <h2 style="margin: 0; font-size: 18px; font-weight: 800; color: #111827;">
                    🔧 سازگاری
                </h2>
            </div>
            <div style="padding: 20px;">
                <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(220px, 1fr)); gap: 12px;">
                    @foreach($this->packages as $package)
                        <div style="
                            display: flex;
                            align-items: center;
                            justify-content: space-between;
                            padding: 12px 14px;
                            background: #f9fafb;
                            border-radius: 10px;
                            border: 1px solid #f3f4f6;
                        ">
                            <div>
                                <div style="font-size: 13px; font-weight: 700; color: #111827;">
                                    {{ $package['name'] }}
                                </div>
                                <div style="font-size: 11px; color: #6b7280; font-family: monospace;">
                                    {{ $package['version'] }}
                                </div>
                            </div>
                            @if($package['required'])
                                <span style="font-size: 10px; font-weight: 700; color: #059669; background: #ecfdf5; padding: 3px 10px; border-radius: 999px;">
                                    الزامی
                                </span>
                            @else
                                <span style="font-size: 10px; font-weight: 700; color: #6b7280; background: #f3f4f6; padding: 3px 10px; border-radius: 999px;">
                                    اختیاری
                                </span>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- ============ لینک‌های مفید ============ --}}
        <div style="background: white; border-radius: 14px; border: 1px solid #e5e7eb; overflow: hidden;">
            <div style="padding: 20px; border-bottom: 1px solid #f3f4f6;">
                <h2 style="margin: 0; font-size: 18px; font-weight: 800; color: #111827;">
                    🔗 لینک‌های مفید
                </h2>
            </div>
            <div style="padding: 20px;">
                <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(220px, 1fr)); gap: 12px;">
                    @foreach([
                        ['icon' => '📖', 'title' => 'مستندات', 'desc' => 'راهنمای کامل', 'url' => url('/admin/documentation'), 'internal' => true],
                        ['icon' => '🧩', 'title' => 'کامپوننت‌ها', 'desc' => 'فهرست آماده', 'url' => url('/admin/components-showcase'), 'internal' => true],
                        ['icon' => '⚙️', 'title' => 'تنظیمات ظاهری', 'desc' => 'فونت و رنگ', 'url' => url('/admin/font-settings'), 'internal' => true],
                        ['icon' => '🐙', 'title' => 'GitHub', 'desc' => 'سورس کد', 'url' => 'https://github.com/sghazanfari/filament-persian', 'internal' => false],
                        ['icon' => '🐛', 'title' => 'گزارش مشکل', 'desc' => 'Issue جدید', 'url' => 'https://github.com/sghazanfari/filament-persian/issues', 'internal' => false],
                        ['icon' => '📝', 'title' => 'تغییرات', 'desc' => 'Changelog', 'url' => 'https://github.com/sghazanfari/filament-persian/releases', 'internal' => false],
                    ] as $link)
                        <a
                            href="{{ $link['url'] }}"
                            target="{{ $link['internal'] ? '_self' : '_blank' }}"
                            style="
                                display: flex;
                                gap: 12px;
                                padding: 14px;
                                background: #f9fafb;
                                border-radius: 10px;
                                border: 1px solid #f3f4f6;
                                text-decoration: none;
                                transition: all 0.15s;
                            "
                            onmouseover="this.style.borderColor='{{ $this->currentColor['value'] ?? '#0ea5e9' }}'; this.style.background='#f0f9ff'; this.style.transform='translateY(-2px)';"
                            onmouseout="this.style.borderColor='#f3f4f6'; this.style.background='#f9fafb'; this.style.transform='translateY(0)';"
                        >
                            <div style="font-size: 24px;">{{ $link['icon'] }}</div>
                            <div>
                                <div style="font-size: 13px; font-weight: 700; color: #111827;">
                                    {{ $link['title'] }}
                                </div>
                                <div style="font-size: 11px; color: #6b7280;">
                                    {{ $link['desc'] }}
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- ============ دستورات ============ --}}
        <div style="background: white; border-radius: 14px; border: 1px solid #e5e7eb; overflow: hidden;">
            <div style="padding: 20px; border-bottom: 1px solid #f3f4f6;">
                <h2 style="margin: 0; font-size: 18px; font-weight: 800; color: #111827;">
                    ⌨️ دستورات Artisan
                </h2>
                <p style="margin: 6px 0 0 0; font-size: 13px; color: #6b7280;">
                    دستورات مفید برای مدیریت پکیج
                </p>
            </div>
            <div style="padding: 20px;">
                <div style="display: flex; flex-direction: column; gap: 10px;">
                    @foreach([
                        ['command' => 'php artisan filament-persian:install', 'desc' => 'نصب و راه‌اندازی کامل'],
                        ['command' => 'php artisan filament-persian:doctor', 'desc' => 'بررسی سلامت نصب'],
                        ['command' => 'php artisan filament-persian:seed-cities', 'desc' => 'درج شهرهای ایران'],
                        ['command' => 'php artisan filament-persian:seed-cities --fresh', 'desc' => 'درج مجدد از صفر'],
                    ] as $cmd)
                        <div style="
                            display: flex;
                            align-items: center;
                            gap: 12px;
                            padding: 12px 14px;
                            background: #1e293b;
                            border-radius: 10px;
                            overflow: hidden;
                        ">
                            <button
                                type="button"
                                onclick="copyAboutCmd(this, {{ json_encode($cmd['command']) }})"
                                style="
                                    background: {{ $this->currentColor['value'] ?? '#0ea5e9' }};
                                    color: white;
                                    border: none;
                                    width: 32px;
                                    height: 32px;
                                    border-radius: 8px;
                                    cursor: pointer;
                                    font-size: 13px;
                                    flex-shrink: 0;
                                    display: flex;
                                    align-items: center;
                                    justify-content: center;
                                "
                                title="کپی"
                            >
                                📋
                            </button>
                            <div style="flex: 1; min-width: 0;">
                                <code style="
                                    display: block;
                                    font-family: 'Fira Code', monospace;
                                    font-size: 12.5px;
                                    color: #a5f3fc;
                                    direction: ltr;
                                    text-align: left;
                                    white-space: nowrap;
                                    overflow: hidden;
                                    text-overflow: ellipsis;
                                ">{{ $cmd['command'] }}</code>
                                <div style="font-size: 10.5px; color: #94a3b8; margin-top: 3px;">
                                    {{ $cmd['desc'] }}
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- ============ سازنده ============ --}}
        <div style="background: linear-gradient(135deg, #f8fafc 0%, #e0e7ff 100%); border-radius: 14px; border: 1px solid #e5e7eb; padding: 32px; text-align: center;">
            <div style="font-size: 32px; margin-bottom: 12px;">❤️</div>
            <div style="font-size: 16px; font-weight: 700; color: #111827; margin-bottom: 8px;">
                ساخته شده با عشق برای جامعه فارسی‌زبان
            </div>
            <div style="font-size: 13px; color: #6b7280; line-height: 1.9;">
                اگر این پکیج برایت مفید بود، در GitHub یک ⭐ بده<br>
                <span style="font-size: 11px; color: #9ca3af; display: block; margin-top: 12px;">
                    © ۱۴۰۵ — منتشر شده تحت لایسنس MIT
                </span>
            </div>
            <div style="margin-top: 20px; display: flex; gap: 10px; justify-content: center; flex-wrap: wrap;">
                <a
                    href="https://github.com/sghazanfari/filament-persian"
                    target="_blank"
                    style="
                        padding: 10px 20px;
                        background: #111827;
                        color: white;
                        border-radius: 10px;
                        text-decoration: none;
                        font-size: 13px;
                        font-weight: 700;
                        display: flex;
                        align-items: center;
                        gap: 8px;
                    "
                >
                    <span>⭐</span>
                    <span>ستاره در GitHub</span>
                </a>
                <a
                    href="{{ url('/admin/documentation') }}"
                    style="
                        padding: 10px 20px;
                        background: white;
                        color: #374151;
                        border: 1px solid #d1d5db;
                        border-radius: 10px;
                        text-decoration: none;
                        font-size: 13px;
                        font-weight: 700;
                        display: flex;
                        align-items: center;
                        gap: 8px;
                    "
                >
                    <span>📖</span>
                    <span>شروع مستندات</span>
                </a>
            </div>
        </div>

    </div>

    <script>
        function copyAboutCmd(button, text) {
            navigator.clipboard.writeText(text).then(() => {
                const originalText = button.textContent;
                button.textContent = '✓';
                button.style.background = '#10b981';
                setTimeout(() => {
                    button.textContent = originalText;
                    button.style.background = '{{ $this->currentColor['value'] ?? '#0ea5e9' }}';
                }, 1500);
            });
        }
    </script>
</x-filament-panels::page>