<template>
<div class="pb-4 mb-4 border-b border-gray-400 border-solid">
  <h2 class="ml-2 font-semibold" style="margin-bottom: 0;">
    <NcCheckboxRadioSwitch :checked.sync="isActive" type="switch" @update:checked="onUpdateType">
      {{ label }}
    </NcCheckboxRadioSwitch>
  </h2>
  <h3 v-if="groupType.description">
    {{ groupType.description }}
  </h3>
  <div class="pb-2 mx-4 mb-2 border-b border-gray-300 border-solid">
    <NcCheckboxRadioSwitch :checked.sync="isSelectAll"
      type="switch"
      :disabled="!isActive"
      class="ml-2"
      @update:checked="onSelectAll">
      Select all
    </NcCheckboxRadioSwitch>
  </div>
  <div class="grid grid-cols-4 gap-2 mx-4">
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
    isInit: false,
    hasChanges: false,
  }),
  computed: {
    ...mapState(
      useConfigurationStore,
      {
        ctSyncGroups(store) {
          return store.ctSyncGroups[this.groupType.id]
        },
      }),
  },
  async created() {
    const { id } = this.groupType
    const resp = await axios.get(`ct-group-type/${id}/groups`, {})

    this.groups = resp.data
    const ctSyncGroups = this.ctSyncGroups
    for (const { id } of this.groups) {
      const isActive = ctSyncGroups ? ctSyncGroups.includes(`${id}`) : false
      this.$set(this.groupValues, id, isActive)
    }

    this.isActive = Object.values(this.groupValues).includes(true)
    this.setIsSelectedAll()
    this.isInit = true
  },
  methods: {
    onUpdateType(isActive) {
      this.hasChanges = true
      this.onSelectAll(isActive)
      this.isSelectAll = isActive
    },
    onUpdateGroup() {
      this.hasChanges = true
      this.setIsSelectedAll()
      this.persistGroupTypes()
    },
    onSelectAll(state) {
      this.hasChanges = true
      for (const groupId of Object.keys(this.groupValues)) {
        this.groupValues[groupId] = state
      }
      this.persistGroupTypes()
    },
    setIsSelectedAll() {
      this.isSelectAll = !Object.values(this.groupValues).includes(false)
    },
    persistGroupTypes() {
      const store = useConfigurationStore()
      const groupsToSync = [...Object.entries(this.groupValues)]
        .reduce((acc, val) => {
          if (val[1] === false) {
            return acc
          }
          return [...acc, val[0]]
        }, [])

      store.$patch(state => {
        state.ctSyncGroups[this.groupType.id] = groupsToSync
      })
    },
  },
}
</script>
