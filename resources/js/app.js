import '../css/app.css';
import 'vue-toastification/dist/index.css';

import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { createApp, h } from 'vue';
import Toast from 'vue-toastification';

createInertiaApp({
    resolve: (name) =>
        resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue')),
    setup({ el, App, props, plugin }) {
        createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(Toast, {
                timeout: 4000,
                position: 'top-right',
                hideProgressBar: false,
                closeOnClick: true,
                pauseOnHover: true,
            })
            .mount(el);
    },
});
