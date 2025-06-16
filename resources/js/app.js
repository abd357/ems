import './bootstrap';
import { createApp, markRaw } from 'vue';
import Vue3Toastify, { toast } from 'vue3-toastify';
import 'vue3-toastify/dist/index.css';
import { createPinia } from 'pinia';
import TileComponent from '../js/components/TileComponent.vue'

import App from './pages/App.vue';
import router from './router';

// createApp({
//     components: {
//     },
// }).mount('#app');

const app = createApp(App)
const pinia = createPinia()

pinia.use(({ store }) => {
    store.router = markRaw(router)
})

app.use(router)
app.use(Vue3Toastify,{
    autoClose:3000
})
app.use(pinia)
app.component('tile-component', TileComponent)

app.mount('#app')