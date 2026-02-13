// src/utils/utm.js

const COOKIE_NAME = 'utm_params';
const COOKIE_DAYS = 90;

export const UTM_KEYS = [
    'utm_source',
    'utm_medium',
    'utm_campaign',
    'utm_term',
    'utm_content',
    'utm_id',
    'utm_source_platform',
    'utm_creative_format',
    'utm_marketing_tactic',
];

function setCookie(name, value, days) {
    let expires = '';
    if (typeof days === 'number') {
        const date = new Date();
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
    const nameEQ = name + '=';
    const ca = document.cookie.split(';');
    for (let i = 0; i < ca.length; i++) {
        let c = ca[i];
        while (c.charAt(0) === ' ') c = c.substring(1);
        if (c.indexOf(nameEQ) === 0) return decodeURIComponent(c.substring(nameEQ.length));
    }
    return null;
}

function safeJsonParse(str) {
    try { return JSON.parse(str); } catch { return null; }
}

export function loadUtmFromCookie() {
    const raw = getCookie(COOKIE_NAME);
    if (!raw) return {};
    const obj = safeJsonParse(raw);
    return (obj && typeof obj === 'object') ? obj : {};
}

function saveUtmToCookie(obj) {
    setCookie(COOKIE_NAME, JSON.stringify(obj), COOKIE_DAYS);
}

function readUtmFromUrl() {
    const params = new URLSearchParams(window.location.search || '');
    const found = {};
    let hasAny = false;

    for (const key of UTM_KEYS) {
        if (params.has(key)) {
            const val = (params.get(key) || '').trim();
            if (val) {
                found[key] = val;
                hasAny = true;
            }
        }
    }
    return hasAny ? found : null;
}

function mergeOverwrite(oldObj, newObj) {
    const merged = { ...oldObj };
    for (const k in newObj) merged[k] = newObj[k];
    merged.__ts = Date.now();
    return merged;
}

/**
 * Вызывай один раз при старте сайта (на каждой странице ок):
 * - если в URL есть UTM -> перезапишет cookie (поверх старых)
 */
export function captureUtmToCookieFromUrl() {
    const fromUrl = readUtmFromUrl();
    if (!fromUrl) return false;

    const fromCookie = loadUtmFromCookie();
    const merged = mergeOverwrite(fromCookie, fromUrl);
    saveUtmToCookie(merged);
    return true;
}

function ensureHiddenInput(form, name) {
    let el = form.querySelector(`input[name="${CSS.escape(name)}"]`);
    if (el) return el;

    el = document.createElement('input');
    el.type = 'hidden';
    el.name = name;
    form.appendChild(el);
    return el;
}

/**
 * Главная функция для твоей отправки:
 * гарантирует, что UTM в форме существуют как hidden input'ы.
 */
export function applyUtmToForm(form) {
    if (!form) return;

    const utm = loadUtmFromCookie();
    if (!utm || Object.keys(utm).length === 0) return;

    for (const key of UTM_KEYS) {
        if (utm[key]) {
            const input = ensureHiddenInput(form, key);
            input.value = utm[key];
        }
    }
}
