import "./style.css"

import { createApp } from 'vue'

import pinia from './utils/init-store'
import './workers/auth-worker'

import App from './App.vue'
const app = createApp(App)

app.use(pinia)

app.mount('#ct_prefs')