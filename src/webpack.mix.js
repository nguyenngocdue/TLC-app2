const mix = require('laravel-mix')
const TerserPlugin = require('terser-webpack-plugin')

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

mix.js('resources/js/app.js', 'public/js')
    .vue()
    .postCss('resources/css/app.css', 'public/css', [
        require('tailwindcss'),
        require('autoprefixer'),
    ])
    .webpackConfig({
        plugins: [
            new TerserPlugin({
                terserOptions: {
                    format: {
                        semicolons: true,
                    },
                },
            }),
        ],
    })
mix.js('resources/js/antd-vue.js', 'public/js').vue()

// mix.js('resources/js/editable-list.js', 'public/js')
// mix.js('resources/js/editable-select.js', 'public/js')
// mix.js('resources/js/editable-table.js', 'public/js')

mix.js('resources/js/number-to-words.js', 'public/js')
mix.js('resources/js/jsdiff.js', 'public/js')
mix.js('resources/js/lazysizes.js', 'public/js')
mix.js('resources/js/lightgallery.js', 'public/js').postCss(
    'resources/css/lightgallery.css',
    'public/css',
)

mix.ts('resources/js/EditableTable/EditableTable3.ts', 'public/js').sourceMaps()
