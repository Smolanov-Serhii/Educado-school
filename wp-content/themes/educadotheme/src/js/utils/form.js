import { EnableScroll } from '../utils/scroll.js'

const formVacanciesSend = (form) => {
    const status = form.querySelector('[data-vacancies-status]')
    const submit = form.querySelector('button[type="submit"]')

    if (status) {
        status.hidden = true
        status.textContent = ''
    }

    if (submit) submit.disabled = true

    fetch(form.action || window.location.href, {
        method: 'POST',
        credentials: 'same-origin',
        headers: {'X-Requested-With': 'XMLHttpRequest'},
        body: new FormData(form)
    })
        .then(async response => {
            const result = await response.json().catch(() => null)

            if (!response.ok || !result || !result.success) {
                const message = result && result.data && result.data.message
                    ? result.data.message
                    : 'Не вдалося відправити заявку. Спробуйте ще раз.'

                throw new Error(message)
            }

            form.reset()
            formSuccessSubmit(form)
        })
        .catch(error => {
            if (!status) return

            status.textContent = error.message
            status.hidden = false
        })
        .finally(() => {
            if (submit) submit.disabled = false
        })
}

const formAjaxSend = (form) => {
    if (form.hasAttribute('data-vacancies-form')) {
        formVacanciesSend(form)
        return
    }

    const actionInput = form.querySelector('input[name="action"]')
    const urlInput = form.querySelector('input[name="url"]')
    const fields =  form.querySelectorAll('input, textarea')

    if (actionInput) {
        let action = actionInput.value
        let url = urlInput.value + '?action=' + action 
        let data = {}

        fields && fields.length && fields.forEach(field => {
            if(field.type === 'checkbox' && field.checked == true){
                data[field.name] = 'Обрано: <br>';
            } else if(field.type === 'text'){
                data[field.name] =  field.value.replace(/\n/g, '<br/>');
            }
        })
        

        data = Object.keys(data).map(key => encodeURIComponent(key) + '=' + encodeURIComponent(data[key])).join('&')
        console.log(data);
        fetch(url, {
            method: 'POST',
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            body: data
        })
        .then(response => response.json())
        .then(response => {
            formSuccessSubmit(form)
        }).catch(() => formSuccessSubmit(form))
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
    const forms = document.querySelectorAll('form.form')

    forms && forms.length && forms.forEach(form => {
        const fields = form.querySelectorAll('.required')
        const phones = form.querySelectorAll('.required-phone')
    
        fields.forEach((field) => {
            field.addEventListener('focus', (e) => field.parentNode.classList.remove('error'), { passive: true })
        })

        phones.forEach((field) => {
            field.addEventListener('focus', (e) => field.parentNode.classList.remove('error'), { passive: true })

            field.addEventListener('input', function(e) {
                this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');
            })
        })

    

        form.addEventListener('submit', (e) => {
            e.preventDefault()
            let errors = 0
            let phones_count = 0

            fields.forEach((field) => {
                if (field.name == 'phone') {
                    if (field.value.length < 16) {
                        field.parentNode.classList.add('error')
                        errors++
                    } else {
                        field.parentNode.classList.remove('error')
                    }
                } else {
                    if (field.value.length < 3) {
                        field.parentNode.classList.add('error')
                        errors++;
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

            formAjaxSend(form)
        })
    })
}
