<!-- eslint-disable vue/multi-word-component-names -->
<template>
  <button @click="handleClick" :class="style" class="h-fit"><slot /></button>
</template>
<script setup lang="ts">
import { computed } from 'vue';


type ButtonSizeType = 'small' | 'medium' | 'large'
interface ToggleSwitchProps {
  size?: ButtonSizeType;
}

const props = withDefaults(defineProps<ToggleSwitchProps>(), {
  size: 'medium'
});

const styles = {
  small: '!text-xs !font-normal !h-fit !min-h-0 !px-4 !py-0',
  medium: '!text-base !font-normal !h-fit !min-h-0 !px-4 !py-1',
  large: '!text-lg !font-normal !h-fit !min-h-0 !px-4 !py-2',
}
const style = computed(() => styles[props.size]);

const emit = defineEmits(['click']);

function handleClick(){
  emit('click');
}
</script>