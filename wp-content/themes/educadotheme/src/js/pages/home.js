import gsap from 'gsap'
import ScrollTrigger from 'gsap/ScrollTrigger.js'
import Swiper from 'swiper/bundle'
import { DrawSVGPlugin } from '../utils/DrawSVGPlugin.js'
import { DisableScroll } from '../utils/scroll.js'
import { MagneticIcon } from '../utils/magnetic.js'


gsap.registerPlugin(ScrollTrigger, DrawSVGPlugin)


const initHomeBanner = () => {
    const banner = document.querySelector('.ed-home-banner')

    if (banner && window.innerWidth > 1080) {
        const planet = banner.querySelector('.banner-world__image')

        gsap.to(planet, {
            duration: .4,
            rotate: 25,
            y: 70,
            scrollTrigger: {
                trigger: banner,
                start: () => 'top top',
                end: () => 'bottom top',
                scrub: 1,
                ease: 'none'
            },
        })
    }   
}

const initHomeUnderlines = () => {
    const lines = document.querySelectorAll('.look-subtitle span, .guarantees-footer span')
    const svg = '<svg viewBox="0 0 364 27" fill="none" xmlns="http://www.w3.org/2000/svg"> <path d="M1 9.05889C100.398 2.97746 311.955 -5.53654 363 9.05889C264.036 22.0905 115.59 6.45254 19.2302 26" stroke="#D3D360" stroke-width="2"/></svg>'

    lines && lines.length && lines.forEach(line => {
        line.innerHTML = line.innerHTML + svg
        const lineSVG = line.querySelector('path')

        gsap.set(lineSVG, { drawSVG: '0%' })

        gsap.to(line, {
            scrollTrigger: {
                trigger: line,
                start: 'top bottom-=200px',  
                once: true,
                scrub: 1,
                onEnter: self => {
                    line.classList.add('shown')

                    gsap.to(lineSVG, { duration: .8, drawSVG: '100%' })
                }
            }
        })
    })
}

const initHomeFounder = () => {
    const founder = document.querySelector('.ed-home-founder')

    if (founder) {
        const blocks = founder.querySelectorAll('.founder-info__title, .founder-info__socials, .founder-info__description, .founder-info__text p, .founder-footer')
        let delay = 0

        gsap.set([blocks], { opacity: 0 })

        blocks && blocks.length && blocks.forEach(block => {
            // delay += 0.05
            delay = .1

            gsap.to(block, { 
                duration: .6,
                delay: delay,
                opacity: 1,
                scrollTrigger: {
                    trigger: block,
                    start: 'top bottom',
                    once: true
                }
            })
        })
    }
}

const initHomeAppointment = () => {
    const icons = document.querySelectorAll('.appointment-item__icon')

    icons && icons.length && icons.forEach(icon => {        
        new MagneticIcon(icon)
    })
}

const initHomeLanguages = () => {
    const tickers = document.querySelectorAll('.languages-ticker')

    tickers && tickers.length && tickers.forEach(ticker => {
        const wrapper = ticker.querySelector('.languages-ticker__wrapper')
        const line = ticker.querySelector('.languages-ticker__line')
        const width = line.clientWidth
        const transition = 100
        const duration = parseFloat(width / (transition * 0.999)) + 's'
        
        gsap.set(line, { '--duration': duration })

        const line2 = line.cloneNode(true)
        wrapper.appendChild(line2)
        ticker.classList.add('ready')

        let tickerTimer = setInterval(() => {
            let durationImages = parseFloat(line.clientWidth / (transition * 0.999)) + 's'

            gsap.set([line, line2], { '--duration': durationImages })
        }, 100)
        
        setTimeout(() => { clearInterval(tickerTimer) }, 1000)
    })
}

const initHomeStart = () => {
    const start = document.querySelector('.ed-home-start')

    if (start) {
        const steps = start.querySelectorAll('.start-step')

        gsap.from(steps, {
            duration: .4,
            opacity: 0,
            y: 100,
            rotate: 0,
            scrollTrigger: {
                trigger: start,
                start: () => 'top center',
                end: () => 'bottom bottom',
                scrub: 1,
                ease: 'none'
            },
        })
    }   
}

const initHomeTeachers = () => {
    const sliders = document.querySelectorAll('.about-teachers-slider')
    const teachers = document.querySelectorAll('.teacher-card')
    const popup = document.querySelector('.popup#youtube')
    const popupClose = document.querySelector('.popup#youtube .popup-close')
    const popupBg = document.querySelector('.popup#youtube .popup-bg')
    const iframe = document.querySelector('.popup#youtube .popup-iframe')

    sliders && sliders.length && (window.innerWidth > 640) && sliders.forEach(slider => {
        const teamSwiper = new Swiper(slider, {
            loop: false,
            slidesPerView: 'auto',
            slidesPerGroup: 1,
            spaceBetween: 30,
            speed: 800
        })
    })

    teachers && teachers.length && popup && teachers.forEach(teacher => {
        const play = teacher.querySelector('.teacher-card__play')
        const id = play ? play.dataset.youtube : ''

        play && play.addEventListener('click', () => {

            if (iframe) {
                iframe.innerHTML = `<iframe width="560" height="315" src="https://www.youtube.com/embed/${id}?rel=0&autoplay=1" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>`
            }

            popup.classList.add('open')
            
            DisableScroll()
        })
    })

    popupClose && popupClose.addEventListener('click', () => iframe.innerHTML = '')
    popupBg && popupBg.addEventListener('click', () => iframe.innerHTML = '')
}

const initHomeReviews = () => {
    const reviews = document.querySelector('.about-reviews-list')
    
    if (reviews) {
        reviewsCols()

        window.addEventListener('resize', reviewsCols)
    
        ScrollTrigger.matchMedia({
            '(min-width: 640px)': () => {
                ScrollTrigger.create({
                    trigger: reviews,
                    pin: true,
                    scrub: 1,
                    start: 'center center',
                    end: () => '+=' + (window.innerHeight * 2)
                })           
            }
        })
    }
}

const reviewsCols = () => {
    const reviews = document.querySelector('.about-reviews')
    const list = reviews.querySelector('.about-reviews .about-reviews-list')
    const cards = reviews.querySelectorAll('.about-reviews .review-card')
    const cols = window.innerWidth > 1780 ? 4 : (window.innerWidth > 1080 ? 3 : (window.innerWidth > 640 ? 2 : 1))

    list.innerHTML = ''
    for (let i = 0; i < cols; i++) {
        list.innerHTML = list.innerHTML + '<div class="about-reviews-list__col"></div>';
    }

    const listColumns = reviews.querySelectorAll('.about-reviews-list__col')
    cards && cards.forEach((card, i) => {
        let col = ((i + cols) % cols)

        listColumns[col].append(card)
    })


    const tl = gsap.timeline({
        scrollTrigger: {
            trigger: list,
            start: () => 'center center',
            end: () => "+=" + (window.innerHeight * 2),
            scrub: 1,
            pinType: 'transform',
        }
    })

    if (cols > 1) {
        const offset1 = listColumns[0].scrollHeight / 4
        const offset2 = listColumns[1].scrollHeight / 4
        
        tl.to('.about-reviews-list__col:nth-child(2n + 1)', { y: offset1 })
        tl.to('.about-reviews-list__col:nth-child(2n)', { y: -offset2 }, 0)
    }
}

export const initHome = () => {

    initHomeBanner()

    initHomeUnderlines()

    initHomeFounder()

    initHomeAppointment()
    
    initHomeStart()

    initHomeLanguages()

    initHomeTeachers()

    initHomeReviews()

    

}
