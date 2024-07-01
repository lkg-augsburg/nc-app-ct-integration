import { generateUrl } from '@nextcloud/router'
import axios from '@nextcloud/axios'
import { useConfigurationStore } from '../store/useConfigurationStore.js'

axios.defaults.baseURL = generateUrl('/apps/churchtoolsintegration/api')

/**
 *
 */
export async function persistConfiguration() {
  const store = useConfigurationStore()

  const { ctUrl, ctToken, ctSyncGroups } = store

  await axios.post('save-config', {
    ctUrl,
    ctToken,
    ctSyncGroups: [...Object.entries(ctSyncGroups)].reduce(
      (acc, [key, val]) => (!val || val.length === 0) ? { ...acc } : { ...acc, [key]: val }, {},
    ),
  })
}
