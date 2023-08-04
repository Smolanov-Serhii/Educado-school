
const html = document.querySelector('html')
const body = document.querySelector('body')

export const DisableScroll = (element) => {
    html.classList.add('noscroll')
    body.classList.add('noscroll')
}

export const EnableScroll = (element) => {
    html.classList.remove('noscroll')
    body.classList.remove('noscroll')
}
