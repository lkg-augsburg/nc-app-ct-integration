<template>
	<div id="ct-integration-prefs" class="section">
		<h2 class="text-yellow-500 font-bold">
			{{ t('ctIntegration', 'ChurchTools Integration') }}
		</h2>
		<div>
			<InputField id="test"
				placeholder="Test Text"
				label="Label for input field"
				@input="debug" />
		</div>
	</div>
</template>

<script>
// import KeyIcon from 'vue-material-design-icons/Key.vue'
// import ArrowRightIcon from 'vue-material-design-icons/ArrowRight.vue'

// import NcLoadingIcon from '@nextcloud/vue/dist/Components/NcLoadingIcon.js'
// import NcButton from '@nextcloud/vue/dist/Components/NcButton.js'
import { loadState } from '@nextcloud/initial-state'
import { generateUrl } from '@nextcloud/router'
import axios from '@nextcloud/axios'
import { showSuccess, showError } from '@nextcloud/dialogs'

import InputField from './InputField.vue'

export default {
	name: 'CtLogin',

	components: {
		// KeyIcon,
		// NcButton,
		// NcLoadingIcon,
		// ArrowRightIcon,
		InputField,
	},

	props: [],

	data() {
		return {
			state: loadState('churchtoolsintegration', 'admin-config'),
			loading: false,
			inputChanged: false,
		}
	},

	computed: {
	},

	watch: {
	},

	mounted() {
	},

	methods: {
		debug(evt) {
			// eslint-disable-next-line no-console
			console.log(evt)
		},
		onSave() {
			this.saveOptions({
				api_key: this.state.api_key,
			})
		},
		saveOptions(values) {
			this.loading = true
			const req = {
				values,
			}
			const url = generateUrl('/apps/pexels/admin-config')
			axios.put(url, req).then((response) => {
				showSuccess(t('pexels', 'Pexels options saved'))
				this.inputChanged = false
			}).catch((error) => {
				showError(
					t('pexels', 'Failed to save Pexels options')
					+ ': ' + (error.response?.data?.error ?? ''),
				)
				console.error(error)
			}).then(() => {
				this.loading = false
			})
		},
	},
}
</script>

<style scoped lang="scss">
#ct-integration-prefs {
	h2,
	.line,
	.settings-hint {
		display: flex;
		align-items: center;
		.icon {
			margin-right: 4px;
		}
	}
}
</style>
