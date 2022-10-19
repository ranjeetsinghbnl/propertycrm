import './bootstrap'
import '../css/app.css'

import { createApp, h } from 'vue'
import { createInertiaApp } from '@inertiajs/inertia-vue3'
import { InertiaProgress } from '@inertiajs/progress'
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers'
import { ZiggyVue } from '../../vendor/tightenco/ziggy/dist/vue.m'

const appName = window.document.getElementsByTagName('title')[0]?.innerText || 'Property CRM'

createInertiaApp({
    title: (title) => (title ? `${title} - ${appName}` : appName),
    resolve: (name) => resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue')),
    setup({ el, app, props, plugin }) {
        const App = createApp({ render: () => h(app, props) })
            .use(plugin)
            .use(ZiggyVue, Ziggy) /*eslint no-undef: "error"*/
        App.config.globalProperties.$filters = {
            currency(value) {
                if (typeof value !== 'number') {
                    return value
                }
                const formatter = new Intl.NumberFormat('en-US', {
                    style: 'currency',
                    currency: 'USD',
                    minimumFractionDigits: 0,
                })
                return formatter.format(value)
            },
            // Put the rest of your filters here
        }
        App.mount(el)
    },
})
InertiaProgress.init({ color: '#4B5563' })
