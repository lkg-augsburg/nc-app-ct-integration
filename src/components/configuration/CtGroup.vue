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
import { useStateStore } from '@/stores/useStateStore';

interface CtGroupProps {
  id: number;
  name: string;
}

const configStore = useConfigStore();
const stateStore = useStateStore();
const props = defineProps<CtGroupProps>();
const groupSyncState = ref(
  configStore.groupSync.includes(props.id) ||
  configStore.groupTypeSync.includes(
    stateStore.groups.find(({id}) => id === props.id)?.type || -1
  )
);
const labelGroupSync = 'Group Sync';
const groupFolderState = ref(
  groupSyncState.value === true && 
  (
    configStore.groupFolderSync.includes(props.id) ||
    configStore.groupTypeFolderSync.includes(
      stateStore.groups.find(({id}) => id === props.id)?.type || -1
    )
  )
);
const groupFolderDisabledState = ref(groupSyncState.value === false);
const labelGroupFolder = 'Group Folder';

watch(groupSyncState, (isSync) => {
  if(!isSync){
    groupFolderState.value = false;
  }
  groupFolderDisabledState.value = !isSync;
  configStore.setGroupSyncStatus(props.id, isSync);
});

watch(groupFolderState, (isSync) => {
  configStore.setGroupFolderSyncStatus(props.id, isSync);
});
</script>