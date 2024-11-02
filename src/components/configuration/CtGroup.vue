<template>
  <Card :title="`#${id} ${name}`"
    class="border-gray-700"
    :class="{
      'bg-green-50': groupSyncState
    }"
  >
    <ToggleSwitch v-model="groupSyncState"
      :label="labelGroupSync"
    />
    <ToggleSwitch v-model="groupFolderState"
      :label="labelGroupFolder"
      :disabled="groupFolderDisabledState"
    />
  </Card>
</template>
<script setup lang="ts">
import { ref, watch } from 'vue';
import Card from '@/components/card/card.vue';
import ToggleSwitch from '@/components/forms/ToggleSwitch.vue';
import { useConfigStore } from '@/stores/useConfigStore';

interface CtGroupProps {
  id: number;
  name: string;
}

const configStore = useConfigStore();
const props = defineProps<CtGroupProps>();
const groupSyncState = ref(false);
const labelGroupSync = 'Group Sync';
const groupFolderState = ref(false);
const groupFolderDisabledState = ref(true);
const labelGroupFolder = 'Group Folder';

watch(groupSyncState, (isSync) => {
  if(!isSync){
    groupFolderState.value = false;
  }
  groupFolderDisabledState.value = !isSync;

  console.log("[GROUP SYNC] ", props.id, props.name, isSync);
  configStore.setGroupSyncStatus(props.id, isSync);
  
})

watch(groupFolderState, (isSync) => {
  console.log("[FOLDER SYNC]", props.id, props.name, isSync);
  configStore.setGroupFolderSyncStatus(props.id, isSync);
})
</script>