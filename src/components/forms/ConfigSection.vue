<template>
<section class="relative px-2 py-3 mt-3 border border-gray-400 border-solid rounded min-h-4">
  <label v-if="title !== null"
    class="absolute -top-3.5 left-2.5 bg-white px-1">
    {{ title }}
  </label>
  <div>
    <slot />
  </div>
</section>
</template>
<script>
export default {
  name: 'ConfigSection',
  components: {

  },
  props: {
    title: {
      type: String,
      default: null,
    },
  },
}
</script>
