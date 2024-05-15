<template>
<div>
  <h1 class="font-bold text-9xl">
    Parent Component
  </h1>
  <ChildComp>Hello World</ChildComp>
  <ChildComp>Foo Bar</ChildComp>
  <ConfigSection title="Config Section">
    Config Section Slot Content
  </ConfigSection>
</div>
</template>
<script>

import ChildComp from './ChildComp.vue'
import ConfigSection from './ConfigSection.vue'

export default {
  name: 'ParentComp',
  components: {
    ChildComp,
    ConfigSection,
  },
}
</script>
