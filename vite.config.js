import { fileURLToPath, URL } from 'url';
import { defineConfig, loadEnv, splitVendorChunkPlugin } from 'vite';
import laravel, { refreshPaths } from 'laravel-vite-plugin';

let env;

// prettier-ignore
export default defineConfig(({ command, mode }) => {
  env = loadEnv(mode, process.cwd(), '');

  return {
    plugins: [
      splitVendorChunkPlugin(),
      laravel({
        input: ['resources/js/app.js'],
        refresh: [...refreshPaths, 'app/Livewire', 'resources/*'],
        detectTls: env.VITE_DOMAIN,
      }),
    ],
    resolve: {
      alias: {
        livewire: fileURLToPath(new URL('./vendor/livewire/livewire/dist/livewire.esm', import.meta.url)),
      },
    },
  };
});
