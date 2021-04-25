const mix = require("laravel-mix");
const tailwindcss = require("tailwindcss");
const CopyPlugin = require("copy-webpack-plugin");

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
    .js("resources/js/front.js", "public/js")
    .extract(["tinymce"])
    .vue()
    .less("resources/less/app.less", "public/css")
    .less("resources/less/front.less", "public/css")
    .options({
        postCss: [tailwindcss("./tailwind.config.js")],
    })
    .webpackConfig({
        plugins: [
            new CopyPlugin({
                patterns: [
                    { from: "node_modules/tinymce/skins", to: "js/skins" },
                ],
            }),
        ],
    })
    .version();
