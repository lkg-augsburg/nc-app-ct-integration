import axios from '../axios'
import type Response from '@/models/requests/Response'
import type { CtAuthenticationResponse } from '@/models/requests/CtAuthentication'
import type { ErrorResponse } from '@/models/requests/ErrorResponse'

export function authenticateChurchTools(url: string, token: string) {
  return axios.post<Response<CtAuthenticationResponse | ErrorResponse>>('authenticate', {url, token})
}