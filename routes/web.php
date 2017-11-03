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
Route::get('/users/create/', 'UsersController@create')->name('users.create');
Route::post('/users/store/', 'UsersController@store')->name('users.store');
Route::get('/profile', ['as' => 'profile', 'uses' => 'ProfileController@index']);
Route::get('/users', ['as' => 'users', 'uses' => 'UsersController@index']);
Route::get('/users/edit/{id}', [
            'as'   => 'users.edit',
            'uses' => 'UsersController@edit',
        ]);
Route::put('/users/update/{id}', [
            'as'   => 'users.update',
            'uses' => 'UsersController@update',
        ]);

//Route::get('/users/editUser/{id}', 'UsersController@editUser')->name('users.editUser');
//Route::get('/users/edit/{id}', 'UsersController@edit')->name('users.edit');
//Route::get('/users/edit/{id}', 'UsersController@edit')->name('users.edit');
Route::get('/users/destroy/{id}', 'UsersController@destroy')->name('users.destroy');
//Route::get('/users/edit/{id}', 'UsersController@edit')->name('users.edit');
//Route::get('/posts/details/{id}', 'PostsController@details')->name('posts.details');
//Route::get('/users/edit', ['as' => '/users/edit', 'uses' => 'UsersController@edit']);
//Route::get('/auth/register', ['as' => 'auth/register', 'uses' => 'RegisterController@index']);
Route::get('/sales/sales', ['as' => 'sales/sales', 'uses' => 'SalesController@index']);
Route::get('/sales/edit/{id}', 'SalesController@edit')->name('sales.edit');
Route::put('/sales/update/{id}', [
            'as'   => 'sales.update',
            'uses' => 'SalesController@update',
        ]);
Route::delete('/sales.destroy/{id}', 'SalesController@destroy')->name('sales.destroy');
Route::get('/create-sale', ['as' => 'create-sale', 'uses' => 'SalesController@create']);
Route::post('/sales/store/', 'SalesController@store')->name('sales.store');
//Route::post('/sales/store', ['as' => 'sales/store', 'uses' => 'SalesController@store']);
Route::get('/my-sales', ['as' => 'my-sales', 'uses' => 'SalesController@index']);
Route::get('/shop-sales', ['as' => 'shop-sales', 'uses' => 'SalesController@shopSales']);
//Route::get('auth/logout', 'Auth\AuthController@logout');
//Route::get('/auth/logout', 'Auth\AuthController@logout');
//Route::get('logout', ['as' => 'logout', 'uses' => 'Auth\AuthController@getLogout']);