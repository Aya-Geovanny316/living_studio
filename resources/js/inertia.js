import '../css/app.css';
import 'cropperjs/dist/cropper.css';
import { createApp, h } from 'vue';
import { createInertiaApp, Link, Head } from '@inertiajs/vue3';
import { InertiaProgress } from '@inertiajs/progress';

InertiaProgress.init({
    color: '#0342e9',
    showSpinner: false,
});

const pages = import.meta.glob('./Pages/**/*.vue');

createInertiaApp({
    resolve: (name) => pages[`./Pages/${name}.vue`](),
    setup({ el, App, props, plugin }) {
        createApp({ render: () => h(App, props) })
            .use(plugin)
            .component('Link', Link)
            .component('Head', Head)
            .mount(el);
    },
});
