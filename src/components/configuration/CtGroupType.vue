<template>
  <Card :title="`#${groupType} ${title}`" class="my-1">
    <div class="mb-4">{{ description }}</div>
    <div class="flex gap-4 mb-4">
      <button class="!text-xs !font-normal !min-h-0 !px-2 !py-0" @click="handleDeactivateGroupType">Deactivate</button>
      <ToggleSwitch
        class="inline-block"
        :model-value="allGroupsSync"
        label="Group Sync"
        @update:model-value="handleAllGroupsSyncToggleSwitch"
        />
        <ToggleSwitch
        class="inline-block"
        :model-value="allGroupFoldersSync"
        label="Group Folder Sync"
        @update:model-value="handleAllGroupFoldersSyncToggleSwitch"
        />
    </div>
    <div class="grid grid-cols-4 gap-1">
      <CtGroup v-for="(group, index) in groups" 
        :key="index"
        :id="group.id"
        :name="group.name"
      />
    </div>
  </Card>
</template>
<script setup lang="ts">
import { computed } from 'vue';
import { useStateStore } from '@/stores/useStateStore';
import { useConfigStore } from '@/stores/useConfigStore';
import Card from '@/components/card/card.vue';
import ToggleSwitch from '../forms/ToggleSwitch.vue';
import CtGroup from './CtGroup.vue';

interface CtGroupTypeProps {
  title: string;
  description?: string;
  groupType: number;
}

const props = defineProps<CtGroupTypeProps>();

const stateStore = useStateStore();
const configStore = useConfigStore();
const groups = computed(() => stateStore
  .getGroupsForType(props.groupType)
  .sort(({name: name1}, {name: name2}) => name1.localeCompare(name2))
);

const allGroupsSync = computed(() => {
  return configStore.allGroupSyncChecked(props.groupType);
});
function handleAllGroupsSyncToggleSwitch(){
  configStore.setGroupTypeSyncStatus(props.groupType, !allGroupsSync.value);
}
const allGroupFoldersSync = computed(
  () => {
    const allSyncGroupsChecked = groups.value.map(
      ({id}) => 
      configStore.groupFolderSync
      .includes(id))
      .reduce((acc, val) => acc && val, true);
      return allSyncGroupsChecked
    }
  );
function handleAllGroupFoldersSyncToggleSwitch(){
  configStore.setGroupTypeFolderSyncStatus(props.groupType, !allGroupFoldersSync.value);
}

function handleDeactivateGroupType(){
  configStore.deactivateGroupType(props.groupType);
}

</script>