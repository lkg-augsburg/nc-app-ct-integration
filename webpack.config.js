const path = require('path')
// we extend the Nextcloud webpack config
const webpackConfig = require('@nextcloud/webpack-vue-config')
// this is to enable eslint and stylelint during compilation
const ESLintPlugin = require('eslint-webpack-plugin')
const StyleLintPlugin = require('stylelint-webpack-plugin')

const buildMode = process.env.NODE_ENV
const isDev = buildMode === 'development'
webpackConfig.devtool = isDev ? 'cheap-source-map' : 'source-map'

webpackConfig.stats = {
  colors: true,
  modules: false,
}

const appId = 'churchtoolsintegration'
webpackConfig.entry = {
  adminSettings: { import: path.join(__dirname, 'src', 'adminSettings.js'), filename: appId + '-adminSettings.js' },
}

// this enables eslint and stylelint during compilation
webpackConfig.plugins.push(
  new ESLintPlugin({
    extensions: ['js', 'vue'],
    files: 'src',
    failOnError: !isDev,
  }),
  new StyleLintPlugin({
    files: 'src/**/*.{css,scss,vue}',
    failOnError: !isDev,
  }),
)

webpackConfig.module.rules.push({
  test: /\.css$/i,
  use: [
    {
      loader: 'postcss-loader',
      options: {
        postcssOptions: {
          plugins: [
            ['postcss-preset-env', {}],
            require('autoprefixer'),
            require('tailwindcss'),
          ],
        },
      },
    },
  ],
})

module.exports = webpackConfig
