import { defineConfig } from 'vite';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
  base: './',
  plugins: [tailwindcss()],
  build: {
    outDir: 'assets/dist',
    emptyOutDir: true,
    sourcemap: true,
    chunkSizeWarningLimit: 800,
    rollupOptions: {
      input: 'src/main.js',
      output: {
        entryFileNames: 'main.js',
        chunkFileNames: 'chunks/[name]-[hash].js',
        assetFileNames: 'main.css', // CSS becomes main.css (fixed name)
      },
    },
  },
});