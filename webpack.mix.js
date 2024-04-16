const mix = require('laravel-mix')
const path = require('path')

const directory = path.basename(path.resolve(__dirname))
const source = `platform/packages/${directory}`
const dist = `public/vendor/core/packages/${directory}`

mix
    .sass(`${source}/resources/sass/data-synchronize.scss`, `${dist}/css`)
    .js(`${source}/resources/js/data-synchronize.js`, `${dist}/js`)

if (mix.inProduction()) {
    mix
        .copy(`${dist}/css/data-synchronize.css`, `${source}/public/css`)
        .copy(`${dist}/js/data-synchronize.js`, `${source}/public/js`)
}
