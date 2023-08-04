import gsap from 'gsap'

const Accordion = (accordion) => {
    const section = accordion.closest('section')
    const header = document.querySelector('.ed-header')
    const list = accordion.parentNode
    const speed = 400


    accordion.addEventListener('click', (e) => {
        if (!accordion.classList.contains('active')) {
            e.preventDefault();

            const promise = new Promise((resolve, reject) => {
                accordionCloseAll(list, accordion)

                setTimeout(resolve, speed)
            })
            .then(() => {
                const accordionOffset = accordion.offsetTop
                const sectionOffset = section ? section.offsetTop : 0
                const headerheight = header.offsetHeight
                const offset = accordionOffset + sectionOffset - headerheight

                accordionOpen(accordion, offset)
            })
        }
    })
}


const accordionOpen = (accordion, offset) => {
    accordion.classList.add('active');
    
    window.scroll({ top: offset, left: 0, behavior: 'smooth' })
}


const accordionClose = (accordion) => {
    accordion.classList.remove('active')
}


const accordionCloseAll = (list) => {
    const items = list.querySelectorAll('.accordion')

    items && items.length && items.forEach((item) => {
        if (item.classList.contains('active')) accordionClose(item)
    })  
}

export const initAccordions = () => {
	const accordions = document.querySelectorAll('.accordion')

	accordions && accordions.length && accordions.forEach((accordion) => {
        Accordion(accordion)
    })
}
