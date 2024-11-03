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
  <ConfigSection title="Configuration" class="m-2">
    <CtGroupType v-for="(groupType, index) in sortedGroupTypes"
      :key="index"
      :title="groupType.name"
      :description="groupType.description"
      :group-type="groupType.id"
    />
    <div v-if="configStore.deactivatedGroupTypes.length > 0">
      <Card title="Deactivated Group Types" class="my-1 bg-red-50">
        <div class="grid grid-cols-[75px_1fr] items-center">
          <template v-for="({name, id}, index) in deactivatedGroupTypes" :key="index">
            <div><button class="!text-xs !font-normal !min-h-0 !px-2 !py-0" @click="activateGroupType(id)">Activate</button></div>
            <div>{{ name }}</div>
          </template>
        </div>
      </Card>
    </div>
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

const configStore = useConfigStore()
const stateStore = useStateStore()
const { ctToken, ctUrl } = storeToRefs(configStore)
const { authWasExecuted, authErrorMessage, authSuccessful } = storeToRefs(stateStore)

const cardTitle = computed(() => `Authorization ${authSuccessful.value ? 'successful' : 'failed'}`)
const cardType = computed(() => authSuccessful.value ? 'success' : 'danger')
const sortedGroupTypes = computed(
  () => [...stateStore.getNonEmptyGroupTypes]
    .filter(({id}) => !configStore.deactivatedGroupTypes.includes(id))
    .sort(({name: name1}, {name: name2}) => name1.localeCompare(name2))
);
const deactivatedGroupTypes = computed(() => {
  return stateStore.groupTypes.filter(({id}) => configStore.deactivatedGroupTypes.includes(id))
});
function activateGroupType(groupType: number){
  configStore.activateGroupType(groupType);
}
</script>