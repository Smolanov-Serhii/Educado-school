import { DisableScroll, EnableScroll } from '../utils/scroll.js'

export const initPopups = () => {
    const popups = document.querySelectorAll('.popup')
    const buttons = document.querySelectorAll('.callpopup')

    popups && popups.length && popups.forEach(popup => {
        const close = popup.querySelector('.popup-close')
        const button = popup.querySelector('.popup-head__close')
        const bg = popup.querySelector('.popup-bg')

        close && close.addEventListener('click', (e) => {
            popup.classList.remove('open')
            EnableScroll()
        }, {passive: true})

        button && button.addEventListener('click', (e) => {
            popup.classList.remove('open')
            EnableScroll()
        }, {passive: true})

        bg && bg.addEventListener('click', (e) => {
            popup.classList.remove('open')
            EnableScroll()
        }, {passive: true})
    })

    buttons && buttons.length && buttons.forEach(button => {
        const name = button.dataset.popup
        const popup = document.querySelector('.popup#' + name)

        popup && button.addEventListener('click', (e) => {
            // 1. Проверяем, что у кнопки вообще есть data-item
            if (button.dataset.item) {
                const ButtonItem = button.dataset.item

                // 2. Ищем форму внутри попапа
                const form = popup.querySelector('form.form')
                if (form) {
                    // 3. Ищем скрытый инпут
                    const hiddenInput = form.querySelector('input[name="button_item"]')

                    // 4. Если поле есть — записываем значение
                    if (hiddenInput) {
                        hiddenInput.value = `Выбрано: - ${ButtonItem}`
                    }
                }
            }

            popup.classList.add('open')
            DisableScroll()
        }, {passive: true})
    })
}