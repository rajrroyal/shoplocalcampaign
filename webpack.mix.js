const mix = require('laravel-mix');

mix.js('resources/js/app.js', 'public/js/app.js')
    .js('resources/js/user.js', 'public/js/user.js')
    .js('resources/js/account.js', 'public/js/account.js')
    .sass('resources/sass/app.scss', 'public/css').options({
        processCssUrls: false,
        postCss: [
            require('tailwindcss'),
            require('autoprefixer')
        ],
    })
    .options({
        terser: {
            extractComments: false,
        }
    })
    .version()
;
