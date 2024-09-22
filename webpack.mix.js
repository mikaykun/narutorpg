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
    .minify([
        'public/Menus/js/allgdats.js',
        'public/Menus/js/functions.js',
        'public/Menus/js/image_resize.js',
        'public/Menus/js/netz.js',
    ])
    .setPublicPath('public');

// New System
mix
    .sass('assets/styles/global.scss', 'css')
    .setPublicPath('public');
