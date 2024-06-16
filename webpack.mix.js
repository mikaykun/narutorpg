let mix = require('laravel-mix');

mix.options({
    manifest: false,
    processCssUrls: false,
});

// Legacy code
mix
    .js('assets/legacy.js', 'js')
    .sass('assets/styles/legacy.scss', 'css')
    .minify('assets/menu1.js', 'public/js/menu1.js', false)
    .setPublicPath('public');

// New System
mix
    .sass('assets/styles/global.scss', 'css')
    .setPublicPath('public');
