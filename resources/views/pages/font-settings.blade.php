<x-filament-panels::page>
    <div
        x-data="{
            fonts: @js($this->fonts),
            colors: @js($this->colors),
            loadedFonts: new Set(),
            get currentFontFamily() {
                return this.fonts[$wire.selectedFont]?.family ?? 'Vazirmatn';
            },
            get currentColorValue() {
                return this.colors[$wire.selectedColor]?.value ?? '#0ea5e9';
            },
            init() {
                Object.values(this.fonts).forEach(f => this.loadFontCss(f.url));
            },
            loadFontCss(url) {
                if (this.loadedFonts.has(url)) return;
                if (document.querySelector(`link[data-fp-font='${url}']`)) {
                    this.loadedFonts.add(url); return;
                }
                const l = document.createElement('link');
                l.rel = 'stylesheet';
                l.href = url;
                l.setAttribute('data-fp-font', url);
                document.head.appendChild(l);
                this.loadedFonts.add(url);
            }
        }"
        x-init="init()"
        style="display: flex; flex-direction: column; gap: 20px;"
    >

        {{-- ============ تب‌ها ============ --}}
        <div style="
            display: flex;
            gap: 4px;
            padding: 4px;
            background: #f3f4f6;
            border-radius: 12px;
            overflow-x: auto;
        ">
            @php
                $tabs = [
                    'appearance' => ['label' => 'ظاهر', 'icon' => '🎨'],
                    'dates' => ['label' => 'تاریخ', 'icon' => '📅'],
                    'numbers' => ['label' => 'اعداد', 'icon' => '🔢'],
                    'working-hours' => ['label' => 'ساعت کاری', 'icon' => '⏰'],
                    'login' => ['label' => 'صفحه ورود', 'icon' => '🔐'],
                    'presets' => ['label' => 'قالب‌های آماده', 'icon' => '⚡'],
                ];
            @endphp
            @foreach($tabs as $key => $meta)
                <button
                    type="button"
                    wire:click="setTab('{{ $key }}')"
                    style="
                        flex: 1;
                        min-width: 100px;
                        padding: 9px 16px;
                        border: none;
                        border-radius: 9px;
                        cursor: pointer;
                        font-family: inherit;
                        font-size: 13px;
                        font-weight: 600;
                        transition: all 0.15s;
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        gap: 6px;
                        background: {{ $tab === $key ? 'white' : 'transparent' }};
                        color: {{ $tab === $key ? '#111827' : '#6b7280' }};
                        box-shadow: {{ $tab === $key ? '0 1px 3px rgba(0,0,0,0.08)' : 'none' }};
                        white-space: nowrap;
                    "
                >
                    <span>{{ $meta['icon'] }}</span>
                    <span>{{ $meta['label'] }}</span>
                </button>
            @endforeach
        </div>

        {{-- ============================================================ --}}
        {{-- ============ تب: ظاهر ============ --}}
        {{-- ============================================================ --}}
        @if($tab === 'appearance')

            {{-- پیش‌نمایش زنده --}}
            <div style="background: white; border-radius: 14px; box-shadow: 0 1px 3px rgba(0,0,0,0.05); border: 1px solid #e5e7eb; overflow: hidden;">
                <div style="display: flex; align-items: center; justify-content: space-between; padding: 14px 20px; border-bottom: 1px solid #f3f4f6;">
                    <h2 style="margin: 0; font-size: 14px; font-weight: 700; color: #111827;">پیش‌نمایش زنده</h2>
                    <span style="background: #ecfdf5; color: #047857; font-size: 11px; font-weight: 600; padding: 3px 10px; border-radius: 999px;">آنی</span>
                </div>
                <div style="padding: 20px;">
                    <div style="border-radius: 10px; overflow: hidden;" :style="`border: 2px solid ${currentColorValue};`">
                        <div style="display: flex; align-items: center; justify-content: space-between; padding: 8px 14px;" :style="`background-color: ${currentColorValue};`">
                            <div style="display: flex; gap: 5px;">
                                <div style="width: 6px; height: 6px; border-radius: 50%; background: rgba(255,255,255,0.6);"></div>
                                <div style="width: 6px; height: 6px; border-radius: 50%; background: rgba(255,255,255,0.6);"></div>
                                <div style="width: 6px; height: 6px; border-radius: 50%; background: rgba(255,255,255,0.6);"></div>
                            </div>
                            <div style="color: white; font-size: 12px; font-weight: 700;">پنل مدیریت</div>
                            <div style="width: 28px;"></div>
                        </div>
                        <div style="padding: 20px; background: #f9fafb;" :style="`font-family: '${currentFontFamily}', sans-serif;`">
                            <p style="margin: 0 0 12px 0; font-size: 22px; font-weight: 800; line-height: 1.4;" :style="`color: ${currentColorValue};`">
                                {{ $previewText }}
                            </p>
                            <div style="display: flex; flex-wrap: wrap; gap: 16px; font-size: 12px; color: #4b5563; margin-bottom: 16px;">
                                <div><span style="color: #9ca3af;">فونت:</span> <strong x-text="currentFontFamily" style="color: #111827;"></strong></div>
                                <div><span style="color: #9ca3af;">رنگ:</span> <strong x-text="currentColorValue" style="color: #111827;"></strong></div>
                                <div><span style="color: #9ca3af;">اعداد:</span> <strong style="color: #111827;">{{ $persianNumbers ? '۱۲۳۴۵۶۷۸۹۰' : '1234567890' }}</strong></div>
                            </div>
                            <div style="display: flex; flex-wrap: wrap; gap: 8px;">
                                <button type="button" style="padding: 6px 14px; border-radius: 6px; border: none; color: white; font-size: 12px; font-weight: 600; cursor: pointer; font-family: inherit;" :style="`background-color: ${currentColorValue};`">دکمه اصلی</button>
                                <button type="button" style="padding: 6px 14px; border-radius: 6px; background: white; font-size: 12px; font-weight: 600; cursor: pointer; font-family: inherit;" :style="`border: 2px solid ${currentColorValue}; color: ${currentColorValue};`">دکمه ثانویه</button>
                            </div>
                        </div>
                    </div>
                    <div style="margin-top: 16px;">
                        <label style="display: block; font-size: 11px; font-weight: 600; color: #6b7280; margin-bottom: 6px;">متن پیش‌نمایش</label>
                        <input type="text" wire:model.live.debounce.500ms="previewText" style="width: 100%; padding: 9px 12px; border: 1px solid #d1d5db; border-radius: 8px; font-size: 13px; font-family: inherit; box-sizing: border-box; outline: none;" />
                    </div>
                </div>
            </div>

            {{-- فونت --}}
            <div style="background: white; border-radius: 14px; box-shadow: 0 1px 3px rgba(0,0,0,0.05); border: 1px solid #e5e7eb; overflow: hidden;">
                <div style="display: flex; align-items: center; justify-content: space-between; padding: 14px 20px; border-bottom: 1px solid #f3f4f6;">
                    <h2 style="margin: 0; font-size: 14px; font-weight: 700; color: #111827;">فونت پنل</h2>
                    <span style="font-size: 11px; color: #9ca3af;">{{ count($this->fonts) }} فونت موجود</span>
                </div>
                <div style="padding: 20px;">
                    @if(empty($this->fonts))
                        <div style="padding: 14px; background: #fef3c7; color: #92400e; border-radius: 8px; font-size: 13px;">
                            هیچ فونتی در پوشه <code>public/fonts</code> پیدا نشد.
                        </div>
                    @else
                        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(220px, 1fr)); gap: 12px;">
                            @foreach($this->fonts as $key => $font)
                                <label
                                    wire:key="font-{{ $key }}"
                                    style="position: relative; display: flex; flex-direction: column; gap: 6px; padding: 16px; border-radius: 10px; cursor: pointer; transition: all 0.15s; border: 2px solid {{ $selectedFont === $key ? '#0ea5e9' : '#e5e7eb' }}; background: {{ $selectedFont === $key ? 'rgba(14,165,233,0.05)' : 'white' }};"
                                >
                                    <input type="radio" name="font" value="{{ $key }}" wire:model.live="selectedFont" style="position: absolute; opacity: 0; pointer-events: none;" />
                                    @if($selectedFont === $key)
                                        <div style="position: absolute; top: 10px; left: 10px; width: 20px; height: 20px; border-radius: 50%; background: #0ea5e9; color: white; display: flex; align-items: center; justify-content: center; font-size: 12px; font-weight: 700;">✓</div>
                                    @endif
                                    <div style="font-size: 20px; font-weight: 800; color: #111827; font-family: '{{ $font['family'] }}', sans-serif;">{{ $font['name'] }}</div>
                                    <div style="font-size: 12px; color: #6b7280; font-family: '{{ $font['family'] }}', sans-serif;">نمونه متن فارسی ۱۲۳۴۵</div>
                                </label>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

            {{-- رنگ --}}
            <div style="background: white; border-radius: 14px; box-shadow: 0 1px 3px rgba(0,0,0,0.05); border: 1px solid #e5e7eb; overflow: hidden;">
                <div style="display: flex; align-items: center; justify-content: space-between; padding: 14px 20px; border-bottom: 1px solid #f3f4f6;">
                    <h2 style="margin: 0; font-size: 14px; font-weight: 700; color: #111827;">رنگ اصلی</h2>
                    <span style="background-color: {{ $this->colors[$selectedColor]['value'] ?? '#0ea5e9' }}; color: white; padding: 3px 12px; border-radius: 999px; font-size: 11px; font-weight: 600;">{{ $this->colors[$selectedColor]['name'] ?? '' }}</span>
                </div>
                <div style="padding: 20px;">
                    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(48px, 1fr)); gap: 10px;">
                        @foreach($this->colors as $key => $color)
                            <button type="button" wire:key="color-{{ $key }}" wire:click="$set('selectedColor', '{{ $key }}')" title="{{ $color['name'] }}" style="aspect-ratio: 1 / 1; width: 100%; border-radius: 12px; background-color: {{ $color['value'] }}; border: 3px solid {{ $selectedColor === $key ? '#111827' : 'transparent' }}; cursor: pointer; display: flex; align-items: center; justify-content: center; padding: 0; box-shadow: 0 1px 3px rgba(0,0,0,0.08);">
                                @if($selectedColor === $key)
                                    <span style="color: white; font-weight: bold; font-size: 18px; text-shadow: 0 1px 4px rgba(0,0,0,0.7); line-height: 1;">✓</span>
                                @endif
                            </button>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- اعداد فارسی --}}
            <div style="background: white; border-radius: 14px; box-shadow: 0 1px 3px rgba(0,0,0,0.05); border: 1px solid #e5e7eb; overflow: hidden;">
                <div style="padding: 14px 20px; border-bottom: 1px solid #f3f4f6;">
                    <h2 style="margin: 0; font-size: 14px; font-weight: 700; color: #111827;">اعداد فارسی</h2>
                </div>
                <div style="padding: 20px;">
                    <label style="display: flex; align-items: center; justify-content: space-between; gap: 16px; cursor: pointer;">
                        <div>
                            <div style="font-size: 13px; font-weight: 600; color: #111827; margin-bottom: 2px;">تبدیل خودکار اعداد لاتین به فارسی</div>
                            <div style="font-size: 11px; color: #9ca3af;">مثال: 1234 → ۱۲۳۴</div>
                        </div>
                        <input type="checkbox" wire:model.live="persianNumbers" style="width: 20px; height: 20px; cursor: pointer; accent-color: {{ $this->colors[$selectedColor]['value'] ?? '#0ea5e9' }};" />
                    </label>
                </div>
            </div>

        @endif

        {{-- ============================================================ --}}
        {{-- ============ تب: تاریخ ============ --}}
        {{-- ============================================================ --}}
        @if($tab === 'dates')

            <div style="background: white; border-radius: 14px; box-shadow: 0 1px 3px rgba(0,0,0,0.05); border: 1px solid #e5e7eb; overflow: hidden;">
                <div style="padding: 14px 20px; border-bottom: 1px solid #f3f4f6;">
                    <h2 style="margin: 0; font-size: 14px; font-weight: 700; color: #111827;">تقویم پیش‌فرض</h2>
                </div>
                <div style="padding: 20px; display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
                    @foreach(['jalali' => 'شمسی (جلالی)', 'gregorian' => 'میلادی'] as $key => $label)
                        <label style="display: flex; align-items: center; gap: 10px; padding: 14px; border: 2px solid {{ $calendar === $key ? '#0ea5e9' : '#e5e7eb' }}; background: {{ $calendar === $key ? 'rgba(14,165,233,0.05)' : 'white' }}; border-radius: 10px; cursor: pointer;">
                            <input type="radio" wire:model.live="calendar" value="{{ $key }}" style="width: 18px; height: 18px; cursor: pointer;" />
                            <span style="font-size: 13px; font-weight: 600; color: #111827;">{{ $label }}</span>
                        </label>
                    @endforeach
                </div>
            </div>

            <div style="background: white; border-radius: 14px; box-shadow: 0 1px 3px rgba(0,0,0,0.05); border: 1px solid #e5e7eb; overflow: hidden;">
                <div style="padding: 14px 20px; border-bottom: 1px solid #f3f4f6;">
                    <h2 style="margin: 0; font-size: 14px; font-weight: 700; color: #111827;">فرمت‌های تاریخ</h2>
                </div>
                <div style="padding: 20px; display: flex; flex-direction: column; gap: 16px;">
                    <div>
                        <label style="display: block; font-size: 12px; font-weight: 600; color: #374151; margin-bottom: 6px;">تاریخ + ساعت</label>
                        <input type="text" wire:model.live="dateFormat" style="width: 100%; padding: 10px 12px; border: 1px solid #d1d5db; border-radius: 8px; font-size: 13px; font-family: monospace; box-sizing: border-box; direction: ltr; text-align: left;" />
                        <div style="font-size: 11px; color: #9ca3af; margin-top: 4px;">مثال: <code>Y/m/d H:i</code> → ۱۴۰۳/۰۵/۱۲ ۱۴:۳۰</div>
                    </div>
                    <div>
                        <label style="display: block; font-size: 12px; font-weight: 600; color: #374151; margin-bottom: 6px;">فقط تاریخ</label>
                        <input type="text" wire:model.live="dateOnlyFormat" style="width: 100%; padding: 10px 12px; border: 1px solid #d1d5db; border-radius: 8px; font-size: 13px; font-family: monospace; box-sizing: border-box; direction: ltr; text-align: left;" />
                        <div style="font-size: 11px; color: #9ca3af; margin-top: 4px;">مثال: <code>Y/m/d</code> یا <code>j F Y</code></div>
                    </div>
                </div>
            </div>

            <div style="background: white; border-radius: 14px; box-shadow: 0 1px 3px rgba(0,0,0,0.05); border: 1px solid #e5e7eb; overflow: hidden;">
                <div style="padding: 14px 20px; border-bottom: 1px solid #f3f4f6;">
                    <h2 style="margin: 0; font-size: 14px; font-weight: 700; color: #111827;">اولین روز هفته</h2>
                </div>
                <div style="padding: 20px;">
                    <select wire:model.live="firstDayOfWeek" style="width: 100%; padding: 10px 12px; border: 1px solid #d1d5db; border-radius: 8px; font-size: 13px; font-family: inherit; box-sizing: border-box; background: white;">
                        <option value="6">شنبه</option>
                        <option value="0">یک‌شنبه</option>
                        <option value="1">دوشنبه</option>
                        <option value="2">سه‌شنبه</option>
                        <option value="3">چهارشنبه</option>
                        <option value="4">پنج‌شنبه</option>
                        <option value="5">جمعه</option>
                    </select>
                </div>
            </div>

        @endif

        {{-- ============================================================ --}}
        {{-- ============ تب: اعداد ============ --}}
        {{-- ============================================================ --}}
        @if($tab === 'numbers')

            <div style="background: white; border-radius: 14px; box-shadow: 0 1px 3px rgba(0,0,0,0.05); border: 1px solid #e5e7eb; overflow: hidden;">
                <div style="padding: 14px 20px; border-bottom: 1px solid #f3f4f6;">
                    <h2 style="margin: 0; font-size: 14px; font-weight: 700; color: #111827;">جداکننده‌ها</h2>
                </div>
                <div style="padding: 20px; display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                    <div>
                        <label style="display: block; font-size: 12px; font-weight: 600; color: #374151; margin-bottom: 6px;">جداکننده هزارگان</label>
                        <select wire:model.live="thousandSeparator" style="width: 100%; padding: 10px 12px; border: 1px solid #d1d5db; border-radius: 8px; font-size: 13px; font-family: inherit; box-sizing: border-box; background: white;">
                            <option value="٬">٬ (فارسی)</option>
                            <option value=",">, (انگلیسی)</option>
                            <option value=" ">فاصله</option>
                            <option value="">بدون جداکننده</option>
                        </select>
                    </div>
                    <div>
                        <label style="display: block; font-size: 12px; font-weight: 600; color: #374151; margin-bottom: 6px;">ممیز اعشار</label>
                        <select wire:model.live="decimalSeparator" style="width: 100%; padding: 10px 12px; border: 1px solid #d1d5db; border-radius: 8px; font-size: 13px; font-family: inherit; box-sizing: border-box; background: white;">
                            <option value="٫">٫ (فارسی)</option>
                            <option value=".">. (انگلیسی)</option>
                            <option value=",">, (اروپایی)</option>
                        </select>
                    </div>
                </div>
                <div style="padding: 0 20px 20px 20px;">
                    <div style="padding: 14px; background: #f9fafb; border-radius: 10px; font-family: monospace; font-size: 16px; direction: ltr; text-align: center;">
                        ۱{{ $thousandSeparator }}۲۳۴{{ $decimalSeparator }}۵۶
                    </div>
                </div>
            </div>

            <div style="background: white; border-radius: 14px; box-shadow: 0 1px 3px rgba(0,0,0,0.05); border: 1px solid #e5e7eb; overflow: hidden;">
                <div style="padding: 14px 20px; border-bottom: 1px solid #f3f4f6;">
                    <h2 style="margin: 0; font-size: 14px; font-weight: 700; color: #111827;">واحد پول پیش‌فرض</h2>
                </div>
                <div style="padding: 20px; display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 12px;">
                    @foreach(['IRT' => 'تومان', 'IRR' => 'ریال', 'none' => 'بدون واحد'] as $key => $label)
                        <label style="display: flex; align-items: center; gap: 10px; padding: 14px; border: 2px solid {{ $currency === $key ? '#0ea5e9' : '#e5e7eb' }}; background: {{ $currency === $key ? 'rgba(14,165,233,0.05)' : 'white' }}; border-radius: 10px; cursor: pointer;">
                            <input type="radio" wire:model.live="currency" value="{{ $key }}" style="width: 18px; height: 18px; cursor: pointer;" />
                            <span style="font-size: 13px; font-weight: 600; color: #111827;">{{ $label }}</span>
                        </label>
                    @endforeach
                </div>
            </div>

        @endif

        {{-- ============================================================ --}}
        {{-- ============ تب: ساعت کاری ============ --}}
        {{-- ============================================================ --}}
        @if($tab === 'working-hours')

            <div style="background: white; border-radius: 14px; box-shadow: 0 1px 3px rgba(0,0,0,0.05); border: 1px solid #e5e7eb; overflow: hidden;">
                <div style="padding: 14px 20px; border-bottom: 1px solid #f3f4f6; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 10px;">
                    <h2 style="margin: 0; font-size: 14px; font-weight: 700; color: #111827;">
                        ⏰ ساعت کاری هفتگی
                    </h2>
                    <div style="display: flex; gap: 8px; align-items: center;">
                        <span style="
                            background: linear-gradient(135deg, {{ $this->colors[$selectedColor]['value'] ?? '#0ea5e9' }} 0%, #8b5cf6 100%);
                            color: white;
                            padding: 4px 12px;
                            border-radius: 999px;
                            font-size: 11px;
                            font-weight: 700;
                        ">
                            {{ $this->weeklyHoursTotal }} ساعت در هفته
                        </span>
                        <button
                            type="button"
                            wire:click="resetWorkingHours"
                            style="
                                padding: 4px 12px;
                                border-radius: 8px;
                                border: 1px solid #d1d5db;
                                background: white;
                                color: #374151;
                                font-size: 11px;
                                font-family: inherit;
                                cursor: pointer;
                            "
                        >
                            بازنشانی
                        </button>
                    </div>
                </div>

                <div style="padding: 20px;">
                    <p style="margin: 0 0 16px 0; font-size: 12px; color: #6b7280; line-height: 1.7;">
                        ساعت شروع و پایان کار هر روز را تنظیم کن. روزهایی که نمی‌خواهی کاری باشند، سوییچشان را خاموش کن.
                    </p>

                    <div style="display: flex; flex-direction: column; gap: 10px;">
                        @foreach($this->dayNames as $dow => $dayName)
                            @php
                                $schedule = $workingHours[$dow] ?? null;
                                $isActive = ! empty($schedule) && ! empty($schedule['start']) && ! empty($schedule['end']);
                            @endphp
                            <div style="
                                display: grid;
                                grid-template-columns: 120px 1fr auto;
                                align-items: center;
                                gap: 16px;
                                padding: 12px 16px;
                                border-radius: 10px;
                                background: {{ $isActive ? '#f0f9ff' : '#f9fafb' }};
                                border: 1px solid {{ $isActive ? '#bae6fd' : '#e5e7eb' }};
                                transition: all 0.15s;
                            ">
                                <div style="font-size: 13px; font-weight: 700; color: #111827;">
                                    {{ $dayName }}
                                </div>

                                <div style="display: flex; align-items: center; gap: 10px; flex-wrap: wrap;">
                                    @if($isActive)
                                        <input
                                            type="time"
                                            wire:model.live="workingHours.{{ $dow }}.start"
                                            style="padding: 6px 10px; border: 1px solid #d1d5db; border-radius: 8px; font-family: monospace; font-size: 13px; background: white; direction: ltr;"
                                        />
                                        <span style="color: #9ca3af; font-size: 12px;">تا</span>
                                        <input
                                            type="time"
                                            wire:model.live="workingHours.{{ $dow }}.end"
                                            style="padding: 6px 10px; border: 1px solid #d1d5db; border-radius: 8px; font-family: monospace; font-size: 13px; background: white; direction: ltr;"
                                        />
                                    @else
                                        <span style="font-size: 12px; color: #9ca3af;">تعطیل</span>
                                    @endif
                                </div>

                                <button
                                    type="button"
                                    wire:click="toggleWorkingDay({{ $dow }})"
                                    style="
                                        position: relative;
                                        width: 44px;
                                        height: 24px;
                                        border-radius: 999px;
                                        border: none;
                                        background: {{ $isActive ? ($this->colors[$selectedColor]['value'] ?? '#0ea5e9') : '#d1d5db' }};
                                        cursor: pointer;
                                        transition: all 0.15s;
                                        padding: 0;
                                    "
                                >
                                    <span style="
                                        position: absolute;
                                        top: 3px;
                                        {{ $isActive ? 'right: 3px' : 'left: 3px' }};
                                        width: 18px;
                                        height: 18px;
                                        border-radius: 50%;
                                        background: white;
                                        box-shadow: 0 1px 3px rgba(0,0,0,0.2);
                                        transition: all 0.15s;
                                    "></span>
                                </button>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- تعطیلات رسمی --}}
            <div style="background: white; border-radius: 14px; box-shadow: 0 1px 3px rgba(0,0,0,0.05); border: 1px solid #e5e7eb; overflow: hidden;">
                <div style="padding: 14px 20px; border-bottom: 1px solid #f3f4f6;">
                    <h2 style="margin: 0; font-size: 14px; font-weight: 700; color: #111827;">
                        📅 تعطیلات رسمی ایران
                    </h2>
                </div>
                <div style="padding: 20px;">
                    <p style="margin: 0 0 16px 0; font-size: 12px; color: #6b7280; line-height: 1.7;">
                        این تعطیلات به‌طور خودکار در محاسبه ساعت کاری و نوبت‌دهی لحاظ می‌شوند.
                    </p>

                    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(240px, 1fr)); gap: 8px;">
                        @php
                            $solarHolidays = \Sghazanfari\FilamentPersian\Support\IranHolidays::solarHolidays();
                            $monthNames = ['فروردین','اردیبهشت','خرداد','تیر','مرداد','شهریور','مهر','آبان','آذر','دی','بهمن','اسفند'];
                        @endphp
                        @foreach($solarHolidays as $holiday)
                            @if($holiday['is_holiday'])
                                <div style="display: flex; align-items: center; gap: 10px; padding: 10px 12px; background: #fef3c7; border: 1px solid #fcd34d; border-radius: 8px;">
                                    <span style="font-size: 16px;">🎉</span>
                                    <div style="flex: 1;">
                                        <div style="font-size: 12px; font-weight: 700; color: #78350f;">
                                            {{ $holiday['day'] }} {{ $monthNames[$holiday['month'] - 1] }}
                                        </div>
                                        <div style="font-size: 11px; color: #92400e;">
                                            {{ $holiday['name'] }}
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>

                    <div style="margin-top: 16px; padding: 12px; background: #f0f9ff; border-radius: 10px; border: 1px solid #bae6fd;">
                        <div style="display: flex; gap: 10px; font-size: 12px; color: #0c4a6e; line-height: 1.7;">
                            <span style="font-size: 16px;">💡</span>
                            <div>
                                <strong>تعطیلات قمری</strong> (محرم، صفر، رمضان و...) هر سال جابه‌جا می‌شوند و
                                برای ۸ سال آینده (۱۴۰۳ تا ۱۴۱۰) در پکیج لحاظ شده‌اند.
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        @endif

        {{-- ============================================================ --}}
        {{-- ============ تب: صفحه ورود ============ --}}
        {{-- ============================================================ --}}
        @if($tab === 'login')

            {{-- انتخاب قالب --}}
            <div style="background: white; border-radius: 14px; box-shadow: 0 1px 3px rgba(0,0,0,0.05); border: 1px solid #e5e7eb; overflow: hidden;">
                <div style="padding: 14px 20px; border-bottom: 1px solid #f3f4f6;">
                    <h2 style="margin: 0; font-size: 14px; font-weight: 700; color: #111827;">
                        🎨 قالب صفحه ورود
                    </h2>
                    <p style="margin: 4px 0 0 0; font-size: 12px; color: #6b7280;">
                        یکی از {{ count($this->loginTemplates) }} قالب آماده را انتخاب کن. با ذخیره، بلافاصله اعمال می‌شود.
                    </p>
                </div>

                <div style="padding: 20px;">
                    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 12px;">
                        @foreach($this->loginTemplates as $key => $template)
                            <button
                                type="button"
                                wire:key="login-tpl-{{ $key }}"
                                wire:click="selectLoginTemplate('{{ $key }}')"
                                style="
                                    position: relative;
                                    padding: 16px;
                                    border-radius: 12px;
                                    border: 2px solid {{ $loginTemplate === $key ? ($this->colors[$selectedColor]['value'] ?? '#0ea5e9') : '#e5e7eb' }};
                                    background: {{ $loginTemplate === $key ? 'rgba(14,165,233,0.05)' : 'white' }};
                                    cursor: pointer;
                                    text-align: right;
                                    font-family: inherit;
                                    transition: all 0.15s;
                                    display: flex;
                                    flex-direction: column;
                                    gap: 8px;
                                "
                                onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 8px 20px rgba(0,0,0,0.08)';"
                                onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none';"
                            >
                                @if($loginTemplate === $key)
                                    <div style="
                                        position: absolute;
                                        top: 8px;
                                        left: 8px;
                                        width: 20px;
                                        height: 20px;
                                        border-radius: 50%;
                                        background: {{ $this->colors[$selectedColor]['value'] ?? '#0ea5e9' }};
                                        color: white;
                                        display: flex;
                                        align-items: center;
                                        justify-content: center;
                                        font-size: 12px;
                                        font-weight: 700;
                                    ">✓</div>
                                @endif

                                <div style="font-size: 32px; line-height: 1;">
                                    {{ $template['icon'] }}
                                </div>
                                <div>
                                    <div style="font-size: 14px; font-weight: 700; color: #111827;">
                                        {{ $template['name'] }}
                                    </div>
                                    <div style="font-size: 11px; color: #6b7280; margin-top: 2px; line-height: 1.5;">
                                        {{ $template['description'] }}
                                    </div>
                                </div>
                            </button>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- پیش‌نمایش + باز کردن --}}
            <div style="background: white; border-radius: 14px; box-shadow: 0 1px 3px rgba(0,0,0,0.05); border: 1px solid #e5e7eb; overflow: hidden;">
                <div style="padding: 14px 20px; border-bottom: 1px solid #f3f4f6; display: flex; align-items: center; justify-content: space-between; gap: 12px; flex-wrap: wrap;">
                    <div>
                        <h2 style="margin: 0; font-size: 14px; font-weight: 700; color: #111827;">
                            👁️ پیش‌نمایش قالب
                        </h2>
                        <p style="margin: 4px 0 0 0; font-size: 12px; color: #6b7280;">
                            برای دیدن قالب کامل، روی دکمه زیر کلیک کن
                        </p>
                    </div>
                    <a
                        href="{{ url('/admin/login') }}"
                        target="_blank"
                        style="
                            padding: 8px 16px;
                            border-radius: 8px;
                            background: {{ $this->colors[$selectedColor]['value'] ?? '#0ea5e9' }};
                            color: white;
                            font-size: 12px;
                            font-weight: 700;
                            text-decoration: none;
                            display: flex;
                            align-items: center;
                            gap: 6px;
                        "
                    >
                        <span>↗</span>
                        <span>باز کردن در تب جدید</span>
                    </a>
                </div>
                <div style="padding: 20px;">
                    <div style="
                        border-radius: 12px;
                        border: 1px solid #e5e7eb;
                        background: linear-gradient(135deg, {{ $this->colors[$selectedColor]['value'] ?? '#0ea5e9' }}15 0%, {{ $this->colors[$selectedColor]['value'] ?? '#0ea5e9' }}05 100%);
                        padding: 40px 20px;
                        text-align: center;
                    ">
                        <div style="
                            display: inline-block;
                            padding: 16px 24px;
                            border-radius: 12px;
                            background: white;
                            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
                            font-size: 13px;
                            color: #6b7280;
                            max-width: 340px;
                        ">
                            <div style="font-size: 28px; margin-bottom: 8px;">
                                {{ $this->loginTemplates[$loginTemplate]['icon'] ?? '🔐' }}
                            </div>
                            <div style="font-weight: 700; color: #111827; margin-bottom: 4px;">
                                {{ $this->loginTemplates[$loginTemplate]['name'] ?? 'قالب' }}
                            </div>
                            <div style="font-size: 11px;">
                                {{ $this->loginTemplates[$loginTemplate]['description'] ?? '' }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- متن‌های صفحه ورود --}}
            <div style="background: white; border-radius: 14px; box-shadow: 0 1px 3px rgba(0,0,0,0.05); border: 1px solid #e5e7eb; overflow: hidden;">
                <div style="padding: 14px 20px; border-bottom: 1px solid #f3f4f6; display: flex; align-items: center; justify-content: space-between; gap: 12px; flex-wrap: wrap;">
                    <div>
                        <h2 style="margin: 0; font-size: 14px; font-weight: 700; color: #111827;">
                            ✏️ متن‌های صفحه ورود
                        </h2>
                        <p style="margin: 4px 0 0 0; font-size: 12px; color: #6b7280;">
                            متن‌های نمایش داده شده روی صفحه ورود را ویرایش کن.
                        </p>
                    </div>
                    <button
                        type="button"
                        wire:click="resetLoginTexts"
                        style="
                            padding: 6px 14px;
                            border-radius: 8px;
                            border: 1px solid #d1d5db;
                            background: white;
                            color: #374151;
                            font-size: 11px;
                            font-family: inherit;
                            cursor: pointer;
                        "
                    >
                        بازنشانی
                    </button>
                </div>

                <div style="padding: 20px; display: flex; flex-direction: column; gap: 16px;">
                    @foreach($this->loginTextFields as $key => $field)
                        <div>
                            <label style="display: block; font-size: 12px; font-weight: 600; color: #374151; margin-bottom: 6px;">
                                {{ $field['label'] }}
                            </label>
                            <input
                                type="text"
                                wire:model.live.debounce.500ms="loginTexts.{{ $key }}"
                                placeholder="{{ $field['placeholder'] }}"
                                style="
                                    width: 100%;
                                    padding: 10px 14px;
                                    border: 1px solid #d1d5db;
                                    border-radius: 8px;
                                    font-size: 13px;
                                    font-family: inherit;
                                    box-sizing: border-box;
                                    outline: none;
                                    transition: all 0.15s;
                                "
                                onfocus="this.style.borderColor='{{ $this->colors[$selectedColor]['value'] ?? '#0ea5e9' }}'; this.style.boxShadow='0 0 0 3px {{ $this->colors[$selectedColor]['value'] ?? '#0ea5e9' }}20';"
                                onblur="this.style.borderColor='#d1d5db'; this.style.boxShadow='none';"
                            />
                        </div>
                    @endforeach
                </div>

                {{-- پیش‌نمایش متن‌ها --}}
                <div style="padding: 0 20px 20px 20px;">
                    <div style="padding: 16px; background: #f9fafb; border-radius: 10px; border: 1px dashed #d1d5db;">
                        <div style="font-size: 11px; font-weight: 700; color: #6b7280; margin-bottom: 12px;">
                            پیش‌نمایش:
                        </div>
                        <div style="text-align: center;">
                            <div style="font-size: 22px; font-weight: 800; color: {{ $this->colors[$selectedColor]['value'] ?? '#0ea5e9' }}; margin-bottom: 6px;">
                                {{ $loginTexts['heading'] ?? 'ورود به حساب' }}
                            </div>
                            <div style="font-size: 12px; color: #6b7280; margin-bottom: 16px;">
                                {{ $loginTexts['subheading'] ?? '' }}
                            </div>
                            <div style="display: inline-block; padding: 10px 24px; background: {{ $this->colors[$selectedColor]['value'] ?? '#0ea5e9' }}; color: white; border-radius: 8px; font-size: 12px; font-weight: 700;">
                                ورود
                            </div>
                            <div style="margin-top: 20px; padding-top: 12px; border-top: 1px solid #e5e7eb; font-size: 10px; color: #9ca3af;">
                                {{ $loginTexts['copyright_prefix'] ?? '©' }} {{ fa_digits(jalali_format(now(), 'Y')) }} — {{ $loginTexts['footer'] ?? '' }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        @endif

        {{-- ============================================================ --}}
        {{-- ============ تب: قالب‌های آماده ============ --}}
        {{-- ============================================================ --}}
        @if($tab === 'presets')

            <div style="background: white; border-radius: 14px; box-shadow: 0 1px 3px rgba(0,0,0,0.05); border: 1px solid #e5e7eb; overflow: hidden;">
                <div style="padding: 14px 20px; border-bottom: 1px solid #f3f4f6;">
                    <h2 style="margin: 0; font-size: 14px; font-weight: 700; color: #111827;">قالب‌های آماده</h2>
                    <p style="margin: 4px 0 0 0; font-size: 12px; color: #6b7280;">با انتخاب هر قالب، همه تنظیمات به‌صورت یک‌جا اعمال می‌شوند. سپس می‌توانید دلخواه ویرایش کنید.</p>
                </div>
                <div style="padding: 20px;">
                    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(260px, 1fr)); gap: 14px;">
                        @foreach($this->presets as $key => $preset)
                            <button
                                type="button"
                                wire:click="applyPreset('{{ $key }}')"
                                style="
                                    padding: 20px;
                                    border-radius: 12px;
                                    border: 2px solid {{ $this->activePreset === $key ? '#0ea5e9' : '#e5e7eb' }};
                                    background: {{ $this->activePreset === $key ? 'rgba(14,165,233,0.05)' : 'white' }};
                                    cursor: pointer;
                                    text-align: right;
                                    font-family: inherit;
                                    transition: all 0.15s;
                                    display: flex;
                                    flex-direction: column;
                                    gap: 8px;
                                "
                                onmouseover="this.style.borderColor='#0ea5e9'; this.style.transform='translateY(-2px)';"
                                onmouseout="this.style.borderColor='{{ $this->activePreset === $key ? '#0ea5e9' : '#e5e7eb' }}'; this.style.transform='translateY(0)';"
                            >
                                <div style="display: flex; align-items: center; gap: 10px;">
                                    <span style="font-size: 28px;">{{ $preset['icon'] }}</span>
                                    <div>
                                        <div style="font-size: 15px; font-weight: 700; color: #111827;">{{ $preset['name'] }}</div>
                                        @if($this->activePreset === $key)
                                            <span style="font-size: 10px; color: #0ea5e9; font-weight: 700;">● فعال</span>
                                        @endif
                                    </div>
                                </div>
                                <div style="font-size: 12px; color: #6b7280;">{{ $preset['description'] }}</div>
                                <div style="display: flex; gap: 6px; margin-top: 4px; flex-wrap: wrap;">
                                    <span style="padding: 2px 8px; background: #f3f4f6; border-radius: 999px; font-size: 10px; color: #4b5563;">{{ $preset['settings']['font'] }}</span>
                                    <span style="padding: 2px 8px; background: #f3f4f6; border-radius: 999px; font-size: 10px; color: #4b5563;">{{ $preset['settings']['calendar'] === 'jalali' ? 'شمسی' : 'میلادی' }}</span>
                                </div>
                            </button>
                        @endforeach
                    </div>
                </div>
            </div>

        @endif

        {{-- ============ دکمه‌های عملیات ============ --}}
        <div style="background: white; border-radius: 14px; box-shadow: 0 1px 3px rgba(0,0,0,0.05); border: 1px solid #e5e7eb; padding: 16px 20px; display: flex; align-items: center; justify-content: space-between; gap: 12px; flex-wrap: wrap; position: sticky; bottom: 16px; z-index: 10;">
            <div style="font-size: 12px; display: flex; align-items: center; gap: 8px; color: #6b7280;">
                @if($this->isDirty)
                    <span style="display: inline-block; width: 8px; height: 8px; border-radius: 50%; background: #f59e0b; animation: fp-pulse 1.5s ease-in-out infinite;"></span>
                    <span>تغییرات ذخیره نشده</span>
                @else
                    <span style="display: inline-block; width: 8px; height: 8px; border-radius: 50%; background: #10b981;"></span>
                    <span>همه چیز ذخیره شده</span>
                @endif
            </div>
            <div style="display: flex; gap: 10px;">
                <button type="button" wire:click="resetToOriginal" @if(! $this->isDirty) disabled @endif style="padding: 9px 18px; border-radius: 8px; border: 1px solid #d1d5db; background: white; color: #374151; font-size: 13px; font-weight: 600; cursor: {{ $this->isDirty ? 'pointer' : 'not-allowed' }}; font-family: inherit; opacity: {{ $this->isDirty ? '1' : '0.5' }};">بازنشانی</button>
                <button type="button" wire:click="save" @if(! $this->isDirty) disabled @endif style="padding: 9px 22px; border-radius: 8px; border: none; background: {{ $this->colors[$selectedColor]['value'] ?? '#0ea5e9' }}; color: white; font-size: 13px; font-weight: 700; cursor: {{ $this->isDirty ? 'pointer' : 'not-allowed' }}; font-family: inherit; opacity: {{ $this->isDirty ? '1' : '0.6' }}; box-shadow: 0 2px 6px {{ $this->colors[$selectedColor]['value'] ?? '#0ea5e9' }}40;">ذخیره تغییرات</button>
            </div>
        </div>

    </div>

    <style>
        @keyframes fp-pulse { 0%, 100% { opacity: 1; } 50% { opacity: 0.4; } }
    </style>
</x-filament-panels::page>