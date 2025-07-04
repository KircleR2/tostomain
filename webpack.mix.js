const mix = require('laravel-mix');
require('mix-tailwindcss');
require('laravel-vue-i18n/mix');
/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel applications. By default, we are compiling the CSS
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.i18n()
mix.js('resources/js/app.js', 'public/js')
    .vue()
    .sass('resources/css/app.scss', 'public/css').tailwind()
    .copyDirectory('resources/images', 'public/images')
    .copyDirectory('resources/fonts', 'public/fonts')
    .sourceMaps();
