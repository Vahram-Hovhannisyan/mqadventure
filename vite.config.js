import {defineConfig} from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js', 'resources/css/tours.css', "resources/js/tours.js",
                'resources/css/gallery.css',
                'resources/js/gallery.js',
                'resources/css/tours-page.css',
                'resources/js/tours-page.js',
                'resources/css/admin.css',
                'resources/css/admin-dashboard.css',
                'resources/js/admin-dashboard.js',
                'resources/js/admin-layout.js'
            ],
            refresh: true,
        }),
        tailwindcss(),
    ],
    server: {
        watch: {
            ignored: ['**/storage/framework/views/**'],
        },
    },
});
