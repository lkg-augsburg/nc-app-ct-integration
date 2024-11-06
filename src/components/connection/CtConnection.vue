<template>
  <ConfigSection title="ChurchTools Connection" class="m-2">
    <InputField id="ctUrl"
    v-model="ctUrl"
    placeholder="ChurchTools URL"
    label="ChurchTools URL" />
    <InputField id="ctToken"
      v-model="ctToken"
      placeholder="ChurchTools Password / Token"
      label="ChurchTools Password / Token"
      field-type="password" />
    <Card :type="cardType" :title="cardTitle" v-if="authWasExecuted" class="mt-2">
      <template v-if="authSuccessful">
          <div class="grid grid-cols-[150px_1fr]">
            <div>Organisation</div>
            <div>{{ stateStore.orgName }} ({{ stateStore.orgShortName }})</div>
            <div>User</div>
            <div>{{ stateStore.userMail }} (#{{ stateStore.userId }} – {{ stateStore.userName }})</div>
          </div>
        </template>
      <div v-if="authErrorMessage">{{ authErrorMessage }}</div>
    </Card>
  </ConfigSection>
  <ConfigSection title="Group Type Configuration" class="m-2">
    <Card v-if="configStore.hasGroupTypeConfiguration" title="Group Type Configurations" class="my-1">
      <div class="grid grid-cols-4">
        <template v-for="({ id, name }, index) in sortedGroupTypeConfigurations" :key="index">
          <div>#{{ id }} {{ name }}</div>
          <div>
              <ToggleSwitch 
                :model-value="groupTypeSyncState(id)"
                label="Group Sync"
                @update:model-value="state => handleGroupTypeSyncChange(id, state)"
              />
          </div>
          <div>
              <ToggleSwitch 
                :model-value="groupTypeFolderSyncState(id)"
                label="Group Folder Sync"
                @update:model-value="state => handleGroupTypeFolderSyncChange(id, state)"
              />
          </div>
          <div>
            <Button 
              size="small"
              @click="handleDeactivateConfigureGroupType(id)"
            >Configure Groups</Button>
          </div>
        </template>
      </div>
    </Card>
    <Card v-if="configStore.hasDeactivatedGroupTypes" title="Deactivated Group Types" class="my-1 bg-red-50">
      <div class="grid grid-cols-[75px_1fr] items-center">
        <template v-for="({name, id}, index) in deactivatedGroupTypes" :key="index">
          <div><button class="!text-xs !font-normal !min-h-0 !px-2 !py-0" @click="activateGroupType(id)">Activate</button></div>
          <div>#{{ id }} {{ name }}</div>
        </template>
      </div>
    </Card>
  </ConfigSection>
  <ConfigSection title="Group Configuration" class="m-2">
    <CtGroupType v-for="(groupType, index) in sortedGroupTypes"
      :key="index"
      :title="groupType.name"
      :description="groupType.description"
      :group-type="groupType.id"
    />
  </ConfigSection>
</template>
<script setup lang="ts">
import { useStateStore } from '@/stores/useStateStore';
import Card from '@/components/card/card.vue';
import ConfigSection from '../forms/ConfigSection.vue'
import InputField from '../forms/InputField.vue'
import { useConfigStore } from '@/stores/useConfigStore';
import { storeToRefs } from 'pinia';
import { computed } from 'vue';
import CtGroupType from '../configuration/CtGroupType.vue';
import Button from '../forms/Button.vue';
import ToggleSwitch from '../forms/ToggleSwitch.vue';

const configStore = useConfigStore()
const stateStore = useStateStore()
const { ctToken, ctUrl } = storeToRefs(configStore)
const { authWasExecuted, authErrorMessage, authSuccessful } = storeToRefs(stateStore)

const cardTitle = computed(() => `Authorization ${authSuccessful.value ? 'successful' : 'failed'}`)
const cardType = computed(() => authSuccessful.value ? 'success' : 'danger')
const sortedGroupTypes = computed(
  () => [...stateStore.getNonEmptyGroupTypes]
    .filter(({id}) => !configStore.deactivatedGroupTypes.includes(id))
    .filter(({id}) => !configStore.groupTypeConfigurations.includes(id))
    .sort(({name: name1}, {name: name2}) => name1.localeCompare(name2))
);
const sortedGroupTypeConfigurations = computed(() =>
  [...stateStore.getNonEmptyGroupTypes]
  .filter(({id}) => configStore.groupTypeConfigurations.includes(id))
  .sort(({name: name1}, {name: name2}) => name1.localeCompare(name2))
);
const deactivatedGroupTypes = computed(() => {
  return stateStore.groupTypes.filter(({id}) => configStore.deactivatedGroupTypes.includes(id))
});
function activateGroupType(groupType: number){
  configStore.activateGroupType(groupType);
}

function handleDeactivateConfigureGroupType(groupType: number){
  configStore.deactivateConfigureGroupType(groupType);
}

const groupTypeSyncState = computed(() => (id: number) => configStore.groupTypeSync.includes(id))
function handleGroupTypeSyncChange(groupType: number, isSync: boolean){
  configStore.setGroupTypeSyncStatus(groupType, isSync);
}
const groupTypeFolderSyncState = computed(() => (id: number) => configStore.groupTypeFolderSync.includes(id))
function handleGroupTypeFolderSyncChange(groupType: number, isSync: boolean){
  configStore.setGroupTypeFolderSyncStatus(groupType, isSync);
}
</script>