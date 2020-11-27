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
    return view('welcome');
});

Auth::routes();
Route::get('sales/report/create','SalesController@add');
Route::post('sales/report/create','SalesController@create');
Route::get('clients/create','ClientsController@add');
Route::post('clients/create','ClientsController@create');
Route::get('clients/list','SalesController@list');

Route::get('clients/create2','ClientsController@add2');
Route::post('clients/create2','ClientsController@update');

Route::get('result','SalesController@result');
Route::post('result','SalesController@result');

Route::get('sales/plan','SalesController@plan');
Route::post('sales/plan','SalesController@planSave');

Route::get('sales/planDelete','SalesController@planDelete');
Route::post('sales/planDelete','SalesController@planDelete');

Route::get('clientTop','SalesController@clientTop');

