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
	return File::get("../database/data/Config.json");
});

Route::get('api/getFactoryFullRandom', function () {
    return File::get("../database/data/factoryFullRandom.json");
});

Route::get('api/getFactoryUniformFormat', function () {
    return File::get("../database/data/factoryUniformFormat.json");
});

Route::get('api/persistConfig', function (Request $request) {

	$config = File::get("../database/data/Config.json");

	switch ($request['factory.method']) {
		case 'uniform-format':
			$factory = File::get("../database/data/FactoryUniformFormat.json");
			break;
		
		default:
			$factory = File::get("../database/data/FactoryFullRandom.json");
			break;
	}
	
	$default = json_decode($config, true);
	$default['factory'] = json_decode($factory, true);

	$union = array_replace_recursive($default, $request->all());

	array_walk_recursive($union, function (&$item, $key)
	{
		if(str_contains($item, '%'))
			$item = str_replace('%', '', $item) / 100;
	});

	return $union;

});

