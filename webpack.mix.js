const {mix} = require('laravel-mix');

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


mix.react('resources/assets/js/app.js', 'public/js')
    .less('resources/assets/less/style.less', 'public/css')
    .less('resources/assets/less/animations.less', 'public/css')
    .less('resources/assets/less/fonts.less', 'public/css')
    .less('resources/assets/less/weekstaten.less', 'public/css')
    .less('resources/assets/less/PDFstyle.less', 'public/css')
    .sourceMaps()

;

    mix.version();
