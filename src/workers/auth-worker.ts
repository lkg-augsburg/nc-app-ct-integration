import type { CtAuthenticationResponse } from "@/models/requests/CtAuthentication";
import type { ErrorResponse } from "@/models/requests/ErrorResponse";
import { authenticateChurchTools } from "@/services/auth-service";
import { persistConfiguration } from "@/services/config-service";
import { useConfigStore } from "@/stores/useConfigStore";
import { useStateStore } from "@/stores/useStateStore";

setInterval(async () => {
  const stateStore = useStateStore()
  const configStore = useConfigStore()

  if(stateStore.shouldAuthenticate){
    stateStore.shouldAuthenticate = false
    
    const { data: responseData } = await authenticateChurchTools(configStore.ctUrl, configStore.ctToken)
    stateStore.authWasExecuted = true

    if(responseData.status === 'ok'){
      const { userId, userName, orgName } = responseData.data as CtAuthenticationResponse

      if(!userId || !userName || !orgName){
        stateStore.authSuccessful = false
        stateStore.authErrorMessage = ''
      } else {
        stateStore.authSuccessful = true
        stateStore.authErrorMessage = ''
        stateStore.setAuthData(responseData.data as CtAuthenticationResponse)
        persistConfiguration(configStore.ctUrl, configStore.ctToken)
      }
    } else {
      console.log(responseData.data)

      stateStore.authSuccessful = false
      stateStore.authErrorMessage = responseData.message

      const errorData = responseData.data as ErrorResponse

      if(errorData.message){
        stateStore.authErrorMessage = errorData.message
      }
    }
  }
}, 2000)