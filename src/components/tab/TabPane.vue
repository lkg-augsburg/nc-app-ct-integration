<template>
<div v-show="isActive">
  <slot />
</div>
</template>

<script>
export default {
  name: 'TabPane',
  props: {
    label: {
      type: String,
      required: true,
    },
  },
  data() {
    return {
      isActive: false,
    }
  },
}
</script>
