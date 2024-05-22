import { loadState } from '@nextcloud/initial-state'
import { defineStore } from 'pinia'

export const useConfigurationStore = defineStore('configuration', {
  state: () => {
    const state = {
      ...loadState('churchtoolsintegration', 'configuration'),
      connectionOk: false,
      connectionSaved: false,
    }

    state.connectionOk = !!state.ctToken
      && state.ctToken.trim().length > 0
      && !!state.ctUrl
      && state.ctUrl.trim().length > 0
    state.connectionSaved = state.connectionOk

    return state
  },
})
