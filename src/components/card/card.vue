<!-- eslint-disable vue/multi-word-component-names -->
<template>
  <div :class="['border p-4 rounded-md border-solid', typeClasses]">
    <strong class="block mb-2 font-bold">{{ title }}</strong>
    <slot></slot>
  </div>
</template>

<script setup lang="ts">
import { computed, type PropType } from 'vue';

const cardStyleMapping = {
  success: "bg-green-100 text-green-700 border-green-400",
  info: "bg-blue-100 text-blue-700 border-blue-400",
  warning: "bg-yellow-100 text-yellow-700 border-yellow-400",
  danger: "bg-red-100 text-red-700 red-400",
  transparent: "bg-transparent border-transparent",
  default: "bg-gray-100 text-gray-700 border-gray-400",
}

const props = defineProps({
  type: {
    type: String as PropType<'success' | 'info' | 'warning' | 'danger' | 'transparent'>,
    default: 'default',
  },
  title: {
    type: String,
    default: '',
  },
});

const typeClasses = computed(() => {
  if(cardStyleMapping[props.type]){
    return cardStyleMapping[props.type]
  }
  return cardStyleMapping.default;
});
</script>