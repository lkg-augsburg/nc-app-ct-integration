import type Response from '@/models/requests/Response'
import axios from '../axios'

export function persistConfiguration(url: string, token: string) {
  return axios.post<Response<null>>('configuration', {url, token})
}