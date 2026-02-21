import { EnableScroll } from '../utils/scroll.js'
import { applyUtmToForm } from './cookie_utm.js'

/** ===== Analytics helpers ===== */
const pushToDataLayer = (payload) => {
    if (window.dataLayer && typeof window.dataLayer.push === 'function') {
        window.dataLayer.push(payload)
    }
}

const getUtmFromForm = (form) => {
    const get = (name) => (form.querySelector(`input[name="${name}"]`)?.value || '').trim()
    return {
        utm_source: get('utm_source'),
        utm_medium: get('utm_medium'),
        utm_campaign: get('utm_campaign'),
        utm_content: get('utm_content'),
        utm_term: get('utm_term'),
        utm_id: get('utm_id'),
        utm_source_platform: get('utm_source_platform'),
        utm_creative_format: get('utm_creative_format'),
        utm_marketing_tactic: get('utm_marketing_tactic'),
    }
}

const getFormName = (form) =>
    form.getAttribute('data-form') ||
    form.getAttribute('name') ||
    form.getAttribute('id') ||
    ''

const fireFormEvent = (form, type) => {
    // type: 'start' | 'submit_attempt' | 'submit_success'
    const utm = getUtmFromForm(form)
    const formName = getFormName(form)

    // ===== GTM / GA4 via dataLayer =====
    if (type === 'start') {
        pushToDataLayer({ event: 'form_start', form_name: formName, ...utm })
    }
    if (type === 'submit_attempt') {
        pushToDataLayer({ event: 'form_submit', form_name: formName, ...utm })
    }
    if (type === 'submit_success') {
        pushToDataLayer({ event: 'generate_lead', form_name: formName, ...utm })
    }

    // ===== Meta Pixel =====
    // "Работа с формой" -> кастомное событие
    if (type === 'start' && window.fbq) {
        try { window.fbq('trackCustom', 'FormStart', { form_name: formName, ...utm }) } catch (e) {}
    }
    // успех -> стандартный Lead
    if (type === 'submit_success' && window.fbq) {
        try { window.fbq('track', 'Lead', { form_name: formName, ...utm }) } catch (e) {}
    }

    // ===== TikTok =====
    if (type === 'start' && window.ttq) {
        try { window.ttq.track('FormStart', { form_name: formName, ...utm }) } catch (e) {}
    }
    if (type === 'submit_success' && window.ttq) {
        try { window.ttq.track('SubmitForm', { form_name: formName, ...utm }) } catch (e) {}
    }

    // ===== gtag direct (если используется) =====
    if (window.gtag) {
        try {
            if (type === 'start') window.gtag('event', 'form_start', { form_name: formName, ...utm })
            if (type === 'submit_attempt') window.gtag('event', 'form_submit', { form_name: formName, ...utm })
            if (type === 'submit_success') window.gtag('event', 'generate_lead', { form_name: formName, ...utm })
        } catch (e) {}
    }
}

/** ===== UI helpers ===== */
const showSuccess = (form) => {
    const popup = form.closest('.popup')
    const success = document.querySelector('.popup#success-popup')
    const fields = form.querySelectorAll('.form-row__input, .form-row__textarea')

    popup && popup.classList.remove('open')
    success && success.classList.add('open')

    EnableScroll()

    fields.forEach((field) => (field.value = ''))

    setTimeout(() => success && success.classList.remove('open'), 5000)
}

const showError = () => {
    alert('Помилка відправки форми. Спробуйте ще раз.')
    EnableScroll()
}

/** ===== AJAX send (success matters) ===== */
const formAjaxSend = (form) => {
    applyUtmToForm(form)

    const actionInput = form.querySelector('input[name="action"]')
    const urlInput = form.querySelector('input[name="url"]')
    const fields = form.querySelectorAll('input, textarea')

    if (!actionInput || !urlInput || !urlInput.value) {
        showError()
        return
    }

    const action = actionInput.value
    const url = urlInput.value + '?action=' + encodeURIComponent(action)
    const dataObj = {}

    fields && fields.length && fields.forEach(field => {
        if (!field.name) return

        if (field.type === 'checkbox') {
            if (field.checked === true) dataObj[field.name] = 'Обрано: <br>'
            return
        }

        if (field.tagName === 'TEXTAREA' || field.type === 'text' || field.type === 'hidden') {
            dataObj[field.name] = (field.value || '').replace(/\n/g, '<br/>')
        }
    })

    const data = Object.keys(dataObj)
        .map(key => encodeURIComponent(key) + '=' + encodeURIComponent(dataObj[key]))
        .join('&')

    fetch(url, {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: data
    })
        .then(async (r) => {
            if (!r.ok) throw new Error('HTTP_' + r.status)

            // поддержим json и текст "1"
            const ct = (r.headers.get('content-type') || '').toLowerCase()
            if (ct.includes('application/json')) return r.json()

            const txt = (await r.text()).trim()
            return (txt === '1' || txt.toLowerCase() === 'true' || txt.toLowerCase() === 'ok')
        })
        .then((resp) => {
            // успех — только если сервер подтвердил
            const ok = resp === true || resp === 1 || resp?.success === true || resp?.result === true || resp?.status === 'ok'
            if (!ok) throw new Error('SERVER_NOT_SUCCESS')

            // ✅ "лид/успех"
            fireFormEvent(form, 'submit_success')
            showSuccess(form)
        })
        .catch(() => {
            showError()
        })
}

export const initForms = () => {
    const forms = document.querySelectorAll('form.form')
    if (!forms || !forms.length) return

    forms.forEach(form => {
        applyUtmToForm(form)

        const fields = form.querySelectorAll('.required')
        const phones = form.querySelectorAll('.required-phone')

        // чтобы form_start улетал только 1 раз на форму
        let started = false
        const markStarted = () => {
            if (started) return
            started = true
            applyUtmToForm(form)
            fireFormEvent(form, 'start')
        }

        // старт работы: первый фокус/ввод
        form.addEventListener('focusin', markStarted, { passive: true })
        form.addEventListener('input', markStarted, { passive: true })

        fields.forEach((field) => {
            field.addEventListener('focus', () => field.parentNode.classList.remove('error'), { passive: true })
        })

        phones.forEach((field) => {
            field.addEventListener('focus', () => field.parentNode.classList.remove('error'), { passive: true })

            field.addEventListener('input', function() {
                this.value = this.value
                    .replace(/[^0-9.]/g, '')
                    .replace(/(\..*?)\..*/g, '$1')
            })
        })

        form.addEventListener('submit', (e) => {
            e.preventDefault()

            let errors = 0
            let phones_count = 0

            fields.forEach((field) => {
                if (field.name === 'phone') {
                    if (field.value.length < 16) {
                        field.parentNode.classList.add('error')
                        errors++
                    } else {
                        field.parentNode.classList.remove('error')
                    }
                } else {
                    if (field.value.length < 3) {
                        field.parentNode.classList.add('error')
                        errors++
                    } else {
                        field.parentNode.classList.remove('error')
                    }
                }
            })

            phones.forEach((phone) => {
                if (phone.value.length) phones_count++
            })

            if (!phones_count) {
                phones.forEach((phone) => phone.parentNode.classList.add('error'))
            }

            if (errors > 0 || !phones_count) return false

            applyUtmToForm(form)
            fireFormEvent(form, 'submit_attempt')

            formAjaxSend(form)
        })
    })
}