import gsap from 'gsap'
import ScrollTrigger from 'gsap/ScrollTrigger.js'
import { DisableScroll, EnableScroll } from '../utils/scroll.js'
import smoothscroll from 'smoothscroll-polyfill'

gsap.registerPlugin(ScrollTrigger)


const initHeaderScroll = () => {
	const header = document.querySelector('.ed-header')

	header && window.addEventListener('scroll', (e) => {
		window.pageYOffset > 200 ? header.classList.add('scroll') : header.classList.remove('scroll')
	})
}


const initHeaderMenuToggle = () => {
	const header = document.querySelector('.ed-header')
	const toggle = document.querySelector('.ed-header-burger')

	header && toggle && toggle.addEventListener('click', (e) => {
		if (header.classList.contains('--menu-open')) {
			header.classList.remove('--menu-open')
			EnableScroll()
		} else {
			header.classList.add('--menu-open')
			DisableScroll()
		}
	})
}

const initHeaderMenu = () => {
	const header = document.querySelector('.ed-header')
	const items = document.querySelectorAll('.ed-header-menu a, .ed-menu a')

	items && items.length && items.forEach(item => {
		const block = item.getAttribute('href')
		const el = document.querySelector(block)
		

		item.addEventListener('click', (e) => {
			e.preventDefault();
			header.classList.remove('--menu-open')
			EnableScroll()

			if (el) {
				const offset = el.offsetTop + el.closest('section').offsetTop

				window.scroll({ top: offset - header.offsetHeight, left: 0, behavior: 'smooth' });
			}
		})
	})
}


export const initHeader = () => {

	smoothscroll.polyfill()

	initHeaderScroll()

	initHeaderMenuToggle()

	initHeaderMenu()

}



