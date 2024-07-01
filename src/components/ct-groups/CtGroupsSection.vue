<template>
<ConfigSection title="ChurchTools Groups Configuration">
  <h2 class="text-xl font-bold">
    Group Sync Settings
  </h2>
  <CtGroupsSyncButton />
  <div>
    <CtGroupType v-for="(groupType, index) in groupTypes"
      :key="index"
      :label="groupType.name"
      :group-type="groupType" />
  </div>
</ConfigSection>
</template>
<script>
import { generateUrl } from '@nextcloud/router'
import axios from '@nextcloud/axios'
import ConfigSection from '../forms/ConfigSection.vue'
import { mapState, mapWritableState } from 'pinia'
import { useConfigurationStore } from '../../store/useConfigurationStore.js'
import CtGroupType from './CtGroupType.vue'
import CtGroupsSyncButton from './CtGroupsSyncButton.vue'
axios.defaults.baseURL = generateUrl('/apps/churchtoolsintegration/api')

export default {
  name: 'CtGroupsSection',
  components: {
    ConfigSection,
    CtGroupType,
    CtGroupsSyncButton,
  },
  data: () => ({
    tags: [],
    groupTypes: [],
    syncTag: null,
    syncGroupTypes: [],
    settingsSaved: false,
    inputChanged: false,
    tmpGroupData: null,
    tmpGroupSyncTypes: {},
    vTabKey: 0,
  }),
  computed: {
    ...mapWritableState(
      useConfigurationStore,
      [
        'ctToken',
        'ctUrl',
        'ctGroupSyncTag',
        'ctGroupSyncTypes',
      ]),
    ...mapState(
      useConfigurationStore,
      [
        'connectionOk',
      ]),
  },
  created() {
    if (this.connectionOk) {
      this.loadData()
    }
  },
  mounted() {
    const store = useConfigurationStore()
    store.$subscribe((mutation, state) => {
      if (state.connectionOk === true) {
        this.loadData()
      }
    })
  },
  methods: {
    forceRerender() {
      this.vTabKey += 1
    },
    async loadData() {
      axios.get('ct-tags', {
        params: {
          url: this.ctUrl,
          token: this.ctToken,
        },
      }).then(tags => {
        this.tags = tags.data.data.map(tag => ({ label: tag.name, value: tag.id }))
      })

      await axios.get('ct-group-types', {
        params: {
          url: this.ctUrl,
          token: this.ctToken,
        },
      }).then(groupTypes => {
        this.groupTypes = groupTypes.data.data
        for (const { id } of this.groupTypes) {
          this.$set(this.tmpGroupSyncTypes, id, false)
        }

        this.forceRerender()
      })
    },
    async saveGroupConfig() {
      await axios.post('save-config', {
        ctGroupsSyncTag: this.ctGroupSyncTag,
        ctGroupSyncTypes: this.ctGroupSyncTypes,
      })
      this.settingsSaved = true
      this.inputChanged = false
    },
    async syncGroups() {
      const resp = await axios.post('sync-groups')

      this.tmpGroupData = resp.data
    },
    onInputChanged(e) {
      this.inputChanged = true
      this.settingsSaved = false
    },
    onSwitchChange(id, state) {
      // eslint-disable-next-line no-console
      console.log(id, state)
    },
  },
}
</script>
