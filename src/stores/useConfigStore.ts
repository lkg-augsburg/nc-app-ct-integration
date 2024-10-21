import { defineStore } from "pinia";
import { loadState } from '@nextcloud/initial-state'
import { CT_APP_ID } from "@/constants";

export interface ConfigStoreState {
  ctUrl: string;
  ctToken: string;
}

export const useConfigStore = defineStore('config', {
  state: (): ConfigStoreState => loadState<ConfigStoreState>(
    CT_APP_ID, 
    'configuration', 
    {
      ctUrl: '',
      ctToken: '',
  })
})