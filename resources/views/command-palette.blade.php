<div
    x-data="CommandPalette()"
    style="display: none;"
    x-show="isOpen"
    x-cloak
>
    <div
        @click.self="close()"
        style="
            position: fixed;
            inset: 0;
            z-index: 99999;
            background: rgba(0, 0, 0, 0.5);
            display: flex;
            align-items: flex-start;
            justify-content: center;
            padding: 15vh 16px 16px 16px;
            direction: rtl;
        "
    >
        <div
            style="
                width: 100%;
                max-width: 640px;
                background: white;
                border-radius: 16px;
                box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
                overflow: hidden;
                font-family: inherit;
            "
            @click.stop
        >
            {{-- ورودی جستجو --}}
            <div style="padding: 16px 20px; border-bottom: 1px solid #f3f4f6;">
                <input
                    x-ref="searchInput"
                    type="text"
                    x-model="search"
                    @keydown="handleKeydown($event)"
                    placeholder="جستجو یا رفتن به..."
                    style="
                        width: 100%;
                        border: none;
                        outline: none;
                        font-size: 16px;
                        font-family: inherit;
                        background: transparent;
                        color: #111827;
                        text-align: right;
                        padding: 4px 0;
                        box-sizing: border-box;
                    "
                />
            </div>

            {{-- نتایج --}}
            <div style="max-height: 400px; overflow-y: auto;">

                {{-- هیچ نتیجه‌ای --}}
                <template x-if="allFiltered.length === 0 && !isLoading && search.length > 0">
                    <div style="padding: 40px 20px; text-align: center; color: #9ca3af; font-size: 14px;">
                        نتیجه‌ای یافت نشد
                    </div>
                </template>

                {{-- راهنما وقتی چیزی تایپ نشده --}}
                <template x-if="allFiltered.length === 0 && search.length === 0">
                    <div style="padding: 40px 20px; text-align: center; color: #9ca3af; font-size: 13px;">
                        برای جستجو تایپ کنید — حداقل ۲ حرف برای جستجوی رکوردها
                    </div>
                </template>

                {{-- در حال جستجوی رکوردها --}}
                <template x-if="isLoading">
                    <div style="padding: 12px 20px; color: #6b7280; font-size: 12px; display: flex; align-items: center; gap: 8px;">
                        <span style="display: inline-block; width: 10px; height: 10px; border-radius: 50%; border: 2px solid #0ea5e9; border-top-color: transparent; animation: cp-spin 0.8s linear infinite;"></span>
                        در حال جستجو...
                    </div>
                </template>

                {{-- منوها --}}
                <template x-if="filteredMenus.length > 0">
                    <div>
                        <div style="padding: 8px 20px 4px; font-size: 10px; font-weight: 700; color: #9ca3af; text-transform: uppercase; letter-spacing: 0.5px;">
                            صفحه‌ها
                        </div>
                        <template x-for="(item, index) in filteredMenus" :key="'menu-' + item.url">
                            <button
                                type="button"
                                :data-cp-index="index"
                                @click="select(index)"
                                @mouseenter="selectedIndex = index"
                                :style="`width: 100%; padding: 10px 20px; border: none; background: ${isSelected(item) ? 'rgba(14,165,233,0.08)' : 'transparent'}; cursor: pointer; display: flex; align-items: center; gap: 12px; text-align: right; font-family: inherit;`"
                            >
                                <div style="flex: 1; text-align: right;">
                                    <div style="font-size: 13px; font-weight: 500; color: #111827;" x-text="item.title"></div>
                                </div>
                                <div style="flex-shrink: 0; font-size: 12px; color: #9ca3af;">
                                    <span x-show="isSelected(item)">↵</span>
                                </div>
                            </button>
                        </template>
                    </div>
                </template>

                {{-- رکوردها --}}
                <template x-if="filteredRecords.length > 0">
                    <div>
                        <div style="padding: 8px 20px 4px; font-size: 10px; font-weight: 700; color: #9ca3af; text-transform: uppercase; letter-spacing: 0.5px;">
                            رکوردها
                        </div>
                        <template x-for="(item, index) in filteredRecords" :key="'rec-' + item.url">
                            <button
                                type="button"
                                :data-cp-index="filteredMenus.length + index"
                                @click="select(filteredMenus.length + index)"
                                @mouseenter="selectedIndex = filteredMenus.length + index"
                                :style="`width: 100%; padding: 10px 20px; border: none; background: ${isSelected(item) ? 'rgba(14,165,233,0.08)' : 'transparent'}; cursor: pointer; display: flex; align-items: center; gap: 12px; text-align: right; font-family: inherit;`"
                            >
                                <div style="flex: 1; text-align: right;">
                                    <div style="font-size: 13px; font-weight: 500; color: #111827;" x-text="item.title"></div>
                                    <div style="font-size: 11px; color: #9ca3af; margin-top: 2px;" x-text="item.group"></div>
                                </div>
                                <div style="flex-shrink: 0; font-size: 12px; color: #9ca3af;">
                                    <span x-show="isSelected(item)">↵</span>
                                </div>
                            </button>
                        </template>
                    </div>
                </template>

            </div>

            {{-- راهنمای پایین --}}
            <div style="
                padding: 10px 20px;
                border-top: 1px solid #f3f4f6;
                background: #fafafa;
                display: flex;
                justify-content: space-between;
                align-items: center;
                font-size: 11px;
                color: #6b7280;
            ">
                <div style="display: flex; gap: 14px;">
                    <span>
                        <kbd style="padding: 1px 6px; background: white; border: 1px solid #e5e7eb; border-radius: 4px; font-family: inherit;">↑↓</kbd>
                        ناوبری
                    </span>
                    <span>
                        <kbd style="padding: 1px 6px; background: white; border: 1px solid #e5e7eb; border-radius: 4px; font-family: inherit;">↵</kbd>
                        انتخاب
                    </span>
                    <span>
                        <kbd style="padding: 1px 6px; background: white; border: 1px solid #e5e7eb; border-radius: 4px; font-family: inherit;">Esc</kbd>
                        بستن
                    </span>
                </div>
                <div style="font-size: 11px;">
                    باز کردن:
                    <kbd style="padding: 1px 6px; background: white; border: 1px solid #e5e7eb; border-radius: 4px; font-family: inherit;">Ctrl</kbd>
                    +
                    <kbd style="padding: 1px 6px; background: white; border: 1px solid #e5e7eb; border-radius: 4px; font-family: inherit;">K</kbd>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    [x-cloak] { display: none !important; }
    @keyframes cp-spin {
        to { transform: rotate(360deg); }
    }
</style>