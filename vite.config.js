import { defineConfig } from 'vite';
import vitePluginRequire from "vite-plugin-require";
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        vitePluginRequire(),
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js'
            ],
            refresh: true,
        }),
    ],
});
