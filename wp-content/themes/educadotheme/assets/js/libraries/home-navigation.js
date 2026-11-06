(function () {
    'use strict';

    var hashes = ['#ed-teachers', '#ed-testimonials'];

    function scrollToSection(hash, behavior) {
        if (hashes.indexOf(hash) === -1) return;
        var section = document.getElementById(hash.slice(1));
        if (!section) return;

        var header = document.querySelector('.ed-header');
        if (header) header.classList.remove('--menu-open');
        document.documentElement.classList.remove('noscroll');
        document.body.classList.remove('noscroll');

        var offset = header ? header.getBoundingClientRect().height : 0;
        window.scrollTo({
            top: Math.max(0, window.pageYOffset + section.getBoundingClientRect().top - offset - 16),
            behavior: behavior
        });
    }

    document.addEventListener('click', function (event) {
        if (event.defaultPrevented || event.button !== 0 || event.ctrlKey || event.metaKey || event.shiftKey || event.altKey) return;
        var link = event.target.closest('a[href]');
        if (!link || (link.target && link.target !== '_self')) return;

        var destination = new URL(link.href, window.location.href);
        if (destination.origin !== window.location.origin || destination.pathname !== window.location.pathname || hashes.indexOf(destination.hash) === -1) return;
        if (!document.getElementById(destination.hash.slice(1))) return;

        event.preventDefault();
        if (destination.href !== window.location.href) window.history.pushState(null, '', destination.href);
        var reducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
        scrollToSection(destination.hash, reducedMotion ? 'auto' : 'smooth');
    });

    function scrollAfterLoad() {
        if (hashes.indexOf(window.location.hash) === -1) return;
        var fontsReady = document.fonts ? document.fonts.ready : Promise.resolve();
        fontsReady.then(function () {
            window.requestAnimationFrame(function () {
                window.requestAnimationFrame(function () {
                    scrollToSection(window.location.hash, 'auto');
                });
            });
        });
    }

    if (document.readyState === 'complete') scrollAfterLoad();
    else window.addEventListener('load', scrollAfterLoad, { once: true });

    window.addEventListener('hashchange', function () {
        scrollToSection(window.location.hash, 'auto');
    });
}());
