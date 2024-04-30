<template>
	<div class="field-container">
		<label>{{ t('ctIntegration', label) }}</label>
		<input :id="id"
			v-model="currentValue"
			:type="fieldType"
			:placeholder="t('ctIntegration', placeholder)"
			@input="$emit('input', $event.target.value)">
	</div>
</template>
<script>
import { translate as t } from '@nextcloud/l10n'

export default {
	name: 'InputField',
	components: {

	},

	props: {
		id: {
			type: String,
			required: true,
		},
		fieldType: {
			type: String,
			default: 'text',
			validator: v => [
				'text',
				'checkbox',
				'radio',
				'select',
			].includes(v),
		},
		label: {
			type: String,
			default: t('…'),
		},
		placeholder: {
			type: String,
			default: t('…'),
		},
		value: {
			type: String,
			default: '',
		},
	},

	data() {
		return {
			currentValue: this.value,
			inputChanged: false,
		}
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
