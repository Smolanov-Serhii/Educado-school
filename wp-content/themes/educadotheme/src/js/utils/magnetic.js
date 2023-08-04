import gsap from 'gsap'

export class MagneticIcon {
    constructor(el) {
        this.el = el
        this.delay = (this.el.dataset.delay) ? this.el.dataset.delay : 0.3
        this.translate = (this.el.dataset.translate) ? this.el.dataset.translate : 0

        if (window.innerWidth > 1080) this.initEvents()
    }

    initEvents() {
        this.el.addEventListener('mousemove', e => {
            let { left, top, width, height } = this.el.getBoundingClientRect()
    
            let translate = (this.translate) ? this.translate * height : height / 3
            let mouseX = e.x - left
            let mouseY = e.y - top
    
            let x = gsap.utils.interpolate(-translate, translate, mouseX / width)
            let y = gsap.utils.interpolate(-translate, translate, mouseY / height)
    
            gsap.to(this.el, {
                x: x,
                y: y,
                duration: 0.6
            })
        })

        this.el.addEventListener('mouseleave', e => {
            gsap.to([this.el], {
                x: 0,
                y: 0,
                duration: .6
            })
        })
    }
}