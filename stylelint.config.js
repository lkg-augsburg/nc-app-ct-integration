// SPDX-FileCopyrightText: Nextcloud contributors
// SPDX-License-Identifier: AGPL-3.0-or-later
// const stylelintConfig = require('@nextcloud/stylelint-config')

module.exports = {
	extends: ['@nextcloud/stylelint-config'],
	rules: {
		'scss/at-rule-no-unknown': [
			true,
			{
				ignoreAtRules: ['tailwind'],
			},
		],
	},
}
