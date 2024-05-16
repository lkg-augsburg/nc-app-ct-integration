<template>
<div class="flex">
  <label class="inline-block w-48" :for="`ct-${id}`">{{ t('ctIntegration', label) }}</label>
  <input :id="`ct-${id}`"
    v-model="currentValue"
    class="w-96"
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
        'password',
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
