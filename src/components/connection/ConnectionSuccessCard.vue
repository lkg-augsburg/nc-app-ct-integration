<template>
<NcNoteCard type="success">
  <p>Connection successfully established</p>
  <!-- For some reason grid-cols-[100px_minmax(0px,_1fr)] doesn't work-->
  <div class="grid gap-1" style="grid-template-columns: 100px 1fr;">
    <div>Id</div>
    <div>{{ id }}</div>
    <div>Mail</div>
    <div>{{ email }}</div>
    <div>Username</div>
    <div>{{ user }}</div>
  </div>
  <div>
    <a :href="`${url}/?q=churchdb#PersonView/searchEntry:%23${id}`"
      class="flex hover:font-bold">
      <OpenInNewIcon :size="20" class="mr-1" />
      Open in ChurchTools
    </a>
  </div>
</NcNoteCard>
</template>
<script>
import NcNoteCard from '@nextcloud/vue/dist/Components/NcNoteCard.js'
import OpenInNewIcon from 'vue-material-design-icons/OpenInNew.vue'

export default {
  name: 'ConnectionSuccessCard',
  components: {
    NcNoteCard,
    OpenInNewIcon,
  },
  props: {
    id: {
      type: Number,
      required: true,
    },
    email: {
      type: String,
      required: true,
    },
    user: {
      type: String,
      required: true,
    },
    url: {
      type: String,
      required: true,
    },
  },
}
</script>
