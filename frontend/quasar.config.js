/* eslint-env node */
const { configure } = require('quasar/wrappers')

module.exports = configure(function (/* ctx */) {
  return {
    boot: [
      'pinia',
      'axios'
    ],

    css: [
      'app.scss'
    ],

    extras: [
      'material-icons'
    ],

    build: {
      target: {
        browser: ['es2019', 'edge88', 'firefox78', 'chrome87', 'safari13.1'],
        node: 'node20'
      },
      vueRouterMode: 'history',
      extendViteConf(viteConf) {
        viteConf.resolve = viteConf.resolve ?? {}
        viteConf.resolve.alias = {
          ...(viteConf.resolve.alias ?? {}),
          utils: require('path').join(__dirname, 'src/utils'),
        }
      }
    },

    devServer: {
      open: false,
      port: 9000,
      host: '0.0.0.0',
      proxy: {
        '/api': {
          target: 'http://nginx:80',
          changeOrigin: true
        }
      }
    },

    framework: {
      config: {
        dark: true,
        notify: {
          position: 'top-right',
          timeout: 3000
        }
      },
      plugins: ['Notify', 'Dialog', 'Loading']
    },

    animations: []
  }
})
