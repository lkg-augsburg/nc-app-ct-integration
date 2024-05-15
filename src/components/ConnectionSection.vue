<template>
<ConfigSection title="Connection">
  <InputField id="ctUrl"
    placeholder="ChurchTools URL"
    label="ChurchTools URL"
    :value="state.ctUrl" />
  <InputField id="ctUser"
    placeholder="ChurchTools User Name"
    label="ChurchTools User Name"
    :value="state.ctUser" />
  <InputField id="ctPw"
    placeholder="ChurchTools Password / Token"
    label="ChurchTools Password / Token"
    :value="state.ctPw"
    field-type="password" />
  <div>
    <NcButton :wide="true" @click="testLogin">
      <template #icon>
        <ConnectionIcon :size="20" />
      </template>
      Test Connection
    </NcButton>
  </div>
  <template v-if="isConnectionTested === true && connectionOk !== true">
    <NcNoteCard type="warning">
      <p>{{ error.status }} {{ error.statusText }}</p>
      <p>{{ error.message }}</p>
    </NcNoteCard>
    <div>
      <!-- eslint-disable-next-line vue/no-v-html -->
      <span v-html="error.errHtml" />
    </div>
  </template>
  <NcNoteCard v-if="isConnectionTested === true && connectionOk === true"
    type="success">
    <p>Connection successfully established</p>
    <!-- For some reason grid-cols-[100px_minmax(0px,_1fr)] doesn't work-->
    <div class="grid gap-1" style="grid-template-columns: 100px 1fr;">
      <div>Id</div>
      <div>{{ whoami.id }}</div>
      <div>Mail</div>
      <div>{{ whoami.email }}</div>
      <div>Username</div>
      <div>{{ whoami.cmsUserId }}</div>
    </div>
    <div>
      <a :href="`https://lkg-augsburg.church.tools/?q=churchdb#PersonView/searchEntry:%23${whoami.id}`"
        class="flex hover:font-bold">
        <OpenInNewIcon :size="20" class="mr-1" />
        Open in ChurchTools
      </a>
    </div>
  </NcNoteCard>
</ConfigSection>
</template>
<script>
import { churchtoolsClient, activateLogging } from '@churchtools/churchtools-client'
import { loadState } from '@nextcloud/initial-state'
import { generateUrl } from '@nextcloud/router'
import axios from '@nextcloud/axios'
import NcButton from '@nextcloud/vue/dist/Components/NcButton.js'
import NcNoteCard from '@nextcloud/vue/dist/Components/NcNoteCard.js'
import ConnectionIcon from 'vue-material-design-icons/Connection.vue'
import OpenInNewIcon from 'vue-material-design-icons/OpenInNew.vue'
import ConfigSection from './ConfigSection.vue'
import InputField from './InputField.vue'

activateLogging()
churchtoolsClient.setBaseUrl('https://lkg-augsburg.church.tools')

const baseURL = generateUrl('/apps/churchtoolsintegration/api')
axios.defaults.baseURL = baseURL

export default {
  name: 'ConnectionSection',
  components: {
    ConfigSection,
    InputField,
    NcButton,
    NcNoteCard,
    ConnectionIcon,
    OpenInNewIcon,
  },
  data: () => ({
    title: 'Connection',
    state: loadState('churchtoolsintegration', 'ct-connection'),
    whoami: null,
    isConnectionTested: false,
    connectionOk: false,
    error: null,
  }),
  methods: {
    async testLogin() {
      this.isConnectionTested = false

      try {
        const whoAmIResp = await axios.get('whoami', {
          params: {
            url: this.state.ctUrl,
            token: this.state.ctPw,
          },
        })

        const { id, email, cmsUserId } = whoAmIResp.data.data
        this.whoami = { id, email, cmsUserId }

        this.connectionOk = true
      } catch (e) {
        console.error(e)

        const parser = new DOMParser()
        const errDoc = parser.parseFromString(e.response.data, 'text/html')
        const errDocMsg = errDoc.querySelector('.guest-box.wide')

        this.connectionOk = false
        this.error = {
          error: e,
          errHtml: errDocMsg.outerHTML,
          code: e.code,
          message: e.message,
          status: e.response.status,
          statusText: e.response.statusText,
        }
      } finally {
        this.isConnectionTested = true
      }
    },
  },
}
</script>
