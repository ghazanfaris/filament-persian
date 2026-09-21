<x-filament-panels::page>
    <div style="display: grid; grid-template-columns: 280px 1fr; gap: 20px; align-items: start;">

        {{-- سایدبار --}}
        <aside style="
            background: white;
            border-radius: 14px;
            border: 1px solid #e5e7eb;
            overflow: hidden;
            position: sticky;
            top: 20px;
            max-height: calc(100vh - 120px);
            display: flex;
            flex-direction: column;
        ">
            <div style="padding: 14px 16px; border-bottom: 1px solid #f3f4f6;">
                <input
                    type="text"
                    wire:model.live.debounce.300ms="search"
                    placeholder="جستجو در مستندات..."
                    style="
                        width: 100%;
                        padding: 8px 12px;
                        border: 1px solid #d1d5db;
                        border-radius: 8px;
                        font-size: 13px;
                        font-family: inherit;
                        box-sizing: border-box;
                        outline: none;
                    "
                />
            </div>

            <nav style="padding: 8px; overflow-y: auto; flex: 1;">
                @forelse($this->filteredDocs as $slug => $doc)
                    <button
                        type="button"
                        wire:key="doc-{{ $slug }}"
                        wire:click="selectDoc('{{ $slug }}')"
                        style="
                            display: flex;
                            align-items: center;
                            gap: 8px;
                            width: 100%;
                            padding: 9px 12px;
                            border: none;
                            border-radius: 8px;
                            cursor: pointer;
                            font-family: inherit;
                            font-size: 13px;
                            text-align: right;
                            transition: all 0.1s;
                            background: {{ $activeSlug === $slug ? 'rgba(14,165,233,0.1)' : 'transparent' }};
                            color: {{ $activeSlug === $slug ? '#0ea5e9' : '#374151' }};
                            font-weight: {{ $activeSlug === $slug ? '700' : '500' }};
                            margin-bottom: 2px;
                        "
                    >
                        <span style="font-size: 11px;">📄</span>
                        <span>{{ $doc['title'] }}</span>
                    </button>
                @empty
                    <div style="padding: 20px; text-align: center; color: #9ca3af; font-size: 12px;">
                        نتیجه‌ای یافت نشد
                    </div>
                @endforelse
            </nav>
        </aside>

        {{-- محتوا --}}
        <main style="
            background: white;
            border-radius: 14px;
            border: 1px solid #e5e7eb;
            padding: 32px 40px;
            min-height: 400px;
            overflow-x: auto;
        ">
            <article class="filament-persian-docs">
                {!! $this->activeHtml !!}
            </article>
        </main>

    </div>

    <style>
        .filament-persian-docs { line-height: 1.9; color: #1f2937; font-size: 14px; }
        .filament-persian-docs h1 {
            font-size: 28px; font-weight: 800; color: #111827;
            margin: 0 0 24px 0; padding-bottom: 16px;
            border-bottom: 2px solid #f3f4f6;
        }
        .filament-persian-docs h2 {
            font-size: 20px; font-weight: 700; color: #111827;
            margin: 32px 0 14px 0; padding-bottom: 8px;
            border-bottom: 1px solid #f3f4f6;
        }
        .filament-persian-docs h3 {
            font-size: 16px; font-weight: 700; color: #111827;
            margin: 24px 0 10px 0;
        }
        .filament-persian-docs p { margin: 12px 0; }
        .filament-persian-docs ul,
        .filament-persian-docs ol { padding-right: 24px; margin: 12px 0; }
        .filament-persian-docs li { margin: 6px 0; }
        .filament-persian-docs a {
            color: #0ea5e9; text-decoration: none;
            border-bottom: 1px dashed #0ea5e9;
        }
        .filament-persian-docs a:hover { border-bottom-style: solid; }
        .filament-persian-docs code {
            background: #f3f4f6; color: #be185d;
            padding: 2px 6px; border-radius: 4px;
            font-size: 12.5px;
            font-family: 'Fira Code', 'Consolas', monospace;
            direction: ltr; display: inline-block;
        }
        .filament-persian-docs pre {
            background: #1e293b; color: #e2e8f0;
            padding: 18px 20px; border-radius: 10px;
            overflow-x: auto; margin: 16px 0;
            direction: ltr; text-align: left;
            font-size: 12.5px; line-height: 1.7;
        }
        .filament-persian-docs pre code {
            background: transparent; color: inherit;
            padding: 0; font-size: inherit; display: block;
        }
        .filament-persian-docs table {
            width: 100%; border-collapse: collapse;
            margin: 16px 0; font-size: 13px;
        }
        .filament-persian-docs th,
        .filament-persian-docs td {
            padding: 10px 12px; border: 1px solid #e5e7eb;
            text-align: right;
        }
        .filament-persian-docs th {
            background: #f9fafb; font-weight: 700; color: #111827;
        }
        .filament-persian-docs tr:nth-child(even) td { background: #fafbfc; }
        .filament-persian-docs blockquote {
            border-right: 4px solid #0ea5e9; background: #f0f9ff;
            padding: 12px 16px; margin: 16px 0;
            border-radius: 8px; color: #0c4a6e;
        }
        .filament-persian-docs hr {
            border: none; border-top: 1px solid #e5e7eb;
            margin: 32px 0;
        }
    </style>
</x-filament-panels::page>