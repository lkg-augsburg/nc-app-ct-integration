<template>
  <div class="grid grid-cols-[2.25rem_1fr] gap-2 items-center"
    :class="{
      'cursor-pointer': !disabled,
      'cursor-not-allowed': disabled,
    }"
    @click="handleClick"
    @mouseenter="isHover = disabled ? false : true"
    @mouseleave="isHover = false"
  >
    <div
      class="relative align-middle cursor-pointer select-none"
      :class="[clzDotHeight, fieldWidth]"
      >
      <input
        type="radio"
        :id="idOff"
        :name="name"
        value="false"
        v-model="internalValue"
        class="hidden"
      />
      <input
        type="radio"
        :id="idOn"
        :name="name"
        value="true"
        v-model="internalValue"
        class="hidden"
      />
      <span
        :for="idOff"
        class="absolute block w-full overflow-hidden rounded-full"
        :class="{
          'bg-gray-500' : !disabled && internalValue === 'false',
          'bg-blue-700': !disabled && internalValue === 'true',
          'bg-gray-200' : disabled && internalValue === 'false',
          'bg-blue-400': disabled && internalValue === 'true',
          [clzDotHeight]: true,
          'cursor-pointer': !disabled,
          'cursor-not-allowed': disabled,
        }"
      ></span>
      <div
        class="absolute left-0 transition-transform duration-200 ease-in-out transform rounded-full shadow-md"
        :class="{ 
          'translate-x-6': internalValue === 'true',
          'bg-gray-200': !disabled && isHover && internalValue === 'false',
          'bg-white': !isHover ,
          'bg-blue-300': !disabled && isHover && internalValue === 'true',
          [clzDotHeight]: true,
          [clzDotWidth]: true,
          'cursor-pointer': !disabled,
          'cursor-not-allowed': disabled,
        }"
      ></div>
    </div>
    <div
      class="select-none"
      :class="{
        'cursor-pointer': !disabled,
        'cursor-not-allowed': disabled,
      }"
    >{{ label }}</div>
  </div>
</template>

<script setup lang="ts">
import { ref, watch, defineProps, defineEmits } from 'vue';

interface ToggleSwitchProps {
  modelValue: boolean;
  label?: string;
  disabled?: boolean;
}

const props = withDefaults(defineProps<ToggleSwitchProps>(), {
  label: '',
  disabled: false
});

const emit = defineEmits(['update:modelValue']);

const internalValue = ref(props.modelValue ? 'true' : 'false');
const isHover = ref(false);
const clzDotHeight = 'h-3'
const clzDotWidth = 'w-3'
const fieldWidth = 'w-9'

const uniqueId = Math.random().toString(36).substr(2, 9);
const name = `toggle-${uniqueId}`;
const idOn = `toggle-on-${uniqueId}`;
const idOff = `toggle-off-${uniqueId}`;

function handleClick(){
  if(props.disabled){
    return
  }
  internalValue.value = internalValue.value === 'true' ? 'false' : 'true' 
}

watch(
  () => props.modelValue,
  (newVal) => {
    internalValue.value = newVal ? 'true' : 'false';
  }
);

watch(internalValue, (newVal) => {
  emit('update:modelValue', newVal === 'true');
});
</script>
