import Vue, { } from 'vue'
import { PiniaVuePlugin } from 'pinia'
import CtLogin from './components/ChurchToolsIntegration.vue'
import './assets/style/style.css'
import pinia from './init/init-store.js'

Vue.use(PiniaVuePlugin)
Vue.mixin({ methods: { t, n } })

const VueSettings = Vue.extend({
  pinia,
  render: h => h(CtLogin),
})

new VueSettings().$mount('#ct_prefs')
