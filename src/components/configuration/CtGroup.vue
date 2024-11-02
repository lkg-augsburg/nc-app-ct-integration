<template>
  <Card :title="`#${id} ${name}`"
    class="border-gray-700"
    :class="{
      'bg-green-50': groupSyncState
    }"
  >
    <ToggleSwitch v-model="groupSyncState" :label="labelGroupSync" />
    <ToggleSwitch v-model="groupFolderState" :label="labelGroupFolder" :disabled="true" />
  </Card>
</template>
<script setup lang="ts">
import { ref } from 'vue';
import Card from '@/components/card/card.vue';
import ToggleSwitch from '@/components/forms/ToggleSwitch.vue';

interface CtGroupProps {
  id: number;
  name: string;
}

defineProps<CtGroupProps>()
const groupSyncState = ref(Math.round(Math.random()) === 1)
const labelGroupSync = 'Group Sync'
const groupFolderState = ref(Math.round(Math.random()) === 1)
const labelGroupFolder = 'Group Folder'
</script>