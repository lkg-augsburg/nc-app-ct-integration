<template>
<NcNoteCard type="success">
  {{ text }}
</NcNoteCard>
</template>
<script>
import NcNoteCard from '@nextcloud/vue/dist/Components/NcNoteCard.js'

export default {
  name: 'SuccessCard',
  components: {
    NcNoteCard,
  },
  props: {
    text: { type: String, required: true },
  },
}
</script>
