import gsap from 'gsap'
import { DisableScroll, EnableScroll } from '../utils/scroll.js'
import { getCookie, setCookie } from '../utils/cookies.js'

const preloaderAnimate = () => {
    const preloader = document.querySelector('.ed-preloader')
    const logo = preloader.querySelector('.ed-preloader-logo')
    const paths = logo.querySelectorAll('.path1, .path2, .path3, .path4, .path5, .path6, .path7')
    const tl = gsap.timeline({delay: 1})

    paths.forEach(path => {
        tl.to(path, {
            duration: .4, 
            y: 0,
            opacity: 1,            
        }, '-=.3')
    })

    tl.to('.path8', { delay: .2, duration: .8, opacity: 1 })
    tl.set({}, {}, '+=.4')

    return tl
}

export const initPreloader = async () => {
    const preloader = document.querySelector('.ed-preloader')
    const cookie = getCookie('educado')

    if (preloader) {
        if (cookie == 'educado') {
            preloader.classList.add('hidden')
            preloader.remove()
        } else {
            setCookie('educado', 'educado', 1)
            DisableScroll()

            await preloaderAnimate()
            preloader.classList.add('hidden')
            EnableScroll()
        }        
    }
}