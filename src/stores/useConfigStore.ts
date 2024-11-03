import { defineStore } from "pinia";
import { loadState } from '@nextcloud/initial-state'
import { CT_APP_ID } from "@/constants";
import { useStateStore } from "./useStateStore";

export interface ConfigStoreState {
  ctUrl: string;
  ctToken: string;
  groupSync: number[];
  groupTypeSync: number[];
  groupFolderSync: number[];
  groupTypeFolderSync: number[];
  deactivatedGroupTypes: number[];
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
        groupTypeSync: [],
        groupFolderSync: [],
        groupTypeFolderSync: [],
        deactivatedGroupTypes: [],
      }
    )
  }),
  getters: {
    hasAuth: state => state.ctUrl.length > 10 && state.ctToken.length !== 0
  },
  actions: {
    setGroupSyncStatus(groupId: number, isSync: boolean){
      const exists = this.groupSync.includes(groupId);
      const addGroup = isSync && !exists;
      const removeGroup = !isSync && exists;
      if(addGroup){
        this.groupSync = [...this.groupSync, groupId].sort();
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
        this.groupFolderSync = [...this.groupFolderSync, groupId].sort();
      }
      if (removeGroup) {
        this.groupFolderSync = this.groupFolderSync.filter(id => id !== groupId);
      }
    },
    activateGroupType(groupType: number){
      if(this.deactivatedGroupTypes.includes(groupType)){
        this.deactivatedGroupTypes = this.deactivatedGroupTypes.filter(id => id !== groupType);
      }
    },
    deactivateGroupType(groupType: number){
      const stateStore = useStateStore();
      const groupTypeGroups = stateStore.groups.filter(({type}) => type === groupType).map(({id}) => id);
  
      if(!this.deactivatedGroupTypes.includes(groupType)){
        this.deactivatedGroupTypes = [...this.deactivatedGroupTypes, groupType];
      }
  
      if(this.groupTypeSync.includes(groupType)){
        this.groupTypeSync = this.groupTypeSync.filter(id => id !== groupType);
      }
      if(this.groupTypeFolderSync.includes(groupType)){
        this.groupTypeFolderSync = this.groupTypeFolderSync.filter(id => id !== groupType);
      }
      if(this.groupSync.find(id => groupTypeGroups.includes(id)) !== undefined){
        this.groupSync = this.groupSync.filter(id => !groupTypeGroups.includes(id)); 
      }
      if(this.groupFolderSync.find(id => groupTypeGroups.includes(id)) !== undefined){
        this.groupFolderSync = this.groupFolderSync.filter(id => !groupTypeGroups.includes(id));
      }
    }
  },
})