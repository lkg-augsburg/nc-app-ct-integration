<template>
<div id="ct-integration-prefs" class="section">
  <h2 class="font-bold text-yellow-500">
    {{ t('ctIntegration', 'ChurchTools Integration') }}
  </h2>
  <ConnectionSection />
</div>
</template>

<script>
import ConnectionSection from './connection/ConnectionSection.vue'

export default {
  name: 'ChurchToolsIntegration',

  components: {
    ConnectionSection,
  },

  props: [],

  data() {
    return {
      state: {},
    }
  },

  computed: {
  },

  watch: {
  },

  mounted() {
  },

  methods: {
  },
}
</script>
