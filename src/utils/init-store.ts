import { createPinia } from 'pinia'
import { useConfigStore } from '../stores/useConfigStore'
import type { DebuggerEvent } from 'vue'
import { useStateStore } from '../stores/useStateStore'
import { persistConfiguration } from '@/services/config-service'

const pinia = createPinia();
const configStore = useConfigStore(pinia);
const stateStore = useStateStore(pinia);

const authStateKeys = ["ctToken", "ctUrl"];

if(configStore.ctUrl?.length > 0 || configStore.ctToken?.length > 0) {
  stateStore.shouldAuthenticate = true;
}

configStore.$subscribe(async (evt, state) => {
  const {key, newValue, oldValue} = (evt.events as DebuggerEvent)
  if(
    authStateKeys.includes(key) && newValue !== oldValue
    && configStore.hasAuth
  ){
    stateStore.shouldAuthenticate = true;
    stateStore.isInit = false;
  } else if (!authStateKeys.includes(key) && newValue !== oldValue) {
    
    persistConfiguration(Object.fromEntries(
      Object.entries(state).filter(([key]) => !authStateKeys.includes(key))
    ));
  }
});

export default pinia;
