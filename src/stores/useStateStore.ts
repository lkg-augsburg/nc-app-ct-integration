import type { CtAuthenticationResponse } from '@/models/requests/CtAuthentication'
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
}

export const useStateStore = defineStore('state', {
  state: () => ({
    shouldAuthenticate: false,
    authWasExecuted: false,
    authSuccessful: false,
    authErrorMessage: '',
    userId: '',
    userName: '',
    userMail: '',
    orgName: '',
    orgShortName: '',
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
})