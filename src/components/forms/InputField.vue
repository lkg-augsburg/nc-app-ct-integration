<template>
  <div class="flex">
  <label class="inline-block w-48" :for="`ct-${id}`">{{ label }}</label>
  <input :id="`ct-${id}`"
    class="w-96"
    :type="fieldType"
    :placeholder="placeholder"
    :value="modelValue"
    @input="onInput"
    >
</div>
</template>
<script setup lang="ts">
const emit = defineEmits(['update:modelValue']);
const {
  label = '',
  placeholder = '',
  fieldType = 'text',
  modelValue = '',
 } = defineProps<{
  id: string;
  label?: string;
  placeholder?: string;
  fieldType?: string;
  modelValue?: string;
}>()

function onInput(evt: Event){
  emit('update:modelValue', (evt.target as HTMLInputElement).value)
}
</script>