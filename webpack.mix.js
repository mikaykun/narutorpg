// webpack.mix.js

let mix = require('laravel-mix');

mix.setPublicPath('public');

mix.sass('resources/scss/legacy.scss', 'css');

mix.js('resources/js/legacy.js', 'js');
