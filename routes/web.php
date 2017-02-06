<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/

use Illuminate\Http\Request;

Route::get('/', function () {
    return view('home');
});

Route::get('api/getConfig', function () {
	return File::get(database_path("data/Config.json"));
});

Route::get('api/getFactoryFullRandom', function () {
    return File::get(database_path("data/FactoryFullRandom.json"));
});

Route::get('api/getFactoryUniformFormat', function () {
    return File::get(database_path("data/FactoryUniformFormat.json"));
});

