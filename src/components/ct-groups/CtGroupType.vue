<template>
<div class="pb-4 mb-4 border-b border-gray-500 border-solid">
  <h2 class="ml-2" style="margin-bottom: 0;">
    <NcCheckboxRadioSwitch :checked.sync="isActive" type="switch" @update:checked="onUpdateType">
      {{ label }}
    </NcCheckboxRadioSwitch>
  </h2>
  <h3 v-if="groupType.description">
    {{ groupType.description }}
  </h3>
  <div>
    <NcCheckboxRadioSwitch :checked.sync="isSelectAll"
      type="switch"
      :disabled="!isActive"
      class="ml-2"
      @update:checked="onSelectAll">
      Select all
    </NcCheckboxRadioSwitch>
  </div>
  <div class="grid grid-cols-4 gap-2">
    <NcCheckboxRadioSwitch v-for="group in groups"
      :key="group.id"
      :checked.sync="groupValues[group.id]"
      :disabled="!isActive"
      type="switch"
      class="ml-2"
      @update:checked="(state) => onUpdateGroup(state, group.id)">
      {{ group.name }} ({{ group.id }})
    </NcCheckboxRadioSwitch>
  </div>
</div>
</template>
<script>
import axios from '@nextcloud/axios'
import { mapState } from 'pinia'
import { generateUrl } from '@nextcloud/router'
import NcCheckboxRadioSwitch from '@nextcloud/vue/dist/Components/NcCheckboxRadioSwitch.js'
import { useConfigurationStore } from '../../store/useConfigurationStore.js'

axios.defaults.baseURL = generateUrl('/apps/churchtoolsintegration/api')

export default {
  name: 'CtGroupType',
  components: {
    NcCheckboxRadioSwitch,
  },
  props: {
    label: {
      type: String,
      required: true,
    },
    groupType: {
      type: Object,
      required: true,
    },
  },
  data: () => ({
    groups: [],
    isActive: false,
    isSelectAll: false,
    groupValues: {},
  }),
  computed: {
    // ...mapWritableState(
    //   useConfigurationStore,
    //   [
    //     'ctToken',
    //     'ctUrl',
    //     'ctGroupSyncTag',
    //     'ctGroupSyncTypes',
    //   ]),
    ...mapState(
      useConfigurationStore,
      [
        'ctUrl',
        'ctToken',
      ]),
  },
  async created() {
    const { id } = this.groupType
    const resp = await axios.get(`ct-group-type/${id}/groups`, {})

    // eslint-disable-next-line no-console
    console.log(resp.data)

    this.groups = resp.data
    for (const { id } of this.groups) {
      this.$set(this.groupValues, id, false)
    }
  },
  // beforeMount() {
  //   // eslint-disable-next-line no-console
  //   console.log('beforeMount', JSON.stringify(this.groupType))
  // },
  // mounted() {
  //   // eslint-disable-next-line no-console
  //   console.log('mounted', JSON.stringify(this.groupType))
  // },
  methods: {
    onUpdateType(isActive) {
      // eslint-disable-next-line no-console
      console.log(isActive)

      this.onSelectAll(isActive)
      this.isSelectAll = isActive
    },
    onUpdateGroup(state, groupId) {
      // eslint-disable-next-line no-console
      console.log(state, groupId)

      this.isSelectAll = !Object.values(this.groupValues).includes(false)
    },
    onSelectAll(state) {
      for (const groupId of Object.keys(this.groupValues)) {
        this.groupValues[groupId] = state
      }
    },
  },
}
</script>
