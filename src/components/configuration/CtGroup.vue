<template>
  <Card :title="`#${id} ${name}`"
    class="border-gray-700"
    :class="{
      'bg-green-50': groupSyncState
    }"
  >
    <ToggleSwitch
      :model-value="groupSyncState"
      :label="labelGroupSync"
      @update:model-value="handleGroupSyncChange"
      />
    <ToggleSwitch
      :model-value="groupFolderState"
      :label="labelGroupFolder"
      :disabled="groupFolderDisabledState"
      @update:model-value="handleGroupFolderSyncChange"
    />
  </Card>
</template>
<script setup lang="ts">
import { computed } from 'vue';
import Card from '@/components/card/card.vue';
import ToggleSwitch from '@/components/forms/ToggleSwitch.vue';
import { useConfigStore } from '@/stores/useConfigStore';
import { useStateStore } from '@/stores/useStateStore';

interface CtGroupProps {
  id: number;
  name: string;
}

const labelGroupSync = 'Group Sync';
const labelGroupFolder = 'Group Folder';

const configStore = useConfigStore();
const stateStore = useStateStore();

const props = defineProps<CtGroupProps>();

const groupSyncState = computed(() => {
  const isGroupChecked = configStore.groupSync.includes(props.id);
  const isGroupTypeChecked = configStore.groupTypeSync.includes(
    stateStore.groups.find(({id}) => id === props.id)?.type || -1
  );
  return isGroupChecked || isGroupTypeChecked;
});
const groupFolderState = computed(() => {
  const isGroupSync = groupSyncState.value === true;
  const isGroupFolderChecked = configStore.groupFolderSync.includes(props.id);
  const isGroupTypeFolderChecked = configStore.groupTypeFolderSync.includes(
    stateStore.groups.find(({id}) => id === props.id)?.type || -1
  );
  return isGroupSync && (isGroupFolderChecked || isGroupTypeFolderChecked);
});
const groupFolderDisabledState = computed(() => groupSyncState.value === false);

function handleGroupSyncChange(isSync: boolean){
  configStore.setGroupSyncStatus(props.id, isSync);
}
function handleGroupFolderSyncChange(isSync: boolean){
  configStore.setGroupFolderSyncStatus(props.id, isSync);
}
</script>