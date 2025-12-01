import Alpine from 'alpinejs'
import morph from '@alpinejs/morph'
import ajax from '@imacrayon/alpine-ajax'

window.Alpine = Alpine
Alpine.plugin(morph)
Alpine.plugin(ajax)

Alpine.store('demo', {
    navbar: 'Navbar text',
    aside: 'Aside text',
    main: 'Main text'
})

Alpine.start()
