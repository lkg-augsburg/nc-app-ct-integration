<template>
  <Card :title="`#${id} ${name}`"
    class="border-gray-700"
    :class="{
      'bg-green-50': state
    }"
  >
    <ToggleSwitch v-model="state" />
    <p>Der Schalter ist {{ state ? 'aktiviert' : 'deaktiviert' }}.</p>

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
const state = ref(false)
</script>