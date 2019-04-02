const mix = require('laravel-mix');

mix.js('resources/js/app.js', 'public/js')
    .sass('resources/sass/app.scss', 'public/css')
    .sass('resources/sass/home.scss', 'public/css')
    .copy('node_modules/bootstrap/dist/css/bootstrap.min.css', 'public/css/vendor')
    .copy('node_modules/jquery/dist/jquery.min.js', 'public/js/vendor')
    .copy('node_modules/bootstrap/dist/js/bootstrap.min.js', 'public/js/vendor');

if (mix.inProduction()) {
    mix.extract().version();
}
