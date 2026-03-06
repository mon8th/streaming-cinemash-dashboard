import './bootstrap';
import '../css/app.css'

import { createApp, h } from 'vue'
import { createInertiaApp, Link, Head } from '@inertiajs/vue3'
import Layouts from './Layouts/Layouts.vue';

createInertiaApp({
    title: (title) => `My App ${title}`,
    resolve: name=> {
        const pages = import.meta.glob('./Pages/**/*.vue', {eager: true})
        let page = pages[`./Pages/${name}.vue`]
        if (page.default.layout === undefined) {
            page.default.layout = Layouts
        }
        return page
    },
    setup({el, App, props, plugin }) {
        const app = createApp({ render: () => h(App, props)})
        app.config.devtools = true
        app.use(plugin)
        app.component('Head', Head)
        app.component('Link', Link)
        app.mount(el)
    },
})

