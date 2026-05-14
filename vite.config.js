import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';
import { VitePWA } from 'vite-plugin-pwa';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
            buildDirectory: 'build',
        }),

        tailwindcss(),

        VitePWA({
            registerType: 'autoUpdate',
            includeAssets: ['favicon.ico'],
            manifest: {
                name: 'LPG Distribution',
                short_name: 'LPG',
                description: 'Aplikasi distribusi dan stok LPG',
                theme_color: '#0f172a',
                background_color: '#f8fafc',
                display: 'standalone',
                start_url: '/',
                icons: [
                    {
                        src: '/favicon.ico',
                        sizes: '64x64 32x32 16x16',
                        type: 'image/x-icon'
                    }
                ]
            }
        })
    ],

    server: {
        watch: {
            ignored: ['**/storage/framework/views/**'],
        },
    },
});