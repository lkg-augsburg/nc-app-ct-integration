import type Response from '@/models/requests/Response'
import axios from '../axios'
import type { ErrorResponse } from '@/models/requests/ErrorResponse'
import type { GroupType } from '@/models/requests/GroupType'
import type { Group } from '@/models/requests/Group'

export function fetchGroupTypes() {
  return axios.get<Response<GroupType[] | ErrorResponse>>('group-type')
}
export function fetchAllGroups() {
  return axios.get<Response<Group[] | ErrorResponse>>('group')
}