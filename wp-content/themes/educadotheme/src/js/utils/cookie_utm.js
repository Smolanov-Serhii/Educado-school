(function () {
    // ====== настройки ======
    var COOKIE_NAME = 'utm_params';
    var COOKIE_DAYS = 90;

    var UTM_KEYS = [
        'utm_source',
        'utm_medium',
        'utm_campaign',
        'utm_term',
        'utm_content',
        'utm_id',
        'utm_source_platform',
        'utm_creative_format',
        'utm_marketing_tactic'
    ];

    // ====== helpers: safe escape for selector ======
    function escapeName(name) {
        // CSS.escape может отсутствовать
        if (window.CSS && typeof window.CSS.escape === 'function') {
            return window.CSS.escape(name);
        }
        // простая безопасная замена для атрибута name
        return String(name).replace(/["\\]/g, '\\$&');
    }

    // ====== helpers: cookies ======
    function setCookie(name, value, days) {
        var expires = '';
        if (typeof days === 'number') {
            var date = new Date();
            date.setTime(date.getTime() + days * 24 * 60 * 60 * 1000);
            expires = '; expires=' + date.toUTCString();
        }
        document.cookie =
            name + '=' + encodeURIComponent(value) +
            expires +
            '; path=/' +
            '; SameSite=Lax';
    }

    function getCookie(name) {
        var nameEQ = name + '=';
        var ca = document.cookie.split(';');
        for (var i = 0; i < ca.length; i++) {
            var c = ca[i];
            while (c.charAt(0) === ' ') c = c.substring(1, c.length);
            if (c.indexOf(nameEQ) === 0) return decodeURIComponent(c.substring(nameEQ.length, c.length));
        }
        return null;
    }

    function safeJsonParse(str) {
        try { return JSON.parse(str); } catch (e) { return null; }
    }

    // ====== helpers: query params ======
    function readUtmFromUrl() {
        var params = new URLSearchParams(window.location.search || '');
        var found = {};
        var hasAny = false;

        for (var i = 0; i < UTM_KEYS.length; i++) {
            var key = UTM_KEYS[i];
            if (params.has(key)) {
                var val = (params.get(key) || '').trim();
                if (val !== '') {
                    found[key] = val;
                    hasAny = true;
                }
            }
        }
        return hasAny ? found : null;
    }

    function loadUtmFromCookie() {
        var raw = getCookie(COOKIE_NAME);
        if (!raw) return {};
        var obj = safeJsonParse(raw);
        return (obj && typeof obj === 'object') ? obj : {};
    }

    function saveUtmToCookie(obj) {
        setCookie(COOKIE_NAME, JSON.stringify(obj), COOKIE_DAYS);
    }

    // ====== merge logic ======
    function mergeOverwrite(oldObj, newObj) {
        var merged = {};
        for (var k in oldObj) if (Object.prototype.hasOwnProperty.call(oldObj, k)) merged[k] = oldObj[k];
        for (var n in newObj) if (Object.prototype.hasOwnProperty.call(newObj, n)) merged[n] = newObj[n];
        merged.__ts = Date.now();
        return merged;
    }

    // ====== form fill ======
    function ensureHiddenInput(form, name) {
        var selector = 'input[name="' + escapeName(name) + '"]';
        var el = form.querySelector(selector);
        if (el) return el;

        el = document.createElement('input');
        el.type = 'hidden';
        el.name = name;
        form.appendChild(el);
        return el;
    }

    function shouldTouchForm(form) {
        // Не трогаем формы, где явно отключили UTM
        if (form.hasAttribute('data-utm-ignore')) return false;
        // Если в форме вообще нет полей — нет смысла
        if (!form.querySelector('input, textarea, select')) return false;
        return true;
    }

    function fillFormWithUtm(form, utmObj) {
        if (!form || !utmObj) return;
        if (!shouldTouchForm(form)) return;

        for (var i = 0; i < UTM_KEYS.length; i++) {
            var key = UTM_KEYS[i];
            if (utmObj[key]) {
                var input = ensureHiddenInput(form, key);
                input.value = utmObj[key];
            }
        }
    }

    function fillAllForms() {
        var utm = loadUtmFromCookie();
        if (!utm || Object.keys(utm).length === 0) return;

        var forms = document.querySelectorAll('form');
        for (var i = 0; i < forms.length; i++) {
            fillFormWithUtm(forms[i], utm);
        }
    }

    // ====== init ======
    // 1) Если в URL есть UTM — сохранить (перетереть старые)
    var fromUrl = readUtmFromUrl();
    if (fromUrl) {
        var fromCookie = loadUtmFromCookie();
        var merged = mergeOverwrite(fromCookie, fromUrl);
        saveUtmToCookie(merged);
    }

    // 2) Заполнить формы из cookie на загрузке
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', fillAllForms);
    } else {
        fillAllForms();
    }

    // 3) Перед отправкой любой формы — ещё раз проставить
    document.addEventListener('submit', function (e) {
        var form = e.target;
        if (!form || form.tagName !== 'FORM') return;
        var utm = loadUtmFromCookie();
        fillFormWithUtm(form, utm);
    }, true);

    // 4) На случай если формы появляются после AJAX
    var mo = new MutationObserver(function (mutations) {
        // читаем cookie один раз на пачку мутаций
        var utm = loadUtmFromCookie();
        if (!utm || Object.keys(utm).length === 0) return;

        for (var i = 0; i < mutations.length; i++) {
            var m = mutations[i];
            if (!m.addedNodes) continue;

            for (var j = 0; j < m.addedNodes.length; j++) {
                var node = m.addedNodes[j];
                if (!node || node.nodeType !== 1) continue;

                if (node.tagName === 'FORM') {
                    fillFormWithUtm(node, utm);
                } else if (node.querySelectorAll) {
                    var innerForms = node.querySelectorAll('form');
                    for (var f = 0; f < innerForms.length; f++) {
                        fillFormWithUtm(innerForms[f], utm);
                    }
                }
            }
        }
    });

    mo.observe(document.documentElement, { childList: true, subtree: true });

})();
