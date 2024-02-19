let mix = require('laravel-mix');

mix.js('public/src/app.js', 'js')
   .sass('public/src/app.scss', 'css')
   .setPublicPath('public/assets');