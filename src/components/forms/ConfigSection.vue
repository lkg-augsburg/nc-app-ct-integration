<template>
<section class="relative px-2 py-3 mt-6 border border-gray-400 border-solid rounded min-h-4">
  <label v-if="title"
    class="absolute -top-3.5 left-2.5 bg-nc-main-bg px-1">
    {{ title }}
  </label>
  <div>
    <slot />
  </div>
</section>
</template>
<script setup lang="ts">

defineProps<{
  title: string;
}>()
</script>
