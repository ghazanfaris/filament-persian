/**
 * JalaliCalendar — تقویم جلالی مستقل، حرفه‌ای، بدون وابستگی
 * Supports: date, datetime, time, navigation, Persian digits, RTL
 */
(function (global) {
    'use strict';

    const PERSIAN_DIGITS = '۰۱۲۳۴۵۶۷۸۹';
    const MONTH_NAMES = ['فروردین','اردیبهشت','خرداد','تیر','مرداد','شهریور','مهر','آبان','آذر','دی','بهمن','اسفند'];
    const WEEKDAYS_LONG = ['یک‌شنبه','دوشنبه','سه‌شنبه','چهارشنبه','پنج‌شنبه','جمعه','شنبه'];
    const WEEKDAYS_SHORT = ['ی','د','س','چ','پ','ج','ش'];

    function toPersianDigits(s) {
        return String(s).replace(/[0-9]/g, d => PERSIAN_DIGITS[d]);
    }
    function toLatinDigits(s) {
        return String(s).replace(/[۰-۹]/g, d => PERSIAN_DIGITS.indexOf(d));
    }
    function pad(n) { return String(n).padStart(2, '0'); }

    // ---------- تبدیل ----------
    function toJalali(gy, gm, gd) {
        const g_d_m = [0,31,59,90,120,151,181,212,243,273,304,334];
        const gy2 = (gm > 2) ? (gy + 1) : gy;
        let days = 355666 + (365 * gy) + Math.floor((gy2 + 3) / 4)
                 - Math.floor((gy2 + 99) / 100) + Math.floor((gy2 + 399) / 400)
                 + gd + g_d_m[gm - 1];
        let jy = -1595 + 33 * Math.floor(days / 12053);
        days %= 12053;
        jy += 4 * Math.floor(days / 1461);
        days %= 1461;
        if (days > 365) {
            jy += Math.floor((days - 1) / 365);
            days = (days - 1) % 365;
        }
        let jm, jd;
        if (days < 186) {
            jm = 1 + Math.floor(days / 31);
            jd = 1 + (days % 31);
        } else {
            jm = 7 + Math.floor((days - 186) / 30);
            jd = 1 + ((days - 186) % 30);
        }
        return [jy, jm, jd];
    }

    function toGregorian(jy, jm, jd) {
        jy += 1595;
        let days = -355668 + 365 * jy + Math.floor(jy / 33) * 8
                 + Math.floor(((jy % 33) + 3) / 4) + jd
                 + ((jm < 7) ? (jm - 1) * 31 : (jm - 7) * 30 + 186);
        let gy = 400 * Math.floor(days / 146097);
        days %= 146097;
        if (days > 36524) {
            gy += 100 * Math.floor(--days / 36524);
            days %= 36524;
            if (days >= 365) days++;
        }
        gy += 4 * Math.floor(days / 1461);
        days %= 1461;
        if (days > 365) {
            gy += Math.floor((days - 1) / 365);
            days = (days - 1) % 365;
        }
        let gd = days + 1;
        const sal_a = [0,31,(gy%4===0 && (gy%100!==0 || gy%400===0)) ? 29 : 28,31,30,31,30,31,31,30,31,30,31];
        let gm;
        for (gm = 1; gm <= 12 && gd > sal_a[gm]; gm++) gd -= sal_a[gm];
        return [gy, gm, gd];
    }

    function isLeapJalali(jy) {
        return [1,5,9,13,17,22,26,30].includes(jy % 33);
    }
    function daysInJalaliMonth(jy, jm) {
        if (jm <= 6) return 31;
        if (jm <= 11) return 30;
        return isLeapJalali(jy) ? 30 : 29;
    }
    function todayJalali() {
        const d = new Date();
        const [jy, jm, jd] = toJalali(d.getFullYear(), d.getMonth() + 1, d.getDate());
        return { y: jy, m: jm, d: jd, h: d.getHours(), i: d.getMinutes(), s: d.getSeconds() };
    }

    // ---------- کلاس اصلی ----------
    class JalaliCalendar {
        constructor(input, options = {}) {
            this.input = typeof input === 'string' ? document.querySelector(input) : input;
            if (!this.input) throw new Error('JalaliCalendar: input not found');

            this.options = Object.assign({
                withTime: false,
                withSeconds: false,
                value: null,
                onChange: null,
                persianDigits: true,
                placement: 'auto',
            }, options);

            // مقداردهی
            const val = this.options.value;
            if (val && typeof val === 'object' && val.y) {
                this.selected = val;
            } else if (typeof val === 'string' && val) {
                this.selected = this._parseValue(val);
            } else {
                this.selected = null;
            }

            const today = todayJalali();
            this.viewYear = this.selected?.y ?? today.y;
            this.viewMonth = this.selected?.m ?? today.m;

            this._build();
            this._bind();
            this._render();

            // مقدار اولیه در input
            if (this.selected) {
                this.input.value = this._formatDisplay(this.selected);
            }
        }

        // ---------- ساخت DOM ----------
        _build() {
            this.popover = document.createElement('div');
            this.popover.className = 'jalali-calendar-popover';
            this.popover.dir = 'rtl';
            this.popover.style.cssText = `
                position: absolute; z-index: 99999; background: white;
                border: 1px solid #e5e7eb; border-radius: 12px; padding: 12px;
                box-shadow: 0 10px 40px rgba(0,0,0,0.12); width: 300px;
                font-family: inherit; display: none; user-select: none;
                color: #111827;
            `;

            const parent = this.input.parentElement;
            if (getComputedStyle(parent).position === 'static') {
                parent.style.position = 'relative';
            }
            parent.appendChild(this.popover);
        }

        _bind() {
            this._onInputClick = (e) => { e.stopPropagation(); this.toggle(); };
            this._onDocClick = (e) => {
                if (!this.popover.contains(e.target) && e.target !== this.input) {
                    this.close();
                }
            };
            this._onKey = (e) => {
                if (e.key === 'Escape') this.close();
            };

            this.input.addEventListener('click', this._onInputClick);
            this.input.addEventListener('focus', this._onInputClick);
            document.addEventListener('click', this._onDocClick);
            document.addEventListener('keydown', this._onKey);
        }

        // ---------- رندر ----------
        _render() {
            const days = daysInJalaliMonth(this.viewYear, this.viewMonth);
            // روز اول ماه در تقویم میلادی → روز هفته
            const [gy, gm, gd] = toGregorian(this.viewYear, this.viewMonth, 1);
            const firstWeekday = new Date(gy, gm - 1, gd).getDay(); // 0=Sun..6=Sat
            // در تقویم ما شنبه اول هفته است (Sat = 6)
            const startOffset = (firstWeekday + 1) % 7;

            const today = todayJalali();

            let html = '';

            // هدر
            html += `<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:10px;gap:6px">`;
            html += `<button type="button" data-nav="prev-year" class="jc-btn">‹‹</button>`;
            html += `<button type="button" data-nav="prev-month" class="jc-btn">‹</button>`;
            html += `<div style="flex:1;text-align:center;font-weight:700;font-size:14px">
                        ${MONTH_NAMES[this.viewMonth - 1]} ${this._fa(this.viewYear)}
                     </div>`;
            html += `<button type="button" data-nav="next-month" class="jc-btn">›</button>`;
            html += `<button type="button" data-nav="next-year" class="jc-btn">››</button>`;
            html += `</div>`;

            // روزهای هفته
            html += `<div class="jc-weekdays">`;
            WEEKDAYS_SHORT.forEach(d => { html += `<div>${d}</div>`; });
            html += `</div>`;

            // روزها
            html += `<div class="jc-days">`;
            for (let i = 0; i < startOffset; i++) html += `<div></div>`;
            for (let d = 1; d <= days; d++) {
                const isSelected = this.selected
                    && this.selected.y === this.viewYear
                    && this.selected.m === this.viewMonth
                    && this.selected.d === d;
                const isToday = today.y === this.viewYear && today.m === this.viewMonth && today.d === d;
                const isFriday = ((startOffset + d - 1) % 7) === 6;

                let cls = 'jc-day';
                if (isSelected) cls += ' jc-day-selected';
                else if (isToday) cls += ' jc-day-today';
                if (isFriday && !isSelected) cls += ' jc-day-friday';

                html += `<button type="button" class="${cls}" data-day="${d}">${this._fa(d)}</button>`;
            }
            html += `</div>`;

            // زمان
            if (this.options.withTime) {
                const s = this.selected ?? { h: 0, i: 0, s: 0 };
                html += `<div class="jc-time">`;
                html += `<input type="text" inputmode="numeric" maxlength="2" data-time="h" value="${pad(s.h || 0)}" placeholder="HH">`;
                html += `<span>:</span>`;
                html += `<input type="text" inputmode="numeric" maxlength="2" data-time="i" value="${pad(s.i || 0)}" placeholder="MM">`;
                if (this.options.withSeconds) {
                    html += `<span>:</span>`;
                    html += `<input type="text" inputmode="numeric" maxlength="2" data-time="s" value="${pad(s.s || 0)}" placeholder="SS">`;
                }
                html += `</div>`;
            }

            // پاورقی
            html += `<div class="jc-footer">
                        <button type="button" data-action="today" class="jc-btn-secondary">امروز</button>
                        <button type="button" data-action="clear" class="jc-btn-secondary">پاک</button>
                     </div>`;

            this.popover.innerHTML = `<style>
                .jc-btn { padding: 4px 8px; border-radius: 6px; border: 1px solid #e5e7eb; background: white; cursor: pointer; font-family: inherit; font-size: 14px; line-height: 1; color: #374151; }
                .jc-btn:hover { background: #f3f4f6; }
                .jc-btn-secondary { flex: 1; padding: 6px; border-radius: 6px; border: 1px solid #e5e7eb; background: white; cursor: pointer; font-family: inherit; font-size: 12px; color: #374151; }
                .jc-btn-secondary:hover { background: #f3f4f6; }
                .jc-weekdays { display: grid; grid-template-columns: repeat(7, 1fr); gap: 2px; text-align: center; font-size: 11px; color: #6b7280; margin-bottom: 6px; }
                .jc-days { display: grid; grid-template-columns: repeat(7, 1fr); gap: 2px; }
                .jc-day { padding: 6px 0; border-radius: 6px; border: none; background: transparent; cursor: pointer; font-family: inherit; font-size: 13px; color: #111827; transition: background 0.1s; }
                .jc-day:hover { background: #f3f4f6; }
                .jc-day-today { background: #f0f9ff; color: #0369a1; font-weight: 700; }
                .jc-day-selected { background: #0ea5e9; color: white; font-weight: 700; }
                .jc-day-selected:hover { background: #0284c7; }
                .jc-day-friday { color: #dc2626; }
                .jc-day-friday.jc-day-selected { color: white; }
                .jc-time { display: flex; gap: 6px; margin-top: 10px; justify-content: center; align-items: center; border-top: 1px solid #e5e7eb; padding-top: 10px; }
                .jc-time input { width: 42px; padding: 4px; border: 1px solid #e5e7eb; border-radius: 6px; text-align: center; font-family: inherit; font-size: 13px; }
                .jc-footer { display: flex; gap: 6px; margin-top: 10px; }
                .jalali-calendar-popover { animation: jc-fade 0.15s ease-out; }
                @keyframes jc-fade { from { opacity: 0; transform: translateY(-4px); } to { opacity: 1; transform: translateY(0); } }
            </style>` + html;

            this._attachEvents();
        }

        _attachEvents() {
            // ناوبری
            this.popover.querySelectorAll('[data-nav]').forEach(btn => {
                btn.addEventListener('click', (e) => {
                    e.stopPropagation();
                    const dir = btn.dataset.nav;
                    if (dir === 'prev-month') this._changeMonth(-1);
                    else if (dir === 'next-month') this._changeMonth(1);
                    else if (dir === 'prev-year') { this.viewYear--; this._render(); }
                    else if (dir === 'next-year') { this.viewYear++; this._render(); }
                });
            });

            // انتخاب روز
            this.popover.querySelectorAll('[data-day]').forEach(btn => {
                btn.addEventListener('click', (e) => {
                    e.stopPropagation();
                    const d = parseInt(btn.dataset.day);
                    if (!this.selected) this.selected = { y: this.viewYear, m: this.viewMonth, d, h: 0, i: 0, s: 0 };
                    else { this.selected.y = this.viewYear; this.selected.m = this.viewMonth; this.selected.d = d; }
                    this._emit();
                    this._render();
                });
            });

            // زمان
            this.popover.querySelectorAll('[data-time]').forEach(inp => {
                inp.addEventListener('change', (e) => {
                    e.stopPropagation();
                    if (!this.selected) {
                        this.selected = { y: this.viewYear, m: this.viewMonth, d: 1, h: 0, i: 0, s: 0 };
                    }
                    const k = inp.dataset.time;
                    let v = parseInt(toLatinDigits(inp.value)) || 0;
                    v = Math.max(0, Math.min(k === 'h' ? 23 : 59, v));
                    this.selected[k] = v;
                    inp.value = pad(v);
                    this._emit();
                });
                inp.addEventListener('click', e => e.stopPropagation());
            });

            // اکشن‌ها
            const todayBtn = this.popover.querySelector('[data-action="today"]');
            if (todayBtn) todayBtn.addEventListener('click', (e) => {
                e.stopPropagation();
                const t = todayJalali();
                this.selected = { y: t.y, m: t.m, d: t.d, h: 0, i: 0, s: 0 };
                this.viewYear = t.y; this.viewMonth = t.m;
                this._emit(); this._render();
            });

            const clearBtn = this.popover.querySelector('[data-action="clear"]');
            if (clearBtn) clearBtn.addEventListener('click', (e) => {
                e.stopPropagation();
                this.selected = null;
                this.input.value = '';
                this._emit(); this._render();
            });
        }

        // ---------- API ----------
        _changeMonth(delta) {
            let m = this.viewMonth + delta;
            let y = this.viewYear;
            while (m < 1) { m += 12; y--; }
            while (m > 12) { m -= 12; y++; }
            this.viewMonth = m;
            this.viewYear = y;
            this._render();
        }

        _fa(v) {
            return this.options.persianDigits ? toPersianDigits(v) : String(v);
        }

        _formatDisplay(s) {
            let out = `${this._fa(s.y)}/${this._fa(pad(s.m))}/${this._fa(pad(s.d))}`;
            if (this.options.withTime) {
                out += ` ${this._fa(pad(s.h || 0))}:${this._fa(pad(s.i || 0))}`;
                if (this.options.withSeconds) out += `:${this._fa(pad(s.s || 0))}`;
            }
            return out;
        }

        _parseValue(str) {
            const clean = toLatinDigits(str).replace(/[-.]/g, '/');
            const m = clean.match(/^(\d{4})\/(\d{1,2})\/(\d{1,2})(?:\s+(\d{1,2}):(\d{1,2})(?::(\d{1,2}))?)?$/);
            if (!m) return null;
            return { y: +m[1], m: +m[2], d: +m[3], h: +(m[4]||0), i: +(m[5]||0), s: +(m[6]||0) };
        }

        _emit() {
            if (this.selected) {
                this.input.value = this._formatDisplay(this.selected);
            }
            if (typeof this.options.onChange === 'function') {
                this.options.onChange(this.selected, this);
            }
        }

        // ---------- Public ----------
        open() { this.popover.style.display = 'block'; }
        close() { this.popover.style.display = 'none'; }
        toggle() { this.popover.style.display === 'none' ? this.open() : this.close(); }
        getValue() { return this.selected; }
        setValue(v) {
            if (v && typeof v === 'object') this.selected = v;
            else if (typeof v === 'string') this.selected = this._parseValue(v);
            else this.selected = null;
            if (this.selected) {
                this.viewYear = this.selected.y;
                this.viewMonth = this.selected.m;
                this.input.value = this._formatDisplay(this.selected);
            } else {
                this.input.value = '';
            }
            this._render();
        }
        destroy() {
            this.input.removeEventListener('click', this._onInputClick);
            this.input.removeEventListener('focus', this._onInputClick);
            document.removeEventListener('click', this._onDocClick);
            document.removeEventListener('keydown', this._onKey);
            this.popover.remove();
        }
    }

    // ---------- Public API ----------
    global.JalaliCalendar = JalaliCalendar;
    global.JalaliCalendarUtil = {
        toJalali, toGregorian, isLeapJalali, daysInJalaliMonth,
        todayJalali, toPersianDigits, toLatinDigits, MONTH_NAMES,
    };

})(window);