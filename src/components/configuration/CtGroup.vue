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
      @update:model-value="handleGroupFolderSyncChange"
    />
  </Card>
</template>
<script setup lang="ts">
import { computed } from 'vue';
import Card from '@/components/card/card.vue';
import ToggleSwitch from '@/components/forms/ToggleSwitch.vue';
import { useConfigStore } from '@/stores/useConfigStore';

interface CtGroupProps {
  id: number;
  name: string;
}

const labelGroupSync = 'Group Sync';
const labelGroupFolder = 'Group Folder';

const configStore = useConfigStore();

const props = defineProps<CtGroupProps>();

const groupSyncState = computed(() => configStore.groupSync.includes(props.id));
const groupFolderState = computed(() => configStore.groupFolderSync.includes(props.id));

function handleGroupSyncChange(isSync: boolean){
  configStore.setGroupSyncStatus(props.id, isSync);
}
function handleGroupFolderSyncChange(isSync: boolean){
  configStore.setGroupFolderSyncStatus(props.id, isSync);
}
</script>