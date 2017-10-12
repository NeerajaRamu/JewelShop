<?php

Route::group(['prefix' => 'auth', 'namespace' => 'Auth', 'as' => 'auth.', 'middleware' => ['csrf']], function () {
    /*
    Route::get('signup', [
        'as'   => 'register',
        'uses' => 'AuthController@signup'
    ]);

    Route::post('register', [
        'as'   => 'register',
        'uses' => 'AuthController@register'
    ]);
    */

    Route::get('login', [
        'as'   => 'login',
        'uses' => 'AuthController@getLogin',
    ]);

    Route::post('login', [
        'as'   => 'login',
        'uses' => 'AuthController@postLogin',
    ]);

    Route::get('logout', [
        'as'   => 'logout',
        'uses' => 'AuthController@getLogout',
    ]);
});