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
    <Card :type="cardType" :title="cardTitle" v-if="authWasExecuted">
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
</template>
<script setup lang="ts">
import { useStateStore } from '@/stores/useStateStore';
import Card from '../card/card.vue';
import ConfigSection from '../forms/ConfigSection.vue'
import InputField from '../forms/InputField.vue'
import { useConfigStore } from '@/stores/useConfigStore';
import { storeToRefs } from 'pinia';
import { computed } from 'vue';

const configStore = useConfigStore()
const stateStore = useStateStore()
const { ctToken, ctUrl } = storeToRefs(configStore)
const { authWasExecuted, authErrorMessage, authSuccessful } = storeToRefs(stateStore)

const cardTitle = computed(() => `Authorization ${authSuccessful.value ? 'successful' : 'failed'}`)
const cardType = computed(() => authSuccessful.value ? 'success' : 'danger')

</script>