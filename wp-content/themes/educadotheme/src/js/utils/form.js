import { EnableScroll } from '../utils/scroll.js'
import { captureUtmToCookieFromUrl, applyUtmToForm } from 'utm.js'

const formAjaxSend = (form) => {
    // 1) гарантируем, что utm_* есть в форме как hidden поля
    applyUtmToForm(form)

    const actionInput = form.querySelector('input[name="action"]')
    const urlInput = form.querySelector('input[name="url"]')
    const fields = form.querySelectorAll('input, textarea')

    if (actionInput) {
        let action = actionInput.value
        let url = urlInput.value + '?action=' + action
        let data = {}

        fields && fields.length && fields.forEach(field => {
            if (!field.name) return

            if (field.type === 'checkbox') {
                // если нужно отправлять и unchecked — скажи, сейчас отправляем только checked
                if (field.checked === true) data[field.name] = 'Обрано: <br>'
            } else if (field.type === 'text' || field.type === 'hidden' || field.tagName === 'TEXTAREA') {
                data[field.name] = (field.value || '').replace(/\n/g, '<br/>')
            }
        })

        data = Object.keys(data)
            .map(key => encodeURIComponent(key) + '=' + encodeURIComponent(data[key]))
            .join('&')

        fetch(url, {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: data
        })
            .then(r => r.json())
            .then(() => formSuccessSubmit(form))
            .catch(() => formSuccessSubmit(form))
    } else {
        formSuccessSubmit(form)
    }
}

const formSuccessSubmit = (form) => {
    const popup = form.closest('.popup')
    const success = document.querySelector('.popup#success-popup')
    const fields = form.querySelectorAll('.form-row__input, .form-row__textarea')

    popup && popup.classList.remove('open')
    success && success.classList.add('open')

    EnableScroll()

    fields.forEach((field) => field.value = '')

    setTimeout(() => success && success.classList.remove('open'), 5000)
}

export const initForms = () => {
    // 2) если на странице есть utm_* в URL — записываем в cookie (новые перетрут старые)
    captureUtmToCookieFromUrl()

    const forms = document.querySelectorAll('form.form')
    if (!forms || !forms.length) return

    forms.forEach(form => {
        // 3) проставим utm_* в форму заранее (и на всякий случай они уже будут в DOM)
        applyUtmToForm(form)

        const fields = form.querySelectorAll('.required')
        const phones = form.querySelectorAll('.required-phone')

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

            // 4) финально перед отправкой (если форма динамически менялась) — снова utm_*
            applyUtmToForm(form)

            formAjaxSend(form)
        })
    })
}
