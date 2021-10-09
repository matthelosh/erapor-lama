const mix = require('laravel-mix');
let url = process.env.APP_URL;
let fs = require('fs');
/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel applications. By default, we are compiling the CSS
 | file for the application as well as bundling up all the JS files.
 |
 */

mix
    // .options({
    //     hmrOptions: {
    //         host: url,
    //         port: 8080
    //     }    
    // })
    .js('resources/js/app.js', 'public/js').vue()
    .postCss('resources/css/app.css', 'public/css', [
        //
    ]);

// mix.webpackConfig({
//     devServer: {
//         host: url,
//         port:8080,
//         https: {
//           key: fs.readFileSync('/Users/mattsoleh/.config/valet/Certificates/noname.test.key'),
//           cert: fs.readFileSync('/Users/mattsoleh/.config/valet/Certificates/noname.test.crt')
//         }
//     }
// })
