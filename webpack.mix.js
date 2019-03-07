const mix = require('laravel-mix');

mix.js('resources/js/app.js', 'public/js');
    // .sourceMaps();

if (mix.inProduction()) {
    mix.sass('resources/sass/app.scss', 'public/css')
        .sass('resources/sass/home.scss', 'public/css')
        .extract().version();
}
