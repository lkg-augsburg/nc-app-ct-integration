<template>
  <div class="grid grid-cols-[2.25rem_1fr] gap-2 items-center cursor-pointer"
      @click="internalValue = internalValue === 'true' ? 'false' : 'true' "
      @mouseenter="isHover = true"
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
        class="absolute block w-full overflow-hidden rounded-full cursor-pointer"
        :class="[
          internalValue === 'false' ? 'bg-gray-300' : 'bg-blue-500',
          clzDotHeight,
        ]"
      ></span>
      <div
        class="absolute left-0 transition-transform duration-200 ease-in-out transform rounded-full shadow-md cursor-pointer"
        :class="{ 
          'translate-x-6': internalValue === 'true',
          'bg-gray-100': isHover && internalValue === 'false',
          'bg-white': !isHover ,
          'bg-blue-300': isHover && internalValue === 'true',
          [clzDotHeight]: true,
          [clzDotWidth]: true,
        }"
      ></div>
    </div>
    <div class="cursor-pointer">Some text</div>
  </div>
</template>

<script setup lang="ts">
import { ref, watch, defineProps, defineEmits } from 'vue';

const props = defineProps<{
  modelValue: boolean;
}>();

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
