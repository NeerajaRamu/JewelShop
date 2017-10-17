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

Auth::routes();

Route::group(['middleware' => 'auth'], function () {
    Route::get('/', 'Auth\LoginController@index')->name('home');
});
//Route::get('/', function () {
//    return view('welcome');
//});
//
//Auth::routes();
//
//Route::get('/home', 'HomeController@index')->name('home');
//
Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::get('/profile', ['as' => 'profile', 'uses' => 'ProfileController@index']);
Route::get('/users', ['as' => 'users', 'uses' => 'UsersController@index']);
//Route::get('/auth/register', ['as' => 'auth/register', 'uses' => 'RegisterController@index']);
Route::get('/CreateSale', ['as' => 'CreateSale', 'uses' => 'SalesController@create']);
Route::get('/MySales', ['as' => 'MySales', 'uses' => 'SalesController@index']);
Route::get('/ShopSales', ['as' => 'ShopSales', 'uses' => 'SalesController@index']);
//Route::get('auth/logout', 'Auth\AuthController@logout');
//Route::get('/auth/logout', 'Auth\AuthController@logout');
//Route::get('logout', ['as' => 'logout', 'uses' => 'Auth\AuthController@getLogout']);