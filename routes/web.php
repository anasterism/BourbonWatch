<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    $bourbons = App\Models\Bourbon::all();

    foreach ($bourbons as $bourbon)
    {
        $result = App\Asterism\OHLQ\Client::fetch($bourbon);

        echo '<pre>';
        print_r($result);
        echo '</pre>';
    }

    return view('welcome');
});
