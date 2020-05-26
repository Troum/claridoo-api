<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('city/{postcode}', 'APIController@postcode');
Route::get('pricing/{postcode}/{quantity}', 'APIController@pricing');
Route::get('validate-iban/{iban}', 'APIController@iban');
Route::post('step-one', 'APIController@stepOne');
Route::get('validate-phone/{phone}', 'APIController@phone');
Route::get('supplier/{supplier}', 'APIController@supplier');
