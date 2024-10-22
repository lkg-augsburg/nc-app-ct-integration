import axios from '../axios'
import type Response from '@/models/requests/Response'

export function persistConfiguration(url: string, token: string) {
  return axios.post<Response<null>>('configuration', {url, token})
}