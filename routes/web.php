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

//営業用
Route::get('admin','Admin\SalesController@top');
Route::get('admin/login','Admin\Auth\LoginController@showLoginForm')->name('admin.login');
Route::post('admin/login', 'Admin\Auth\LoginController@login')->name('admin.login');


Route::group(['prefix' => 'admin','middleware' =>'auth:admin'], function() {
    //home
    Route::get('home','Admin\HomeController@index')->name('admin.home');
    //login logout
    
    Route::post('logout', 'Admin\Auth\LoginController@logout')->name('admin.logout');
    //営業用register
    Route::get('register', 'Admin\Auth\RegisterController@showRegisterForm')->name('admin.register');
    Route::post('register', 'Admin\Auth\RegisterController@register')->name('admin.register');
    
    Route::get('clients/list', 'Admin\SalesController@list');
    Route::get('sales/report/create','Admin\SalesController@add');
    Route::post('sales/report/create','Admin\SalesController@create');
    Route::get('clients/create','Admin\ClientsController@add');
    Route::post('clients/create','Admin\ClientsController@create');
    Route::get('clients/list','Admin\SalesController@list');
    
    Route::get('clients/create2','Admin\ClientsController@add2');
    Route::post('clients/create2','Admin\ClientsController@update');
    
    Route::get('result','Admin\SalesController@result');
    Route::post('result','Admin\SalesController@result');
    
    Route::get('sales/plan','Admin\SalesController@plan');
    Route::post('sales/plan','Admin\SalesController@planSave');
    
    Route::get('sales/planDelete','Admin\SalesController@planDelete');
    Route::post('sales/planDelete','Admin\SalesController@planDelete');
    
    Route::get('clientTop','Admin\SalesController@clientTop');
    
    //登録者情報（営業）
    Route::get('sales/adminsList','Admin\SalesController@adminsList');
    Route::get('sales/adminDelete','Admin\SalesController@adminDelete');
    Route::post('sales/adminDelete','Admin\SalesController@adminDelete');
    
    //登録者情報（お客様）
    Route::get('clients/usersList','Admin\ClientsController@usersList');
    Route::get('clients/userDelete','Admin\ClientsController@userDelete');
    Route::post('clients/userDelete','Admin\ClientsController@userDelete');
    
});

//お客様用
Route::get('/','SalesController@top');

Route::group(['middleware' =>'auth'], function() {
    
    Route::get('clients/list', 'SalesController@list');
    
    Route::get('result','SalesController@result');
    Route::post('result','SalesController@result');
    
    Route::get('clientTop','SalesController@clientTop');
    
    
});
