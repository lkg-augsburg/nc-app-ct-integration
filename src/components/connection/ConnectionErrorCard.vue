<template>
<div>
  <NcNoteCard type="warning">
    <p>{{ status }} {{ statusText }}</p>
    <p>{{ message }}</p>
  </NcNoteCard>
  <div v-if="errHtml !== null" class="overflow-scroll">
    <!-- eslint-disable-next-line vue/no-v-html -->
    <span v-html="safeHtml(errHtml)" />
  </div>
</div>
</template>
<script>
import NcNoteCard from '@nextcloud/vue/dist/Components/NcNoteCard.js'

export default {
  name: 'ConnectionErrorCard',
  components: {
    NcNoteCard,
  },
  props: {
    status: {
      type: Number,
      required: true,
    },
    statusText: {
      type: String,
      required: true,
    },
    message: {
      type: String,
      required: true,
    },
    errHtml: {
      type: String,
      default: null,
    },
  },
  methods: {
    safeHtml(htmlString) {
      return htmlString
        .replace(/<(script|style)([^>]*)>/gm, '&lt;$1$2&gt;')
        .replace(/<\/(script|style).*>/gm, '&lt;$1&gt;')
    },
  },
}
</script>
