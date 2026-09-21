@php
    $statePath = $getStatePath();
    $isDisabled = $isDisabled();
    $withTime = $isWithTime();
    $withSeconds = $isWithSeconds();
    $jalaliValue = $getJalaliValue();
@endphp

<x-dynamic-component :component="$getFieldWrapperView()" :field="$field">
    <div
        wire:ignore
        x-data="{
            instance: null,
            init() {
                const input = this.$refs.input;
                this.instance = new window.JalaliCalendar(input, {
                    withTime: @js($withTime),
                    withSeconds: @js($withSeconds),
                    value: @js($jalaliValue),
                    onChange: (value) => {
                        if (! value) {
                            $wire.set(@js($statePath), null);
                            return;
                        }
                        const pad = (n) => String(n).padStart(2, '0');
                        let str = `${value.y}/${pad(value.m)}/${pad(value.d)}`;
                        if (@js($withTime)) {
                            str += ` ${pad(value.h || 0)}:${pad(value.i || 0)}`;
                            if (@js($withSeconds)) str += `:${pad(value.s || 0)}`;
                        }
                        $wire.set(@js($statePath), str);
                    },
                });
            },
            destroy() {
                if (this.instance) this.instance.destroy();
            }
        }"
        style="position: relative;"
    >
        <input
            x-ref="input"
            type="text"
            @if($isDisabled) disabled @endif
            placeholder="{{ $withTime ? ($withSeconds ? '۱۴۰۳/۰۵/۱۲ ۱۴:۳۰:۰۰' : '۱۴۰۳/۰۵/۱۲ ۱۴:۳۰') : '۱۴۰۳/۰۵/۱۲' }}"
            style="
                width: 100%;
                padding: 9px 12px;
                border-radius: 8px;
                border: 1px solid #d1d5db;
                font-family: inherit;
                font-size: 13px;
                box-sizing: border-box;
                outline: none;
                text-align: right;
                background: white;
            "
            autocomplete="off"
            inputmode="numeric"
        />
    </div>
</x-dynamic-component>