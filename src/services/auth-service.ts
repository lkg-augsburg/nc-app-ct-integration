import type { CtAuthenticationResponse } from '@/models/requests/CtAuthentication'
import type { ErrorResponse } from '@/models/requests/ErrorResponse'
import type Response from '@/models/requests/Response'
import axios from '../axios'

export function authenticateChurchTools(url: string, token: string) {
  return axios.post<Response<CtAuthenticationResponse | ErrorResponse>>('authenticate', {url, token})
}