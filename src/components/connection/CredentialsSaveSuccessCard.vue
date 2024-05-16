<template>
<NcNoteCard type="success" />
</template>
<script>
import NcNoteCard from '@nextcloud/vue/dist/Components/NcNoteCard.js'

export default {
  name: 'CredentialsSaveSuccessCard',
  components: {
    NcNoteCard,
  },
  props: {
    text: { type: String, required: true },
  },
}
</script>
