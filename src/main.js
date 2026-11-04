import { createApp } from 'vue'
import App from './App.vue'
import router from './routers'
import 'bootstrap/dist/css/bootstrap.min.css'
import 'bootstrap-icons/font/bootstrap-icons.min.css'
import 'bootstrap/dist/js/bootstrap.bundle.min.js'
import './assets/dashboard.css'
import './assets/reservas.css'
import './assets/calendario.css'

const app = createApp(App)
app.use(router)
app.mount('#app')