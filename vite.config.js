import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
                'resources/js/app-csp-test.js',
                'resources/js/landing.js',
                'resources/js/map-index.js',
            ],
            refresh: true,
        }),
    ],
    server: {
        host: 'localhost',  // IPv4のlocalhostを明示的に指定
        port: 5173,
        strictPort: false,
        hmr: {
            host: 'localhost'  // HMRもIPv4を使用
        }
    }
});
