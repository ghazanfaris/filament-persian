<x-filament-panels::page>
    <div style="display: flex; flex-direction: column; gap: 24px;">

        {{-- ============ هدر ============ --}}
        <div style="
            background: linear-gradient(135deg, {{ $this->currentColor['value'] ?? '#0ea5e9' }} 0%, #8b5cf6 100%);
            border-radius: 16px;
            padding: 32px 40px;
            color: white;
            position: relative;
            overflow: hidden;
        ">
            <div style="position: relative; z-index: 1;">
                <h1 style="margin: 0 0 8px 0; font-size: 26px; font-weight: 800;">
                    🧩 کامپوننت‌های آماده
                </h1>
                <p style="margin: 0; opacity: 0.9; font-size: 14px;">
                    همه کامپوننت‌ها، اعتبارسنجی‌ها، قالب‌ها و ابزارهای پکیج — آماده کپی و استفاده
                </p>
                <div style="display: flex; gap: 12px; margin-top: 20px; font-size: 12px; flex-wrap: wrap;">
                    <span style="background: rgba(255,255,255,0.2); padding: 5px 12px; border-radius: 999px;">
                        فونت: <strong>{{ $this->currentFont['name'] ?? 'نامشخص' }}</strong>
                    </span>
                    <span style="background: rgba(255,255,255,0.2); padding: 5px 12px; border-radius: 999px;">
                        رنگ: <strong>{{ $this->currentColor['name'] ?? 'نامشخص' }}</strong>
                    </span>
                    <span style="background: rgba(255,255,255,0.2); padding: 5px 12px; border-radius: 999px;">
                        قالب ورود: <strong>{{ $this->loginTemplates[$this->currentLoginTemplate]['name'] ?? 'کلاسیک' }}</strong>
                    </span>
                </div>
            </div>
            <div style="position: absolute; top: -50px; left: -50px; font-size: 240px; opacity: 0.05; transform: rotate(-15deg);">🧩</div>
        </div>

        {{-- ============================================================ --}}
        {{-- ============ کامپوننت‌ها به تفکیک گروه ============ --}}
        {{-- ============================================================ --}}
        @foreach($this->components as $group)
            <div style="background: white; border-radius: 14px; border: 1px solid #e5e7eb; overflow: hidden;">
                <div style="padding: 16px 20px; background: #f9fafb; border-bottom: 1px solid #e5e7eb;">
                    <h2 style="margin: 0; font-size: 15px; font-weight: 700; color: #111827;">
                        {{ $group['group'] }}
                    </h2>
                </div>

                <div style="padding: 20px; display: grid; grid-template-columns: repeat(auto-fill, minmax(360px, 1fr)); gap: 16px;">
                    @foreach($group['items'] as $item)
                        <div style="
                            border: 1px solid #e5e7eb;
                            border-radius: 12px;
                            overflow: hidden;
                            background: #fafbfc;
                        ">
                            <div style="padding: 14px 16px 8px 16px;">
                                <div style="font-size: 14px; font-weight: 700; color: #111827; margin-bottom: 6px;">
                                    {{ $item['title'] }}
                                </div>
                                <div style="font-size: 12px; color: #6b7280; margin-bottom: 10px;">
                                    {{ $item['desc'] }}
                                </div>
                                <div style="
                                    display: inline-block;
                                    font-family: 'Fira Code', monospace;
                                    font-size: 11px;
                                    background: #f0f9ff;
                                    color: #0369a1;
                                    padding: 3px 8px;
                                    border-radius: 4px;
                                    direction: ltr;
                                ">
                                    {{ $item['name'] }}
                                </div>
                            </div>

                            <div style="padding: 0 16px 16px 16px;">
                                <div style="position: relative; background: #1e293b; border-radius: 10px; overflow: hidden;">
                                    <button
                                        type="button"
                                        onclick="copyCode(this, {{ json_encode($item['code']) }})"
                                        style="
                                            position: absolute;
                                            top: 8px;
                                            left: 8px;
                                            background: rgba(255,255,255,0.15);
                                            border: none;
                                            color: white;
                                            font-size: 11px;
                                            padding: 4px 10px;
                                            border-radius: 6px;
                                            cursor: pointer;
                                            font-family: inherit;
                                        "
                                    >
                                        کپی
                                    </button>
                                    <pre style="
                                        margin: 0;
                                        padding: 14px 16px;
                                        color: #e2e8f0;
                                        font-family: 'Fira Code', 'Consolas', monospace;
                                        font-size: 11.5px;
                                        line-height: 1.7;
                                        direction: ltr;
                                        text-align: left;
                                        overflow-x: auto;
                                        white-space: pre;
                                    ">{{ $item['code'] }}</pre>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach

        {{-- ============================================================ --}}
        {{-- ============ قالب‌های صفحه ورود ============ --}}
        {{-- ============================================================ --}}
        <div style="background: white; border-radius: 14px; border: 1px solid #e5e7eb; overflow: hidden;">
            <div style="padding: 16px 20px; background: linear-gradient(135deg, {{ $this->currentColor['value'] ?? '#0ea5e9' }}15 0%, {{ $this->currentColor['value'] ?? '#0ea5e9' }}05 100%); border-bottom: 1px solid #e5e7eb;">
                <div style="display: flex; align-items: center; justify-content: space-between; gap: 12px; flex-wrap: wrap;">
                    <div>
                        <h2 style="margin: 0; font-size: 15px; font-weight: 700; color: #111827;">
                            🔐 قالب‌های صفحه ورود
                        </h2>
                        <p style="margin: 4px 0 0 0; font-size: 12px; color: #6b7280;">
                            {{ count($this->loginTemplates) }} قالب آماده — از تنظیمات ظاهری قابل انتخاب
                        </p>
                    </div>
                    <a
                        href="{{ url('/admin/font-settings') }}"
                        style="
                            padding: 8px 16px;
                            border-radius: 8px;
                            background: {{ $this->currentColor['value'] ?? '#0ea5e9' }};
                            color: white;
                            font-size: 12px;
                            font-weight: 700;
                            text-decoration: none;
                            display: flex;
                            align-items: center;
                            gap: 6px;
                        "
                    >
                        <span>⚙️</span>
                        <span>تنظیمات</span>
                    </a>
                </div>
            </div>

            <div style="padding: 20px;">
                <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(180px, 1fr)); gap: 12px;">
                    @foreach($this->loginTemplates as $key => $template)
                        <div
                            wire:key="showcase-login-{{ $key }}"
                            style="
                                position: relative;
                                padding: 16px;
                                border-radius: 12px;
                                border: 2px solid {{ $this->currentLoginTemplate === $key ? ($this->currentColor['value'] ?? '#0ea5e9') : '#e5e7eb' }};
                                background: {{ $this->currentLoginTemplate === $key ? 'rgba(14,165,233,0.05)' : 'white' }};
                                text-align: center;
                                transition: all 0.15s;
                            "
                        >
                            @if($this->currentLoginTemplate === $key)
                                <div style="
                                    position: absolute;
                                    top: 8px;
                                    left: 8px;
                                    background: {{ $this->currentColor['value'] ?? '#0ea5e9' }};
                                    color: white;
                                    font-size: 10px;
                                    font-weight: 700;
                                    padding: 3px 8px;
                                    border-radius: 999px;
                                ">فعال</div>
                            @endif

                            <div style="font-size: 40px; line-height: 1; margin-bottom: 10px;">
                                {{ $template['icon'] }}
                            </div>
                            <div style="font-size: 14px; font-weight: 700; color: #111827; margin-bottom: 4px;">
                                {{ $template['name'] }}
                            </div>
                            <div style="font-size: 11px; color: #6b7280; line-height: 1.5;">
                                {{ $template['description'] }}
                            </div>
                            <div style="
                                margin-top: 10px;
                                padding-top: 10px;
                                border-top: 1px dashed #e5e7eb;
                                font-family: 'Fira Code', monospace;
                                font-size: 10px;
                                color: #9ca3af;
                                direction: ltr;
                            ">
                                {{ $key }}
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- متن‌های قابل ویرایش --}}
            <div style="padding: 0 20px 20px 20px;">
                <div style="padding: 16px; background: #f9fafb; border-radius: 12px; border: 1px solid #e5e7eb;">
                    <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 12px;">
                        <span style="font-size: 16px;">✏️</span>
                        <span style="font-size: 13px; font-weight: 700; color: #374151;">
                            متن‌های قابل ویرایش
                        </span>
                    </div>

                    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(220px, 1fr)); gap: 10px;">
                        @foreach($this->currentLoginTexts as $key => $value)
                            <div style="padding: 10px 12px; background: white; border-radius: 8px; border: 1px solid #e5e7eb;">
                                <div style="font-size: 10px; color: #9ca3af; font-family: monospace; direction: ltr; margin-bottom: 4px;">
                                    {{ $key }}
                                </div>
                                <div style="font-size: 12px; color: #111827; font-weight: 600;">
                                    {{ $value ?: '—' }}
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div style="margin-top: 12px; padding: 10px 12px; background: #fef3c7; border-radius: 8px; font-size: 11px; color: #78350f; line-height: 1.7;">
                        <strong>نکته:</strong> این متن‌ها از <strong>تنظیمات ظاهری</strong> → تب <strong>صفحه ورود</strong> قابل ویرایش هستند.
                    </div>
                </div>
            </div>

            {{-- کد نمونه --}}
            <div style="padding: 0 20px 20px 20px;">
                <div style="background: #1e293b; border-radius: 12px; overflow: hidden; position: relative;">
                    <button
                        type="button"
                        onclick="copyCode(this, '// در AdminPanelProvider:\n->login(\\Sghazanfari\\FilamentPersian\\Auth\\Login::class)\n\n// انتخاب قالب از پنل:\n// تنظیمات ظاهری → صفحه ورود → انتخاب قالب')"
                        style="
                            position: absolute;
                            top: 8px;
                            left: 8px;
                            background: rgba(255,255,255,0.15);
                            border: none;
                            color: white;
                            font-size: 11px;
                            padding: 4px 10px;
                            border-radius: 6px;
                            cursor: pointer;
                            font-family: inherit;
                        "
                    >
                        کپی
                    </button>
                    <pre style="
                        margin: 0;
                        padding: 14px 16px;
                        color: #e2e8f0;
                        font-family: 'Fira Code', 'Consolas', monospace;
                        font-size: 11.5px;
                        line-height: 1.7;
                        direction: ltr;
                        text-align: left;
                        overflow-x: auto;
                        white-space: pre;
                    ">// در AdminPanelProvider:
