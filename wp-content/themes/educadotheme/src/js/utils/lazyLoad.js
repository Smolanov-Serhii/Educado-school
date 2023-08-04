

export const initLazyLoad = () => {    
    const lazyLoadPromise = new Promise((resolve, reject) => {
        const dataImages = document.querySelectorAll('img[data-src]');

        dataImages.forEach((img, i) => {
            img.removeAttribute('src')
            img.setAttribute('src', img.getAttribute('data-src'))

            if (img.getAttribute('data-srcset')) img.setAttribute('srcset', img.getAttribute('data-srcset'))
            
            img.onload = () => {
                img.removeAttribute('data-src')
                img.removeAttribute('data-srcset')
            }
        })
        
        resolve()
    })
}