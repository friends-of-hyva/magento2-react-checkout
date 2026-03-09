import { defineConfig, loadEnv } from 'vite'
import react from '@vitejs/plugin-react'

export default defineConfig(async ({ mode }) => {
  const env = loadEnv(mode, process.cwd(), 'HYVA_')

  const plugins = [react()]

  if (mode === 'production') {
    const { visualizer } = await import('rollup-plugin-visualizer')
    plugins.push(visualizer())
  }

  const newConfig = {
    build: {
      cssCodeSplit: false,
      sourcemap: true,
      outDir: '../view/frontend/web',
      emptyOutDir: false,
    },
    envPrefix: 'HYVA_',
    plugins,
    server: {
      port: 3000,
      proxy: {
        '/backend': {
          target: env.HYVA_BASE_URL || 'https://demo.hyva.io',
          changeOrigin: true,
          secure: false,
          rewrite: (path) => path.replace(/^\/backend/, ''),
        },
      },
    },
  }

  if (mode === 'production') {
    return {
      ...newConfig,
      build: {
        ...newConfig.build,
        rollupOptions: {
          output: {
            entryFileNames: 'js/react-checkout.js',
            chunkFileNames: 'js/[name].js',
            assetFileNames: (assetInfo) => {
              if (assetInfo.name?.endsWith('.css')) {
                return 'css/styles.css'
              }
              return assetInfo.name || '[name][extname]'
            },
          },
        },
      },
      resolve: {
        alias: {
          lodash: 'lodash-es',
          react: 'preact/compat',
          'react-dom': 'preact/compat',
        },
      },
    }
  }

  return newConfig
})
