import { createPinia } from 'pinia'
import { useConfigStore } from '../stores/useConfigStore'
import type { DebuggerEvent } from 'vue'
import { useStateStore } from '../stores/useStateStore'

const pinia = createPinia()
const configStore = useConfigStore(pinia)
const stateStore = useStateStore(pinia)

if(configStore.ctUrl?.length > 0 || configStore.ctToken?.length > 0) {
  stateStore.shouldAuthenticate = true
}

configStore.$subscribe(async (evt, state) => {
  const {key, newValue, oldValue} = (evt.events as DebuggerEvent)
  if(
    ["ctToken", "ctUrl"].includes(key) && newValue !== oldValue
    && state.ctUrl.length > 10
    && state.ctToken.length !== 0
  ){
    stateStore.shouldAuthenticate = true
  }
})

export default pinia
