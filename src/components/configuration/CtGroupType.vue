<template>
  <Card :title="title" class="my-1">
    <div class="mb-4">{{ description }}</div>
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
import { useStateStore } from '@/stores/useStateStore';
import Card from '@/components/card/card.vue';
import { computed } from 'vue';
import CtGroup from './CtGroup.vue';

interface CtGroupTypeProps {
  title: string;
  description?: string;
  groupType: number;
}

const props = defineProps<CtGroupTypeProps>();

const stateStore = useStateStore();
const groups = computed(() => stateStore
  .getGroupsForType(props.groupType)
  .sort(({name: name1}, {name: name2}) => name1.localeCompare(name2))
);

</script>