import type { CtAuthenticationResponse } from '@/models/requests/CtAuthentication'
import type { Group } from '@/models/requests/Group';
import type { GroupType } from '@/models/requests/GroupType';
import { defineStore } from "pinia";

export interface StateStoreState {
  shouldAuthenticate: boolean;
  authWasExecuted: boolean;
  authSuccessful: boolean;
  authErrorMessage: string;
  userId: string;
  userName: string;
  userMail: string;
  orgName: string;
  orgShortName: string;
  groupTypes: GroupType[];
  groups: Group[];
}

export const useStateStore = defineStore('state', {
  state: (): StateStoreState => ({
    shouldAuthenticate: false,
    authWasExecuted: false,
    authSuccessful: false,
    authErrorMessage: '',
    userId: '',
    userName: '',
    userMail: '',
    orgName: '',
    orgShortName: '',
    groupTypes: [],
    groups: [],
  }),
  actions: {
    setAuthData(data: CtAuthenticationResponse){
      this.userId = data.userId
      this.userName = data.userName
      this.userMail = data.userMail
      this.orgName = data.orgName
      this.orgShortName = data.orgShortName
    }
  },
  getters: {
    getGroupsForType: (state) => {
      return (groupTypeId: number) => state.groups.filter((group) => group.type === groupTypeId)
    },
  }
})