<template>
<ConfigSection title="Connection">
  <InputField id="ctUrl"
    v-model="state.ctUrl"
    placeholder="ChurchTools URL"
    label="ChurchTools URL" />
  <InputField id="ctToken"
    v-model="state.ctToken"
    placeholder="ChurchTools Password / Token"
    label="ChurchTools Password / Token"
    field-type="password" />
  <div class="flex">
    <NcButton :wide="false" @click="testLogin">
      <template #icon>
        <ConnectionIcon :size="20" />
      </template>
      Test Connection
    </NcButton>
    <NcButton :wide="false"
      :disabled="isConnectionTested === false || error !== null"
      @click="saveCredentials">
      <template #icon>
        <ContentSaveIcon :size="20" />
      </template>
      Save credentials
    </NcButton>
  </div>
  <ConnectionErrorCard v-if="(isConnectionTested === true || credentialsSaved === true) && error !== null"
    :status="error.status"
    :status-text="error.statusText"
    :message="error.message"
    :err-html="error.errHtml" />
  <ConnectionSuccessCard v-if="isConnectionTested === true && error === null"
    :id="whoami.id"
    :url="state.ctUrl"
    :email="whoami.email"
    :user="whoami.cmsUserId" />
  <CredentialsSaveSuccessCard v-if="credentialsSaved === true && error === null"
    text="ChurchTools credentials successfully saved." />
</ConfigSection>
</template>
<script>
import { loadState } from '@nextcloud/initial-state'
import { generateUrl } from '@nextcloud/router'
import axios from '@nextcloud/axios'
import NcButton from '@nextcloud/vue/dist/Components/NcButton.js'
import ConnectionIcon from 'vue-material-design-icons/Connection.vue'
import ContentSaveIcon from 'vue-material-design-icons/ContentSave.vue'
import ConfigSection from '../forms/ConfigSection.vue'
import InputField from '../forms/InputField.vue'
import ConnectionErrorCard from './ConnectionErrorCard.vue'
import ConnectionSuccessCard from './ConnectionSuccessCard.vue'
import CredentialsSaveSuccessCard from './CredentialsSaveSuccessCard.vue'

axios.defaults.baseURL = generateUrl('/apps/churchtoolsintegration/api')

export default {
  name: 'ConnectionSection',
  components: {
    ConfigSection,
    ConnectionErrorCard,
    ConnectionSuccessCard,
    CredentialsSaveSuccessCard,
    InputField,
    NcButton,
    ConnectionIcon,
    ContentSaveIcon,
  },
  data: () => ({
    title: 'Connection',
    state: loadState('churchtoolsintegration', 'ct-connection'),
    whoami: null,
    isConnectionTested: false,
    error: null,
    credentialsSaved: false,
  }),
  methods: {
    async saveCredentials() {
      this.isConnectionTested = false
      this.error = null

      try {
        await axios.post('ct-credentials', {
          url: this.state.ctUrl,
          token: this.state.ctToken,
        })
      } catch (e) {
        console.error(e)
        const parser = new DOMParser()
        const errDoc = parser.parseFromString(e.response.data, 'text/html')
        const errDocMsg = errDoc.querySelector('.guest-box.wide')
        this.error = {
          error: e,
          errHtml: errDocMsg.outerHTML,
          code: e.code,
          message: e.message,
          status: e.response.status,
          statusText: e.response.statusText,
        }
      } finally {
        this.credentialsSaved = true
      }
    },
    async testLogin() {
      this.isConnectionTested = false

      try {
        const whoAmIResp = await axios.get('whoami', {
          params: {
            url: this.state.ctUrl,
            token: this.state.ctToken,
          },
        })

        const { id, email, cmsUserId } = whoAmIResp.data.data
        this.whoami = { id, email, cmsUserId }
      } catch (e) {
        console.error(e)

        const parser = new DOMParser()
        const errDoc = parser.parseFromString(e.response.data, 'text/html')
        const errDocMsg = errDoc.querySelector('.guest-box.wide')

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
