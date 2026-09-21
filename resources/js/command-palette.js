/**
 * Command Palette فارسی — باز شدن با Ctrl+K
 * شامل منوها + رکوردها (با جستجوی سرور)
 */
(function () {
    'use strict';

    const CHAR_MAP = {
        'ي': 'ی', 'ك': 'ک', 'ة': 'ه', 'ۀ': 'ه',
        'ؤ': 'و', 'إ': 'ا', 'أ': 'ا',
    };

    function normalize(text) {
        if (!text) return '';
        let r = String(text);
        for (const [from, to] of Object.entries(CHAR_MAP)) {
            r = r.split(from).join(to);
        }
        r = r.replace(/\u200C/g, '');
        return r.toLowerCase().trim();
    }

    function extractIcon(el) {
        const svg = el.querySelector('svg');
        return svg ? svg.outerHTML : null;
    }

    function collectMenuItems() {
        const items = [];
        const seen = new Set();

        const selectors = [
            '.fi-sidebar-item a',
            '.fi-sidebar-item button',
            '.fi-sidebar-group a',
            'nav a[href]:not([target=_blank])',
        ];

        selectors.forEach(sel => {
            document.querySelectorAll(sel).forEach(el => {
                const label = (el.textContent || '').trim().split('\n')[0].trim();
                const url = el.getAttribute('href') || el.dataset.url || '';
                if (!label || !url || url === '#' || url.startsWith('javascript')) return;
                if (seen.has(url)) return;
                seen.add(url);

                items.push({
                    title: label,
                    url: url,
                    icon: extractIcon(el),
                    group: 'صفحه‌ها',
                    type: 'menu',
                });
            });
        });

        return items;
    }

    window.CommandPalette = function () {
        return {
            isOpen: false,
            search: '',
            menuItems: [],
            recordItems: [],
            filteredMenus: [],
            filteredRecords: [],
            selectedIndex: 0,
            isLoading: false,
            _debounceTimer: null,

            get allFiltered() {
                return [...this.filteredMenus, ...this.filteredRecords];
            },

            init() {
                document.addEventListener('keydown', (e) => {
                    if ((e.ctrlKey || e.metaKey) && (e.key === 'k' || e.key === 'K')) {
                        e.preventDefault();
                        this.toggle();
                    } else if (e.key === 'Escape' && this.isOpen) {
                        e.preventDefault();
                        this.close();
                    }
                });

                this.$watch('isOpen', (value) => {
                    if (value) {
                        this.menuItems = collectMenuItems();
                        this.filterMenus();
                        this.$nextTick(() => {
                            if (this.$refs.searchInput) this.$refs.searchInput.focus();
                        });
                    } else {
                        this.search = '';
                        this.recordItems = [];
                        this.filteredRecords = [];
                    }
                });

                this.$watch('search', () => {
                    this.filterMenus();
                    this.debouncedSearchRecords();
                });
            },

            debouncedSearchRecords() {
                clearTimeout(this._debounceTimer);
                const q = this.search.trim();

                if (q.length < 2) {
                    this.recordItems = [];
                    this.filteredRecords = [];
                    this.selectedIndex = 0;
                    this.isLoading = false;
                    return;
                }

                this.isLoading = true;
                this._debounceTimer = setTimeout(() => this.searchRecords(), 300);
            },

            async searchRecords() {
                try {
                    const r = await fetch(
                        '/admin/_command-search?q=' + encodeURIComponent(this.search.trim()),
                        {
                            headers: {
                                'Accept': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest',
                            },
                            credentials: 'same-origin',
                        }
                    );

                    if (!r.ok) throw new Error('HTTP ' + r.status);

                    this.recordItems = await r.json();
                } catch (e) {
                    console.warn('Command search error:', e);
                    this.recordItems = [];
                } finally {
                    this.isLoading = false;
                    this.filterRecords();
                }
            },

            filterMenus() {
                const needle = normalize(this.search);
                this.filteredMenus = !needle
                    ? this.menuItems.slice(0, 8)
                    : this.menuItems.filter(i => normalize(i.title).includes(needle)).slice(0, 8);
                this.selectedIndex = 0;
            },

            filterRecords() {
                const needle = normalize(this.search);
                this.filteredRecords = !needle
                    ? []
                    : this.recordItems.filter(i => normalize(i.title).includes(needle));
                this.selectedIndex = 0;
            },

            toggle() { this.isOpen ? this.close() : this.open(); },
            open() { this.isOpen = true; },
            close() { this.isOpen = false; },

            navigate(index) {
                const all = this.allFiltered;
                if (all.length === 0) return;
                this.selectedIndex = Math.max(0, Math.min(index, all.length - 1));
                this.$nextTick(() => {
                    const el = document.querySelector(`[data-cp-index="${this.selectedIndex}"]`);
                    el?.scrollIntoView({ block: 'nearest' });
                });
            },

            handleKeydown(e) {
                if (e.key === 'ArrowDown') { e.preventDefault(); this.navigate(this.selectedIndex + 1); }
                else if (e.key === 'ArrowUp') { e.preventDefault(); this.navigate(this.selectedIndex - 1); }
                else if (e.key === 'Enter') { e.preventDefault(); this.select(this.selectedIndex); }
            },

            select(index) {
                const item = this.allFiltered[index];
                if (item?.url) {
                    this.close();
                    window.location.href = item.url;
                }
            },

            isSelected(item) {
                return this.allFiltered[this.selectedIndex]?.url === item.url;
            },
        };
    };
})();