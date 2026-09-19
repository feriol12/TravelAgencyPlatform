import { createApp } from 'vue'
import App from './App.vue'
import router from './router/index.js'
import pinia from './stores/index.js'
import './assets/main.css'

createApp(App).use(pinia).use(router).mount('#app')
