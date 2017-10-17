<?php

Route::group(
    [
        'middleware' => ['auth', 'csrf'],
        'namespace'  => 'Web\Messaging',
        'prefix'     => 'notifications',
    ],
    function () {

        Route::get('/', [
            'as'   => 'notifications.index',
            'uses' => 'NotificationController@index',
        ]);

        Route::get('create', [
            'as'   => 'notifications.create',
            'uses' => 'NotificationController@create',
        ]);

        Route::post('create', [
            'as'   => 'notifications.store',
            'uses' => 'NotificationController@store',
        ]);

        Route::get('user-search', [
            'as'   => 'notifications.user-search',
            'uses' => 'NotificationController@userSearch',
        ]);

        Route::get('{conversation_id}/', [
            'as'   => 'notifications.show',
            'uses' => 'NotificationController@show',
        ]);

        Route::post('{conversation_id}/send/', [
            'as'   => 'notifications.send',
            'uses' => 'NotificationController@sendMessage',
        ]);

        Route::get('{conversation_id}/updates', [
            'as' => 'notifications.updates',
            'uses' => 'NotificationController@getUpdates'
        ]);

    }
);
