<template>
<ConfigSection title="ChurchTools Groups Configuration">
  <h2 class="text-xl font-bold">
    Tag for syncing groups
  </h2>
  <NcSelect v-model="ctGroupSyncTag"
    input-label="ChurchTools Tags"
    placeholder="Select tag for syncing groups"
    :options="tags"
    :disabled="!connectionOk"
    @input="onInputChanged" />
  <h2 class="mt-4 text-xl font-bold">
    Group types to sync
  </h2>
  <div class="grid grid-cols-5">
    <NcCheckboxRadioSwitch v-for="(groupType, index) in groupTypes"
      :key="index"
      :checked.sync="ctGroupSyncTypes"
      :value="`${groupType.id}`"
      name="ct-group-sync-types"
      @update:checked="onInputChanged">
      {{ groupType.name }}
    </NcCheckboxRadioSwitch>
  </div>
  <div>
    <NcButton :wide="false"
      :disabled="!inputChanged"
      @click="saveGroupConfig">
      <template #icon>
        <ContentSaveIcon :size="20" />
      </template>
      Save Group Config
    </NcButton>
  </div>
  <SuccessCard v-if="settingsSaved" text="Group configuration successfully saved." />
</ConfigSection>
</template>
<script>
// import { loadState } from '@nextcloud/initial-state'
import { generateUrl } from '@nextcloud/router'
import axios from '@nextcloud/axios'
import NcSelect from '@nextcloud/vue/dist/Components/NcSelect.js'
import NcButton from '@nextcloud/vue/dist/Components/NcButton.js'
import NcCheckboxRadioSwitch from '@nextcloud/vue/dist/Components/NcCheckboxRadioSwitch.js'
import ContentSaveIcon from 'vue-material-design-icons/ContentSave.vue'
import ConfigSection from '../forms/ConfigSection.vue'
import SuccessCard from '../cards/SuccessCard.vue'
import { mapState, mapWritableState } from 'pinia'
import { useConfigurationStore } from '../../store/useConfigurationStore.js'

axios.defaults.baseURL = generateUrl('/apps/churchtoolsintegration/api')

export default {
  name: 'CtGroupsSection',
  components: {
    ConfigSection,
    SuccessCard,
    ContentSaveIcon,
    NcButton,
    NcSelect,
    NcCheckboxRadioSwitch,
  },
  data: () => ({
    tags: [],
    groupTypes: [],
    syncTag: null,
    syncGroupTypes: [],
    settingsSaved: false,
    inputChanged: false,
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
    onInputChanged(e) {
      // eslint-disable-next-line no-console
      console.log(e)
      this.inputChanged = true
      this.settingsSaved = false
    },
  },
}
</script>
