// SPDX-FileCopyrightText: Sören Liebich <soeren.liebich@gmail.com>
// SPDX-License-Identifier: AGPL-3.0-or-later
module.exports = {
  extends: [
    '@nextcloud',
  ],
  rules: {
    indent: ['error', 2],
    'vue/html-indent': ['error', 2, {
      attribute: 1,
      baseIndent: 0,
      closeBracket: 1,
      alignAttributesVertically: false,
    }],
  },
}
