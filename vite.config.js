import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
  plugins: [
    laravel({
      input: [
        'resources/scss/custom.scss',
        'resources/js/app.js',
        'resources/js/pages/admin-articles-index.js',
        'resources/js/pages/admin-comments-index.js',
        'resources/js/admin-forms.js',
        'resources/js/pages/servizi-index.js',
        'resources/js/pages/blog-index.js',
      ],
      refresh: true,
    }),
  ],
});
//
