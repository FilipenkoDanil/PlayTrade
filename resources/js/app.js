import './bootstrap';

import {createApp} from "vue";
import router from "./router/router.js";
import LaravelPermissionToVueJS from 'laravel-permission-to-vuejs'

import App from "./App.vue";

// Vuetify
import '@mdi/font/css/materialdesignicons.css'
import 'vuetify/styles'
import {createVuetify} from 'vuetify'
import * as components from 'vuetify/components'
import * as directives from 'vuetify/directives'

const vuetify = createVuetify({
    components,
    directives,

    theme: {
        defaultTheme: 'dark'
    }
})


createApp(App).use(vuetify).use(router).use(LaravelPermissionToVueJS).mount('#app')
