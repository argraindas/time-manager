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


// also in layout file you need to change app.js from asset() to mix()
//
// mix.options({
//     hmrOptions: {
//         host: 'project.pricer',  // site's host name - add this to etc/host file
//     }
// });
//
// // // fix css files 404 issue
// mix.webpackConfig({
//     // add any webpack dev server config here
//     devServer: {
//         proxy: {
//             host: '0.0.0.0',  // host machine ip - add this to etc/host file
//             port: 8080,
//         },
//         watchOptions:{
//             aggregateTimeout:200,
//             poll:5000
//         },
//
//     }
// });
