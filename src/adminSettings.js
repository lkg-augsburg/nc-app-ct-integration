import Vue from 'vue'
import CtLogin from './components/CtLogin.vue'

import './assets/style/style.css'

Vue.mixin({ methods: { t, n } })

const VueSettings = Vue.extend(CtLogin)
new VueSettings().$mount('#pexels_prefs')
