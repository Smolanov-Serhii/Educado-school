
import { initPreloader } from './parts/preloader.js'
import { initLazyLoad } from './utils/lazyLoad.js'
import { initHeader } from './parts/header.js'
import { initForms } from './utils/form.js'
import { initPopups } from './utils/popup.js'
import { initAccordions } from './utils/accordion.js'
import { initHome } from './pages/home.js'

initPreloader()

initLazyLoad()

document.addEventListener('DOMContentLoaded', () => {


	initHeader()
	
	initForms()

	initPopups()

	initAccordions()

	initHome()

	const button = document.querySelector(".socials__triger");
	let element = document.getElementById("socials");
	button.addEventListener("click", (event) => {
		element.classList.toggle("active");
	});

	const div = document.querySelector('#socials');

	if (div) {
		document.addEventListener('click', (e) => {
			const withinBoundaries = e.composedPath().includes(div);

			if (!withinBoundaries) {
				div.classList.remove("active");
			}
		});
	}



})
