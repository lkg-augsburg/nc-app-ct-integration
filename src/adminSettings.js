import Vue from 'vue'
import { createPinia, PiniaVuePlugin } from 'pinia'
import CtLogin from './components/ChurchToolsIntegration.vue'
import './assets/style/style.css'

const pinia = createPinia()
Vue.use(PiniaVuePlugin)

Vue.mixin({ methods: { t, n } })

// const VueSettings = Vue.extend(CtLogin)
const VueSettings = Vue.extend({
  pinia,
  render: h => h(CtLogin),
})

new VueSettings().$mount('#ct_prefs')
