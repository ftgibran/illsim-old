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

Route::get('api/network', function (Request $request) {

	$data = $request->all();
	$data['illsim']['simulation']['step'] *= 1;

	$collection = collect($data['factory']['node']['groups']);

	$data['factory']['node']['groups'] = $collection->transform(function($item, $key) {
		if(isset($item['percent']))
			$item['quantType'] = 'percent';
		else
			$item['quantType'] = 'number';

		return $item;
	})->toArray();

	//

	$collection = collect($data['factory']['node']['rate']);

	$data['factory']['node']['rate'] = $collection->transform(function($item, $key) {
		return [
			"min"=> $item["min"]/100,
			"max"=> $item["max"]/100
		];
	})->toArray();

	//

	$collection = collect($data['factory']['edge']['rate']);

	$data['factory']['edge']['rate'] = $collection->transform(function($item, $key) {
		return [
			"min"=> $item["min"]/100,
			"max"=> $item["max"]/100
		];
	})->toArray();

    return $data;
});
