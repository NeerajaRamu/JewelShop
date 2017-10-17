<?php

Route::group(['middleware' => ['auth', 'csrf'], 'namespace' => 'Web\Messaging', 'prefix' => 'messaging'], function () {


    /* Full messaging version route
    Route::get('conversations', [
        'as' => 'messaging.conversations.index',
        'uses' => 'MessagingController@index'
    ]);*/

    Route::get('conversations/users', [
        'as' => 'messaging.conversations.users',
        'uses' => 'MessagingController@users'
    ]);

    /* Full messaging version route
    Route::get('conversations/create', [
        'as' => 'messaging.conversations.create',
        'uses' => 'MessagingController@create'
    ]);*/

    Route::post('conversations/create', [
        'as' => 'messaging.conversations.store',
        'uses' => 'MessagingController@store'
    ]);

    /* Full messaging version route
    Route::get('conversations/{conversation_id}/', [
        'as' => 'messaging.conversations.show',
        'uses' => 'MessagingController@show'
    ]);*/

    Route::post('conversations/{conversation_id}/send/', [
        'as' => 'messaging.conversations.send',
        'uses' => 'MessagingController@sendMessage'
    ]);

    Route::get('conversations/{conversation_id}/updates', [
        'as' => 'messaging.conversations.updates',

        'uses' => 'MessagingController@updates'
    ]);

    Route::get('/', [
        'as' => 'messaging.basic.index',
        'uses' => 'BasicController@index'
    ]);

    Route::get('/{conversation_id}', [
        'as' => 'messaging.basic.show',
        'uses' => 'BasicController@show'
    ]);

});
