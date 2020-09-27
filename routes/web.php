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

Route::group(['prefix' => 'admin','middleware' =>'auth'],function(){
    Route::get('news/create','Admin\NewsController@add');
    Route::post('news/create','Admin\NewsController@create');
    
    //問題４
    Route::get('profile/create','Admin\ProfileController@add');
    Route::get('profile/edit','Admin\ProfileController@edit');
    Route::post('profile/create','Admin\ProfileController@create');
    Route::post('profile/edit','Admin\ProfileController@update');
    
    Route::get('news','Admin\NewsController@index');
    Route::get('news/edit','Admin\NewsController@edit');
    Route::post('news/edit','Admin\NewsController@update');
    Route::get('news/delete','Admin\NewsController@delete');
    
});
/*
問題３
「http://XXXXXX.jp/XXX というアクセスが来たときに、 
AAAControllerのbbbというAction に渡すRoutingの設定」を書いてみてください。

　　回答：Route::get('XXX', 'AAAController@bbb');
*/
Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/','NewsController@index');
Route::get('/profile','ProfileController@index')->middleware('auth');
