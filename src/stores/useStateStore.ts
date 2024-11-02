import type { CtAuthenticationResponse } from '@/models/requests/CtAuthentication'
import type { Group } from '@/models/requests/Group';
import type { GroupType } from '@/models/requests/GroupType';
import { defineStore } from "pinia";

export interface StateStoreState {
  authErrorMessage: string;
  authSuccessful: boolean;
  authWasExecuted: boolean;
  groups: Group[];
  groupTypes: GroupType[];
  isInit: boolean;
  orgName: string;
  orgShortName: string;
  shouldAuthenticate: boolean;
  userId: string;
  userMail: string;
  userName: string;
}

export const useStateStore = defineStore('state', {
  state: (): StateStoreState => ({
    authErrorMessage: '',
    authSuccessful: false,
    authWasExecuted: false,
    groups: [],
    groupTypes: [],
    isInit: true,
    orgName: '',
    orgShortName: '',
    shouldAuthenticate: false,
    userId: '',
    userMail: '',
    userName: '',
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
    getGroupsForType: (state) => (groupTypeId: number) => state.groups.filter((group) => group.type === groupTypeId),
    getNonEmptyGroupTypes: (state) => state
      .groupTypes
      .filter(({id}) => state.groups.filter((group) => group.type === id).length > 0)
  },
});
