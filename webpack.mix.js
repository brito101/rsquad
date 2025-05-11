const mix = require("laravel-mix");
require("laravel-mix-purgecss");

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

mix.js("resources/js/app.js", "public/js")
    .copy("resources/img", "public/img")
    .sass("resources/sass/app.scss", "public/css")
    .sass("resources/sass/login.scss", "public/css")
    .sass("resources/sass/site.scss", "public/css")
    /** Dependencies */
    .copy(["node_modules/jquery/dist/jquery.min.js"], "public/vendor/jquery/jquery.min.js")
    /** Admin */
    .scripts(["resources/js/company.js"], "public/js/company.js")
    .scripts(["resources/js/address.js"], "public/js/address.js")
    .scripts(["resources/js/phone.js"], "public/js/phone.js")
    .scripts(["resources/js/google2fa.js"], "public/js/google2fa.js")
    .scripts(["resources/js/snow.js"], "public/js/snow.js")
    .scripts(["resources/js/particles.js"], "public/js/particles.js")
    /** Site */
    .scripts(["resources/js/site/plugins/simple-anime.js"], "public/js/simple-anime.js")
    .scripts(["resources/js/site/script.js"], "public/js/script.js")
    .options({
        processCssUrls: false,
    })
    .sourceMaps()
    .purgeCss();
