import { createPinia } from 'pinia'
import { useConfigurationStore } from '../store/useConfigurationStore.js'
import { persistConfiguration } from '../service/config-service.js'

const pinia = createPinia()
const store = useConfigurationStore(pinia)

store.$subscribe(async () => {
  await persistConfiguration()
})

export default pinia
