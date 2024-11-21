import { defineConfig } from 'vite'
import vue from '@vitejs/plugin-vue'

export default defineConfig({
    plugins: [vue()],
    server: {
        port: 3000,
        proxy: {
            '/api': {
                target: 'http://localhost:8008',
                changeOrigin: true,
                // 如果不需要重写路径，可以注释下面这行
                // rewrite: (path) => path.replace(/^\/api/, '')
            }
        }
    }
}) 