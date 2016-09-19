const elixir = require('laravel-elixir');

require('laravel-elixir-vue');

/*
 |--------------------------------------------------------------------------
 | Elixir Asset Management
 |--------------------------------------------------------------------------
 |
 | Elixir provides a clean, fluent API for defining some basic Gulp tasks
 | for your Laravel application. By default, we are compiling the Sass
 | file for our application, as well as publishing vendor resources.
 |
 */

elixir(function(mix) {

    //Materialize
    mix.copy('node_modules/materialize-css/dist/js/materialize.min.js',
        'public/js/vendor/materialize/materialize.min.js'
    );

    //Vis
    mix.copy(
        'node_modules/vis/dist/vis.min.css',
        'public/css/vendor/vis/vis.min.css'
    );
    mix.copy(
        'node_modules/vis/dist/vis.min.js',
        'public/js/vendor/vis/vis.min.js'
    );
    mix.copy(
        'node_modules/vis/dist/img/**',
        'public/css/img/'
    );

    //Font Awesome
    mix.copy('bower_components/font-awesome/fonts',
        'public/fonts'
    );

    //Keen UI
    mix.copy('node_modules/keen-ui/dist/min/keen-ui.min.css',
        'public/css/vendor/keen-ui/keen-ui.min.css'
    );

    //CSS Utils
    mix.copy('bower_components/css-utils/utils.css',
        'public/css/vendor/css-utils/utils.css'
    );

    mix.sass('app.scss')
        .webpack('app.js');
});