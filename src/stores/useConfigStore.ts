import { defineStore } from "pinia";
import { loadState } from '@nextcloud/initial-state'
import { CT_APP_ID } from "@/constants";
import { useStateStore } from "./useStateStore";

export interface ConfigStoreState {
  ctUrl: string;
  ctToken: string;
  groupSync: number[];
  // groupTypeSync: number[];
  groupFolderSync: number[];
  // groupTypeFolderSync: number[];
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
        groupFolderSync: [],
        deactivatedGroupTypes: [],
      }
    )
  }),
  getters: {
    hasAuth: state => state.ctUrl.length > 10 && state.ctToken.length !== 0,
    allGroupSyncChecked: state => (groupType: number) => {
      const stateStore = useStateStore();
      return stateStore.groups
        .filter(({type}) => type === groupType)
        .map(({id}) => state.groupSync.includes(id))
        .reduce((acc, val) => acc && val, true)
      ;
    }
  },
  actions: {
    setGroupSyncStatus(groupId: number, isSync: boolean){
      const exists = this.groupSync.includes(groupId);
      const addGroup = isSync && !exists;
      const removeGroup = !isSync && exists;

      if(addGroup){
        this.groupSync = Array.from(new Set([...this.groupSync, groupId]));
      }
      if (removeGroup) {
        this.groupSync = this.groupSync.filter(id => id !== groupId);
        this.setGroupFolderSyncStatus(groupId, isSync);
      }
    },
    setGroupTypeSyncStatus(groupType: number, isSync: boolean){
      const stateStore = useStateStore();
      const groups = stateStore.getGroupsForType(groupType).map(({id}) => id);
      if(isSync){
        this.groupSync = Array.from(new Set<number>([...this.groupSync, ...groups]));
      } else {
        this.groupSync = this.groupSync.filter(id => !groups.includes(id));
        this.setGroupTypeFolderSyncStatus(groupType, isSync);
      }
    },
    setGroupFolderSyncStatus(groupId: number, isSync: boolean){
      const exists = this.groupFolderSync.includes(groupId);
      const addGroup = isSync && !exists;
      const removeGroup = !isSync && exists;

      if(addGroup){
        this.groupFolderSync = [...this.groupFolderSync, groupId].sort();
        this.setGroupSyncStatus(groupId, isSync);
      }
      if (removeGroup) {
        this.groupFolderSync = this.groupFolderSync.filter(id => id !== groupId);
      }
    },
    setGroupTypeFolderSyncStatus(groupType: number, isSync: boolean){
      const stateStore = useStateStore();
      const groups = stateStore.getGroupsForType(groupType).map(({id}) => id);
      if(isSync){
        this.groupFolderSync = [...this.groupFolderSync, ...groups];
        this.setGroupTypeSyncStatus(groupType, isSync);
      } else {
        this.groupFolderSync = this.groupFolderSync.filter(id => !groups.includes(id));
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
      if(this.groupSync.find(id => groupTypeGroups.includes(id)) !== undefined){
        this.groupSync = this.groupSync.filter(id => !groupTypeGroups.includes(id)); 
      }
      if(this.groupFolderSync.find(id => groupTypeGroups.includes(id)) !== undefined){
        this.groupFolderSync = this.groupFolderSync.filter(id => !groupTypeGroups.includes(id));
      }
    }
  },
})