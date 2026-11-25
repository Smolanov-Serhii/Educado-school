(function () {
    'use strict';
    var market = document.querySelector('[data-market]');
    if (!market) return;
    var reducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)');
    var header = document.querySelector('.ed-header');
    function syncHeaderSpacing() {
        if (!header) return;
        var height = header.getBoundingClientRect().height;
        market.style.setProperty('--market-header-offset', (height + 20) + 'px');
        market.style.setProperty('--market-top-space', getComputedStyle(header).position === 'fixed' ? height + 'px' : '0px');
    }
    syncHeaderSpacing();
    if (header && 'ResizeObserver' in window) new ResizeObserver(syncHeaderSpacing).observe(header);
    window.addEventListener('resize', syncHeaderSpacing, { passive: true });
    var nav = Array.from(market.querySelectorAll('.market-nav a'));
    function activate(id) {
        nav.forEach(function (link) {
            if (link.hash === '#' + id) link.setAttribute('aria-current', 'location');
            else link.removeAttribute('aria-current');
        });
    }
    nav.forEach(function (link) {
        link.addEventListener('click', function (event) {
            var target = document.getElementById(link.hash.slice(1));
            if (!target || event.ctrlKey || event.metaKey || event.shiftKey || event.altKey) return;
            event.preventDefault();
            activate(target.id);
            target.scrollIntoView({ behavior: reducedMotion.matches ? 'instant' : 'smooth', block: 'start' });
            window.history.replaceState(null, '', link.hash);
        });
    });
    if ('IntersectionObserver' in window) {
        var sectionObserver = new IntersectionObserver(function (entries) {
            entries.forEach(function (entry) { if (entry.isIntersecting) activate(entry.target.id); });
        }, { rootMargin: '-18% 0px -60% 0px' });
        market.querySelectorAll('.market-group').forEach(function (section) { sectionObserver.observe(section); });
        if (!reducedMotion.matches) {
            market.classList.add('ed-market--motion');
            var cardObserver = new IntersectionObserver(function (entries) {
                entries.forEach(function (entry) {
                    if (entry.isIntersecting) { entry.target.classList.add('is-visible'); cardObserver.unobserve(entry.target); }
                });
            }, { threshold: 0.05 });
            market.querySelectorAll('[data-market-card]').forEach(function (card) { cardObserver.observe(card); });
            reducedMotion.addEventListener('change', function () { if (reducedMotion.matches) market.classList.remove('ed-market--motion'); });
        }
    }
    var activeAnimations = new WeakMap();
    function setOpen(detail, open) {
        var from = detail.getBoundingClientRect().height;
        var active = activeAnimations.get(detail);
        if (active) active.cancel();
        detail.dataset.targetOpen = String(open);
        if (reducedMotion.matches || !detail.animate) { detail.open = open; detail.style.overflow = ''; return; }
        if (open) detail.open = true;
        var style = getComputedStyle(detail);
        var borders = parseFloat(style.borderTopWidth) + parseFloat(style.borderBottomWidth);
        var closedHeight = detail.querySelector('summary').getBoundingClientRect().height + parseFloat(style.paddingTop) + parseFloat(style.paddingBottom) + borders;
        var to = open ? detail.scrollHeight + borders : closedHeight;
        detail.style.overflow = 'hidden';
        var animation = detail.animate([{ height: from + 'px' }, { height: to + 'px' }], { duration: 240, easing: 'cubic-bezier(.2,.65,.3,1)' });
        activeAnimations.set(detail, animation);
        animation.onfinish = function () { detail.open = open; detail.style.overflow = ''; activeAnimations.delete(detail); };
    }
    market.querySelectorAll('[data-market-faq]').forEach(function (detail) {
        detail.querySelector('summary').addEventListener('click', function (event) {
            event.preventDefault();
            var open = detail.dataset.targetOpen ? detail.dataset.targetOpen !== 'true' : !detail.open;
            if (open) market.querySelectorAll('[data-market-faq][open]').forEach(function (other) { if (other !== detail) setOpen(other, false); });
            setOpen(detail, open);
        });
    });
})();