-&gt;login(\Sghazanfari\FilamentPersian\Auth\Login::class)

// انتخاب قالب از پنل:
// تنظیمات ظاهری → صفحه ورود → انتخاب قالب</pre>
                </div>
            </div>
        </div>

        {{-- ============================================================ --}}
        {{-- ============ Helper Functions ============ --}}
        {{-- ============================================================ --}}
        <div style="background: white; border-radius: 14px; border: 1px solid #e5e7eb; overflow: hidden;">
            <div style="padding: 16px 20px; background: #f9fafb; border-bottom: 1px solid #e5e7eb;">
                <h2 style="margin: 0; font-size: 15px; font-weight: 700; color: #111827;">
                    🛠️ توابع کمکی (Helper Functions)
                </h2>
                <p style="margin: 6px 0 0 0; font-size: 12px; color: #6b7280;">
                    {{ count($this->helpers) }} تابع آماده — روی 📋 بزن تا کپی کنی
                </p>
            </div>

            <div style="padding: 20px;">
                @php
                    $grouped = collect($this->helpers)->groupBy('group');
                    $groupIcons = [
                        'تاریخ' => '📅',
                        'اعداد' => '🔢',
                        'اعتبارسنجی' => '✅',
                        'داده‌های ایران' => '🗺️',
                        'تعطیلات و ساعت کاری' => '⏰',
                    ];
                @endphp

                @foreach($grouped as $groupName => $items)
                    <div style="margin-bottom: 24px;">
                        <div style="
                            display: flex;
                            align-items: center;
                            gap: 8px;
                            margin-bottom: 10px;
                            padding-bottom: 8px;
                            border-bottom: 1px dashed #e5e7eb;
                        ">
                            <span style="font-size: 16px;">{{ $groupIcons[$groupName] ?? '•' }}</span>
                            <span style="font-size: 13px; font-weight: 700; color: #374151;">
                                {{ $groupName }}
                            </span>
                            <span style="
                                font-size: 10px;
                                background: #f3f4f6;
                                color: #6b7280;
                                padding: 2px 8px;
                                border-radius: 999px;
                                margin-inline-start: 4px;
                            ">{{ count($items) }}</span>
                        </div>

                        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(340px, 1fr)); gap: 8px;">
                            @foreach($items as $helper)
                                <div style="
                                    display: flex;
                                    align-items: center;
                                    gap: 10px;
                                    padding: 10px 12px;
                                    background: #fafbfc;
                                    border: 1px solid #e5e7eb;
                                    border-radius: 8px;
                                    transition: all 0.15s;
                                "
                                onmouseover="this.style.borderColor='#0ea5e9'; this.style.background='#f0f9ff';"
                                onmouseout="this.style.borderColor='#e5e7eb'; this.style.background='#fafbfc';"
                                >
                                    <button
                                        type="button"
                                        onclick="copyText(this, {{ json_encode($helper['code']) }})"
                                        style="
                                            background: #0ea5e9;
                                            color: white;
                                            border: none;
                                            width: 26px;
                                            height: 26px;
                                            border-radius: 6px;
                                            cursor: pointer;
                                            font-size: 11px;
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
                                            font-size: 11.5px;
                                            color: #be185d;
                                            direction: ltr;
                                            text-align: left;
                                            white-space: nowrap;
                                            overflow: hidden;
                                            text-overflow: ellipsis;
                                        ">{{ $helper['code'] }}</code>
                                        <div style="font-size: 10.5px; color: #6b7280; margin-top: 2px;">
                                            {{ $helper['desc'] }}
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- ============ یادآوری ============ --}}
        <div style="background: #f0f9ff; border-radius: 14px; border: 1px solid #bae6fd; padding: 20px;">
            <div style="display: flex; gap: 12px;">
                <div style="font-size: 24px;">💡</div>
                <div>
                    <div style="font-size: 14px; font-weight: 700; color: #0c4a6e; margin-bottom: 4px;">
                        نکته
                    </div>
                    <div style="font-size: 13px; color: #0c4a6e; line-height: 1.7;">
                        همه این کامپوننت‌ها در مستندات به‌طور کامل توضیح داده شده‌اند. برای جزئیات بیشتر،
                        از منوی <strong>مستندات</strong> استفاده کن.
                    </div>
                </div>
            </div>
        </div>

    </div>

    <script>
        function copyCode(button, code) {
            navigator.clipboard.writeText(code).then(() => {
                const originalText = button.textContent;
                button.textContent = 'کپی شد ✓';
                button.style.background = 'rgba(16, 185, 129, 0.4)';
                setTimeout(() => {
                    button.textContent = originalText;
                    button.style.background = 'rgba(255,255,255,0.15)';
                }, 1500);
            });
        }

        function copyText(button, text) {
            navigator.clipboard.writeText(text).then(() => {
                const originalText = button.textContent;
                button.textContent = '✓';
                button.style.background = '#10b981';
                setTimeout(() => {
                    button.textContent = originalText;
                    button.style.background = '#0ea5e9';
                }, 1500);
            });
        }
    </script>
</x-filament-panels::page>