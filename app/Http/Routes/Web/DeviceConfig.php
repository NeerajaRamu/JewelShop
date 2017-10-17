<?php

Route::group(['middleware' => 'auth'], function () {

    Route::group(['namespace' => 'Web'], function () {

        /**
         * Display all device configs
         */
        Route::get('device/config', [
            'as'   => 'device.config',
            'uses' => 'DeviceConfigController@index',
        ]);

        /**
         * Create new config
         */
        Route::post('device/config/create', [
            'as'   => 'device.config.create',
            'uses' => 'DeviceConfigController@store',
        ]);

        /**
         * Edit and update config details
         */
        Route::get('device/config/{id}', [
            'as'   => 'device.config.edit',
            'uses' => 'DeviceConfigController@edit',
        ]);
        Route::put('device/config/{id}/update', [
            'as'   => 'device.config.update',
            'uses' => 'DeviceConfigController@update',
        ]);

        Route::put('device/config/{id}/tag', [
            'as'   => 'device.config.tag',
            'uses' => 'DeviceConfigController@tagConfig',
        ]);


        /**
         * Activate given details record
         */
        Route::get('device/config/{id}/details/{detail_id}/activate', [
            'as'   => 'device.config.details.activate',
            'uses' => 'DeviceConfigController@activateDetailsVersion',
        ]);

        /**
         * Create new config details version record
         */
        Route::post('device/config/{id}/details/create', [
            'as'   => 'device.config.details.create',
            'uses' => 'DeviceConfigController@createDetails',
        ]);

    });

});
