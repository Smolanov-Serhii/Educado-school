// utils/cookies.js

export const setCookie = (cname, cvalue, exdays) => {
    const date = new Date()
    date.setTime(date.getTime() + (exdays * 24 * 60 * 60 * 1000))
    const expires = 'expires=' + date.toUTCString()

    document.cookie =
        cname + '=' + encodeURIComponent(String(cvalue)) +
        ';' + expires +
        ';path=/' +
        ';SameSite=Lax'
}

export const getCookie = (cname) => {
    const name = cname + '='
    const ca = document.cookie.split(';')

    for (let i = 0; i < ca.length; i++) {
        let c = ca[i]
        while (c.charAt(0) === ' ') c = c.substring(1)

        if (c.indexOf(name) === 0) {
            return decodeURIComponent(c.substring(name.length, c.length))
        }
    }

    return ''
}
