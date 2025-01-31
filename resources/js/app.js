import './bootstrap';

import { createApp } from 'vue';
import App from './App.vue';
import store from './store/index.js';
import router from './router/index';

// PrimeVue
import PrimeVue from 'primevue/config';
import Aura from '@primevue/themes/aura';
import ConfirmationService from 'primevue/confirmationservice';
import ToastService from 'primevue/toastservice';
import StyleClass from "primevue/styleclass";
import './assets/styles.scss';

const app = createApp(App);
app.use(store)
app.use(router);
app.use(PrimeVue, {
    theme: {
        preset: Aura,
        options: {
            darkModeSelector: '.app-dark'
        }
    }
});
app.use(ConfirmationService)
app.use(ToastService)
app.directive("styleclass", StyleClass);
app.mount('#app');
