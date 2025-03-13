import './assets/main.css'
import 'bootstrap-icons/font/bootstrap-icons.css'
import { createPinia } from 'pinia';

import { createApp } from 'vue'
import 'bootstrap/dist/css/bootstrap.min.css';
import 'bootstrap/dist/js/bootstrap.bundle.min.js'
import App from './App.vue'
import router from './router'

const app = createApp(App)
const pinia = createPinia();

app.use(pinia);
app.use(router)

app.mount('#app')
