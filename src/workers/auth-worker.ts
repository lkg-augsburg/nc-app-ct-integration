import type { CtAuthenticationResponse } from "@/models/requests/CtAuthentication";
import type { ErrorResponse } from "@/models/requests/ErrorResponse";
import type { Group } from "@/models/requests/Group";
import type { GroupType } from "@/models/requests/GroupType";
import type Response from "@/models/requests/Response";
import { authenticateChurchTools } from "@/services/auth-service";
import { persistConfiguration } from "@/services/config-service";
import { fetchAllGroups, fetchGroupTypes } from "@/services/ct-service";
import { useConfigStore } from "@/stores/useConfigStore";
import { useStateStore } from "@/stores/useStateStore";

function handleErrorResponse(response: Response<ErrorResponse>){
  const stateStore = useStateStore()
  stateStore.authSuccessful = false
  stateStore.authErrorMessage = response.message

  const errorData = response.data

  if(errorData.message){
    stateStore.authErrorMessage = errorData.message
  }
}

function handleEmptyResponse(){
  const stateStore = useStateStore()
  stateStore.authSuccessful = false
  stateStore.authErrorMessage = ''
}

async function handleAuthSuccess(response: Response<CtAuthenticationResponse>){
  const configStore = useConfigStore()
  const stateStore = useStateStore()
  stateStore.authSuccessful = true
  stateStore.authErrorMessage = ''
  stateStore.setAuthData(response.data)
  if(stateStore.isInit){
    stateStore.isInit = false
  } else {
    await persistConfiguration(configStore.ctUrl, configStore.ctToken)
  }
}

function isResponseEmpty(response: Response<CtAuthenticationResponse>){
  const { userId, userName, orgName } = response.data
  return !userId || !userName || !orgName
}

async function handleGroups(){
  const { data } = await fetchAllGroups()
  if(data.status === 'ok'){
    const stateStore = useStateStore()
    stateStore.groups = data.data as Group[]
  } else {
    throw new Error(data.message)
  }
}

async function handleGroupTypes(){
  const { data } = await fetchGroupTypes()
  if(data.status === 'ok'){
    const stateStore = useStateStore()
    stateStore.groupTypes = data.data as GroupType[]
  } else {
    throw new Error(data.message)
  }
}

setInterval(async () => {
  const stateStore = useStateStore()
  const configStore = useConfigStore()

  if(stateStore.shouldAuthenticate){
    stateStore.shouldAuthenticate = false

    const { data: responseData } = await authenticateChurchTools(configStore.ctUrl, configStore.ctToken)
    stateStore.authWasExecuted = true

    if(responseData.status === 'ok'){
      if(isResponseEmpty(responseData as Response<CtAuthenticationResponse>)){
        handleEmptyResponse()
      } else {
        await handleAuthSuccess(responseData as Response<CtAuthenticationResponse>)
        await Promise.all([
          handleGroupTypes(),
          handleGroups()
        ]);
      }
    } else {
      handleErrorResponse(responseData as Response<ErrorResponse>)
    }
  }
}, 1000)