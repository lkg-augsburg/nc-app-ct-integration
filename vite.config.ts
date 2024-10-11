import { fileURLToPath, URL } from 'node:url'
import { resolve } from 'node:path'
import { readFileSync } from 'node:fs'

import { defineConfig } from 'vite'
import vue from '@vitejs/plugin-vue'
import vueDevTools from 'vite-plugin-vue-devtools'
import { nodePolyfills } from 'vite-plugin-node-polyfills'
import xml2js from "simple-xml-to-json"

const appInfoPath = resolve(__dirname, 'appinfo', 'info.xml')
const appInfoFile = readFileSync(appInfoPath, {encoding: 'utf-8'});
const appInfo = xml2js.convertXML(appInfoFile)

const appId = appInfo.info.children.find((el: any) => el.id).id.content

// https://vitejs.dev/config/
export default defineConfig({
  plugins: [
    vue(),
    vueDevTools(),
    nodePolyfills(),
  ],
  resolve: {
    alias: {
      '@': fileURLToPath(new URL('./src', import.meta.url))
    }
  },
  build: {
    outDir: 'dist',
    lib: {
      // Pfad zu deiner Haupteingabedatei (TypeScript)
      entry: 'src/adminSettings.ts',
      // Name der Bibliothek (für UMD/IIFE Builds relevant)
      name: 'ChurchTools Integration Admin Settings',
      // Formate, in denen die Bibliothek gebaut werden soll
      formats: ['es'], // oder ['es', 'cjs', 'umd', 'iife'] je nach Bedarf
      // Anpassung des Ausgabedateinamens
      fileName: () => `${appId}-adminSettings.js`,

    },
    rollupOptions: {
      output: {
        // Benennt die generierte CSS-Datei um
        assetFileNames: (assetInfo) => {
          if (assetInfo.name === 'style.css') {
            return `${appId}-adminSettings.css`;
          }
          // Behandelt andere Assets wie gewohnt
          return 'assets/[name]-[hash][extname]';
        },
      },
    }
  },
})
