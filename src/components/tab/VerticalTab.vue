<template>
<div class="flex">
  <div :key="tabLabelWrapperKey" class="flex flex-col w-48 border-r border-gray-300">
    <div v-for="(tab, index) in tabs"
      :key="index"
      :class="['px-4 py-2 cursor-pointer border-b border-gray-300', { 'bg-gray-200 font-bold': selectedIndex === index }]"
      @click="selectTab(index)">
      {{ tab.label }}
    </div>
  </div>
  <div class="flex-1 p-4">
    <slot />
  </div>
</div>
</template>

<script>

export default {
  name: 'VerticalTab',
  components: {
  },
  data() {
    return {
      selectedIndex: 0,
      tabLabelWrapperKey: 0,
    }
  },
  computed: {
    tabs() {
      return this.$children
    },
  },
  mounted() {
    this.selectTab(0)
  },
  methods: {
    forceRerender() {
      this.tabLabelWrapperKey += 1
    },
    selectTab(index) {
      const tab = this.tabs[index]
      if (tab) {
        this.forceRerender()
      }
      this.selectedIndex = index
      this.tabs.forEach((tab, i) => {
        tab.isActive = i === index
      })
    },
  },
}
</script>
