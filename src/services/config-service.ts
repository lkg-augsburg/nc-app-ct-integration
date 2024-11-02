import type { PersistConfigPayload } from '@/models/requests/Configuration'
import axios from '../axios'
import type Response from '@/models/requests/Response'

export function persistConfiguration(config: PersistConfigPayload) {
  return axios.post<Response<null>>('configuration', config)
}