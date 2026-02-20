import { initPreloader } from './parts/preloader.js'
import { cookieutm, initUtmAutofill } from './utils/cookie_utm.js'
import Swiper from 'swiper/bundle'
import { initLazyLoad } from './utils/lazyLoad.js'
import { initHeader } from './parts/header.js'
import { initForms } from './utils/form.js'
import { initPopups } from './utils/popup.js'
import { initAccordions } from './utils/accordion.js'
import { initHome } from './pages/home.js'

cookieutm()
initUtmAutofill()

initPreloader()
initLazyLoad()

document.addEventListener('DOMContentLoaded', () => {
	initHeader()
	initForms()
	initPopups()
	initAccordions()
	initHome()

	const button = document.querySelector('.socials__triger')
	const div = document.querySelector('#socials')

	if (button && div) {
		button.addEventListener('click', () => {
			div.classList.toggle('active')
		})

		document.addEventListener('click', (e) => {
			const withinBoundaries = e.composedPath().includes(div)
			if (!withinBoundaries) div.classList.remove('active')
		})
	}
})
