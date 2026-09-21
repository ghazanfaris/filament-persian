/**
 * تبدیل خودکار اعداد لاتین به فارسی در سراسر پنل.
 * با MutationObserver کار می‌کند تا محتوای داینامیک Livewire را هم پوشش دهد.
 */
(function () {
    'use strict';

    const map = {
        '0': '۰', '1': '۱', '2': '۲', '3': '۳', '4': '۴',
        '5': '۵', '6': '۶', '7': '۷', '8': '۸', '9': '۹',
    };

    const regex = /[0-9]/g;

    function convertNode(node) {
        if (node.nodeType === Node.TEXT_NODE) {
            const text = node.nodeValue;
            // اگر داخل input/textarea/script/style هستیم، دست نزن
            const parent = node.parentElement;
            if (! parent) return;
            const tag = parent.tagName;
            if (tag === 'INPUT' || tag === 'TEXTAREA' || tag === 'SCRIPT' || tag === 'STYLE' || tag === 'CODE') {
                return;
            }
            // اگر data-no-persian دارد، دست نزن
            if (parent.closest('[data-no-persian]')) {
                return;
            }
            if (regex.test(text)) {
                node.nodeValue = text.replace(regex, d => map[d]);
            }
        } else if (node.nodeType === Node.ELEMENT_NODE) {
            // اگر عنصر جدید اضافه شده، فرزندانش را پیمایش کن
            node.childNodes.forEach(convertNode);
        }
    }

    function walkAndConvert(root) {
        if (! root) return;
        convertNode(root);
        const walker = document.createTreeWalker(
            root,
            NodeFilter.SHOW_TEXT,
            {
                acceptNode: function (node) {
                    const parent = node.parentElement;
                    if (! parent) return NodeFilter.FILTER_REJECT;
                    const tag = parent.tagName;
                    if (tag === 'INPUT' || tag === 'TEXTAREA' || tag === 'SCRIPT' || tag === 'STYLE' || tag === 'CODE') {
                        return NodeFilter.FILTER_REJECT;
                    }
                    if (parent.closest('[data-no-persian]')) {
                        return NodeFilter.FILTER_REJECT;
                    }
                    return regex.test(node.nodeValue)
                        ? NodeFilter.FILTER_ACCEPT
                        : NodeFilter.FILTER_REJECT;
                },
            }
        );

        const nodes = [];
        while (walker.nextNode()) {
            nodes.push(walker.currentNode);
        }
        nodes.forEach(convertNode);
    }

    function startObserver() {
        walkAndConvert(document.body);

        const observer = new MutationObserver(mutations => {
            mutations.forEach(mutation => {
                mutation.addedNodes.forEach(node => {
                    if (node.nodeType === Node.ELEMENT_NODE || node.nodeType === Node.TEXT_NODE) {
                        walkAndConvert(node.nodeType === Node.ELEMENT_NODE ? node : node.parentNode);
                    }
                });
                if (mutation.type === 'characterData') {
                    convertNode(mutation.target);
                }
            });
        });

        observer.observe(document.body, {
            childList: true,
            subtree: true,
            characterData: true,
        });
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', startObserver);
    } else {
        startObserver();
    }

    // در دسترس برای Livewire
    window.FilamentPersianNumbers = { convert: walkAndConvert };
})();