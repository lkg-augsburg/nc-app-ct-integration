import { createPinia } from 'pinia'
import { useConfigStore } from '../stores/useConfigStore'
import type { DebuggerEvent } from 'vue'
import { useStateStore } from '../stores/useStateStore'
import { persistConfiguration } from '@/services/config-service'

const pinia = createPinia();
const configStore = useConfigStore(pinia);
const stateStore = useStateStore(pinia);

if(configStore.ctUrl?.length > 0 || configStore.ctToken?.length > 0) {
  stateStore.shouldAuthenticate = true;
}

configStore.$subscribe(async (evt) => {
  const {key, newValue, oldValue} = (evt.events as DebuggerEvent)
  if(
    ["ctToken", "ctUrl"].includes(key) && newValue !== oldValue
    && configStore.hasAuth
  ){
    stateStore.shouldAuthenticate = true;
    stateStore.isInit = false;
  } else if (!["ctToken", "ctUrl"].includes(key) && newValue !== oldValue) {
    persistConfiguration({
      [key]: newValue
    });
  }
});

export default pinia;
