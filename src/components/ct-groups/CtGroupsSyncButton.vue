<template>
<div>
  <button @click="onSave">
    Sync Groups
  </button>
</div>
</template>
<script>
import { syncGroups } from '../../service/group-service.js'

export default {
  name: 'CtGroupsSyncButton',
  methods: {
    async onSave() {
      await syncGroups()
    },
  },
}
</script>
