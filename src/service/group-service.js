import { generateUrl } from '@nextcloud/router'
import axios from '@nextcloud/axios'

axios.defaults.baseURL = generateUrl('/apps/churchtoolsintegration/api')

/**
 *
 */
export async function syncGroups() {
  await axios.post('sync-groups', {})
}
