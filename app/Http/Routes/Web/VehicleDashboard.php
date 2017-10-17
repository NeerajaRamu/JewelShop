<?php

Route::group(['middleware' => ['auth', 'csrf']], function () {

    Route::group(['namespace' => 'Web'], function () {

        // AJAX call to get Vehicle/Day data
        Route::group(['prefix' => 'vehicle-dashboard/{id}'], function () {
            Route::get('daily-data', [
                'as'   => 'vehicle-dashboard.daily-data',
                'uses' => 'VehicleDashboardController@dailyData',
            ]);
        });

        // Vehicle Dashboard resource routes
        Route::resource(
            'vehicle-dashboard', 'VehicleDashboardController',
            [
                'only' => ['index', 'show'],
            ]
        );

    });

});