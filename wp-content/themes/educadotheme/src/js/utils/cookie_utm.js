// src/js/utils/cookie_utm.js

const COOKIE_NAME = 'utm_params'
const COOKIE_DAYS = 90

const UTM_KEYS = [
    'utm_source',
    'utm_medium',
    'utm_campaign',
    'utm_term',
    'utm_content',
    'utm_id',
    'utm_source_platform',
    'utm_creative_format',
    'utm_marketing_tactic',
]

// ====== helpers: safe escape for selector ======
function escapeName(name) {
    if (window.CSS && typeof window.CSS.escape === 'function') return window.CSS.escape(name)
    return String(name).replace(/["\\]/g, '\\$&')
}

// ====== helpers: cookies ======
function setCookie(name, value, days) {
    let expires = ''
    if (typeof days === 'number') {
        const date = new Date()
        date.setTime(date.getTime() + days * 24 * 60 * 60 * 1000)
        expires = '; expires=' + date.toUTCString()
    }
    document.cookie =
        name + '=' + encodeURIComponent(value) +
        expires +
        '; path=/' +
        '; SameSite=Lax'
}

function getCookie(name) {
    const nameEQ = name + '='
    const ca = document.cookie.split(';')
    for (let i = 0; i < ca.length; i++) {
        let c = ca[i]
        while (c.charAt(0) === ' ') c = c.substring(1)
        if (c.indexOf(nameEQ) === 0) return decodeURIComponent(c.substring(nameEQ.length))
    }
    return null
}

function safeJsonParse(str) {
    try { return JSON.parse(str) } catch { return null }
}

// ====== helpers: query params ======
function readUtmFromUrl() {
    const params = new URLSearchParams(window.location.search || '')
    const found = {}
    let hasAny = false

    for (let i = 0; i < UTM_KEYS.length; i++) {
        const key = UTM_KEYS[i]
        if (params.has(key)) {
            const val = (params.get(key) || '').trim()
            if (val !== '') {
                found[key] = val
                hasAny = true
            }
        }
    }
    return hasAny ? found : null
}

export function loadUtmFromCookie() {
    const raw = getCookie(COOKIE_NAME)
    if (!raw) return {}
    const obj = safeJsonParse(raw)
    return (obj && typeof obj === 'object') ? obj : {}
}

function saveUtmToCookie(obj) {
    setCookie(COOKIE_NAME, JSON.stringify(obj), COOKIE_DAYS)
}

// ====== merge logic ======
function mergeOverwrite(oldObj, newObj) {
    const merged = {}
    for (const k in oldObj) if (Object.prototype.hasOwnProperty.call(oldObj, k)) merged[k] = oldObj[k]
    for (const n in newObj) if (Object.prototype.hasOwnProperty.call(newObj, n)) merged[n] = newObj[n]
    merged.__ts = Date.now()
    return merged
}

// ====== form fill ======
function ensureHiddenInput(form, name) {
    const selector = 'input[name="' + escapeName(name) + '"]'
    let el = form.querySelector(selector)
    if (el) return el

    el = document.createElement('input')
    el.type = 'hidden'
    el.name = name
    form.appendChild(el)
    return el
}

function shouldTouchForm(form) {
    if (form.hasAttribute('data-utm-ignore')) return false
    if (!form.querySelector('input, textarea, select')) return false
    return true
}

function fillFormWithUtm(form, utmObj) {
    if (!form || !utmObj) return
    if (!shouldTouchForm(form)) return

    for (let i = 0; i < UTM_KEYS.length; i++) {
        const key = UTM_KEYS[i]
        if (utmObj[key]) {
            const input = ensureHiddenInput(form, key)
            input.value = utmObj[key]
        }
    }
}

function fillAllForms() {
    const utm = loadUtmFromCookie()
    if (!utm || Object.keys(utm).length === 0) return

    const forms = document.querySelectorAll('form')
    for (let i = 0; i < forms.length; i++) {
        fillFormWithUtm(forms[i], utm)
    }
}

// ✅ 1) сохраняем UTM из URL в cookie (перетираем старые)
export function cookieutm() {
    const fromUrl = readUtmFromUrl()
    if (!fromUrl) return false

    const fromCookie = loadUtmFromCookie()
    const merged = mergeOverwrite(fromCookie, fromUrl)
    saveUtmToCookie(merged)
    return true
}

// ✅ 2) экспорт, чтобы твой form.js мог гарантированно вставить utm_* перед сбором полей
export function applyUtmToForm(form) {
    const utm = loadUtmFromCookie()
    if (!utm || Object.keys(utm).length === 0) return
    fillFormWithUtm(form, utm)
}

// ✅ 3) если хочешь полностью поведение “как было в IIFE”: автозаполнение + submit + MutationObserver
export function initUtmAutofill() {
    // заполнить формы на загрузке
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', fillAllForms)
    } else {
        fillAllForms()
    }

    // перед submit — ещё раз
    document.addEventListener('submit', function (e) {
        const form = e.target
        if (!form || form.tagName !== 'FORM') return
        const utm = loadUtmFromCookie()
        fillFormWithUtm(form, utm)
    }, true)

    // формы после AJAX/попапов
    const mo = new MutationObserver(function (mutations) {
        const utm = loadUtmFromCookie()
        if (!utm || Object.keys(utm).length === 0) return

        for (let i = 0; i < mutations.length; i++) {
            const m = mutations[i]
            if (!m.addedNodes) continue

            for (let j = 0; j < m.addedNodes.length; j++) {
                const node = m.addedNodes[j]
                if (!node || node.nodeType !== 1) continue

                if (node.tagName === 'FORM') {
                    fillFormWithUtm(node, utm)
                } else if (node.querySelectorAll) {
                    const innerForms = node.querySelectorAll('form')
                    for (let f = 0; f < innerForms.length; f++) {
                        fillFormWithUtm(innerForms[f], utm)
                    }
                }
            }
        }
    })

    mo.observe(document.documentElement, { childList: true, subtree: true })
}
