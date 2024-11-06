import { defineStore } from "pinia";
import { loadState } from '@nextcloud/initial-state'
import { CT_APP_ID } from "@/constants";
import { useStateStore } from "./useStateStore";

export interface ConfigStoreState {
  ctUrl: string;
  ctToken: string;
  groupSync: number[];
  groupFolderSync: number[];
  groupTypeSync: number[];
  groupTypeFolderSync: number[];
  groupTypeConfigurations: number[];
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
        groupTypeSync: [],
        groupTypeFolderSync: [],
        groupTypeConfigurations: [],
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
    },
    hasGroupTypeConfiguration: state => state.groupTypeConfigurations.length > 0,
    hasDeactivatedGroupTypes: state => state.deactivatedGroupTypes.length > 0,
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
    setAllGroupTypeGroupsSyncStatus(groupType: number, isSync: boolean){
      const stateStore = useStateStore();
      const groups = stateStore.getGroupsForType(groupType).map(({id}) => id);
      if(isSync){
        this.groupSync = Array.from(new Set<number>([...this.groupSync, ...groups]));
      } else {
        this.groupSync = this.groupSync.filter(id => !groups.includes(id));
        this.setAllGroupTypeGroupFolderSyncStatus(groupType, isSync);
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
    setAllGroupTypeGroupFolderSyncStatus(groupType: number, isSync: boolean){
      const stateStore = useStateStore();
      const groups = stateStore.getGroupsForType(groupType).map(({id}) => id);
      if(isSync){
        this.groupFolderSync = [...this.groupFolderSync, ...groups];
        this.setAllGroupTypeGroupsSyncStatus(groupType, isSync);
      } else {
        this.groupFolderSync = this.groupFolderSync.filter(id => !groups.includes(id));
      }
    },
    activateConfigureGroupType(groupType: number){
      const stateStore = useStateStore();
      const typeGroups = stateStore.getGroupsForType(groupType);
      const isGroupTypeSync = !typeGroups
        .map(({id}) => this.groupSync.includes(id))
        .includes(false)
      ;
      console.log(groupType, isGroupTypeSync);
      if(isGroupTypeSync){
        this.groupTypeSync = Array.from(new Set([...this.groupTypeFolderSync, groupType]))
      }
      const isGroupTypeFolderSync = !typeGroups
        .map(({id}) => this.groupFolderSync.includes(id))
        .includes(false)
      ;
      console.log(groupType, isGroupTypeFolderSync);

      if(isGroupTypeFolderSync){
        this.groupTypeFolderSync = Array.from(new Set([...this.groupTypeFolderSync, groupType]))
      }

      this.setAllGroupTypeGroupsSyncStatus(groupType, false);
      this.setAllGroupTypeGroupFolderSyncStatus(groupType, false);
      this.groupTypeConfigurations =  Array.from(new Set([...this.groupTypeConfigurations, groupType]));
    },
    deactivateConfigureGroupType(groupType: number){
      const isSyncActive = this.groupTypeSync.includes(groupType);
      const isFolderSyncActive = this.groupTypeFolderSync.includes(groupType);

      this.setAllGroupTypeGroupsSyncStatus(groupType, isSyncActive);
      this.setAllGroupTypeGroupFolderSyncStatus(groupType, isFolderSyncActive);

      this.groupTypeConfigurations = this.groupTypeConfigurations.filter(id => id !== groupType)
      this.groupTypeSync = this.groupTypeSync.filter(id => id !== groupType)
      this.groupTypeFolderSync = this.groupTypeFolderSync.filter(id => id !== groupType)
    },
    setGroupTypeSyncStatus(groupType: number, isSync: boolean){
      const exists = this.groupTypeSync.includes(groupType);
      const addGroup = isSync && !exists;
      const removeGroup = !isSync && exists;

      if(addGroup){
        this.groupTypeSync = Array.from(new Set([...this.groupTypeSync, groupType]));
      }
      if (removeGroup) {
        this.groupTypeSync = this.groupTypeSync.filter(id => id !== groupType);
        this.setGroupTypeFolderSyncStatus(groupType, isSync);
      }
    },
    setGroupTypeFolderSyncStatus(groupType: number, isSync: boolean){
      const exists = this.groupTypeFolderSync.includes(groupType);
      const addGroup = isSync && !exists;
      const removeGroup = !isSync && exists;

      if(addGroup){
        this.groupTypeFolderSync = [...this.groupTypeFolderSync, groupType].sort();
        this.setGroupTypeSyncStatus(groupType, isSync);
      }
      if (removeGroup) {
        this.groupTypeFolderSync = this.groupTypeFolderSync.filter(id => id !== groupType);
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