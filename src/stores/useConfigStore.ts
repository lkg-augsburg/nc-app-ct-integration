import { defineStore } from "pinia";
import { loadState } from '@nextcloud/initial-state'
import { CT_APP_ID } from "@/constants";

export interface ConfigStoreState {
  ctUrl: string;
  ctToken: string;
  groupSync: number[];
  groupFolderSync: number[];
}

export const useConfigStore = defineStore('config', {
  state: (): ConfigStoreState => ({
    ...loadState<ConfigStoreState>(
      CT_APP_ID, 
      'configuration', 
      {
        ctUrl: '',
        ctToken: '',
        groupSync: [],
        groupFolderSync: [],
      }
    )
  }),
  actions: {
    setGroupSyncStatus(groupId: number, isSync: boolean){
      const exists = this.groupSync.includes(groupId);
      const addGroup = isSync && !exists;
      const removeGroup = !isSync && exists;
      if(addGroup){
        this.groupSync.push(groupId);
      }
      if (removeGroup) {
        this.groupSync = this.groupSync.filter(id => id !== groupId);
      }
    },
    setGroupFolderSyncStatus(groupId: number, isSync: boolean){
      const exists = this.groupFolderSync.includes(groupId);
      const addGroup = isSync && !exists;
      const removeGroup = !isSync && exists;
      if(addGroup){
        this.groupFolderSync.push(groupId);
      }
      if (removeGroup) {
        this.groupFolderSync = this.groupFolderSync.filter(id => id !== groupId);
      }
    }
  },
})