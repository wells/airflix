const { mix } = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.js('resources/assets/js/app.js', 'public/js')
    .extract([
        'axios',
        'babel-polyfill',
        'chart.js',
        'lodash',
        'moment',
        'moment-range',
        'vue',
        'vue-moment',
        'vue-router',
        'vuex',
        'vuex-router-sync'
    ])
    .sass('resources/assets/sass/app.scss', 'public/css')
    .sourceMaps()
    .version();
