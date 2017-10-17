<?php

Route::group(['middleware' => 'auth'], function () {

    Route::group(['namespace' => 'Web'], function () {

        Route::group(['prefix' => 'devices'], function () {

            Route::get('cvd', [
                'as'   => 'devices.cvd.index',
                'uses' => 'DevicesController@cvdIndex',
            ]);
            Route::get('tablet', [
                'as'   => 'devices.tablet.index',
                'uses' => 'DevicesController@tabletIndex',
            ]);

            Route::get('create', [
                'as'   => 'devices.create',
                'uses' => 'DevicesController@create',
            ]);
            Route::post('create', [
                'as'   => 'devices.create',
                'uses' => 'DevicesController@store',
            ]);

            Route::post('{id}/activate', [
                'as'   => 'devices.activate',
                'uses' => 'DevicesController@activate',
            ]);
            Route::post('{id}/deactivate', [
                'as'   => 'devices.deactivate',
                'uses' => 'DevicesController@deactivate',
            ]);

            Route::delete('{id}/destroy', [
                'as'   => 'devices.destroy',
                'uses' => 'DevicesController@destroy',
            ]);

            Route::put('{id}/tag', [
                'as'   => 'devices.tag',
                'uses' => 'DevicesController@tagDevice',
            ]);


            Route::get('{type?}/{device}', [
                'as'   => 'devices.show',
                'uses' => 'DevicesController@show',
            ])->where('type', 'cvd|tablet');

            // unlock device
            Route::get('{device}/unlock', [
                'as'   => 'devices.unlock.show',
                'uses' => 'DevicesController@unlockShow',
            ]);
            Route::post('{device}/unlock', [
                'as'   => 'devices.unlock.send',
                'uses' => 'DevicesController@unlockSend',
            ]);

            // Device applications
            Route::get('{device}/apps', [
                'as'   => 'devices.apps',
                'uses' => 'DevicesAppController@index',
            ]);
            Route::delete('{device}/apps/{app_id}/destroy', [
                'as'   => 'devices.apps.destroy',
                'uses' => 'DevicesAppController@destroy',
            ]);

             // request performance extract from a Device over SMS
            Route::get('devices/{id}/driver-performance-extract', [
                'as'   => 'devices.driver-performance-extract',
                'uses' => 'DevicesController@driverPerformanceExtract',
            ]);

            // Error Log
            Route::get('errorlog', [
                'as'   => 'devices.errorlog',
                'uses' => 'DeviceErrorLogController@index',
            ]);
            Route::get('errorlog/getdatatablepage', [
                'as'   => 'devices.errorlog.getdatatablepage',
                'uses' => 'DeviceErrorLogController@getDataTablePage',
            ]);

        });

    });

});
