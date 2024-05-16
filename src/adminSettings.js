import Vue from 'vue'
import CtLogin from './components/ChurchToolsIntegration.vue'

import './assets/style/style.css'

Vue.mixin({ methods: { t, n } })

const VueSettings = Vue.extend(CtLogin)
new VueSettings().$mount('#ct_prefs')
