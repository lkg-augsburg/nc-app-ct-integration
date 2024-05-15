<template>
<div id="ct-integration-prefs" class="section">
  <h2 class="font-bold text-yellow-500">
    {{ t('ctIntegration', 'ChurchTools Integration') }}
  </h2>
  <!-- <ParentComp /> -->
  <ConnectionSection />
</div>
</template>

<script>
import ConnectionSection from './ConnectionSection.vue'
// import ConfigSection from './ConfigSection.vue'
// import ParentComp from './ParentComp.vue'

// import ConfigSection from './ConfigSection.vue'

export default {
  name: 'CtLogin',

  components: {
    // ConfigSection,
    // ParentComp,
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
