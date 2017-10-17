<?php

Route::group(['middleware' => ['auth', 'csrf']], function () {

    Route::group(['namespace' => 'Web'], function () {

        /* Ajax activities */

        Route::get('drivers/activities', [
            'as'   => 'drivers.activities',
            'uses' => 'DriversController@dutyStatusActivities',
        ]);

        Route::resource(
            'drivers', 'DriversController',
            [
                'except' => ['show'],
            ]
        );

        Route::group(['prefix' => 'drivers'], function ($api) {

            Route::get('{id}/daily-hos-logs', [
                'as'   => 'drivers.show',
                'uses' => 'DriversController@dailyHosLogs',
            ]);

            Route::get('{id}/all-hos-events', [
                'as'   => 'drivers.all-hos-events',
                'uses' => 'DriversController@allHosEvents',
            ]);

            Route::get('{id}/shifts', [
                'as'   => 'drivers.shifts',
                'uses' => 'DriversController@shifts',
            ]);

            Route::get('{id}/driver-telematics', [
                'as'   => 'drivers.driver-telematics',
                'uses' => 'DriversController@driverTelematics',
            ]);

            Route::get('{userId}/printedlog/{dateFrom}/{dateTo?}', [
                'as'   => 'drivers.printedlog',
                'uses' => 'DriversController@printedLog',
            ]);

            /**
             * HOS ajax routes
             */
            Route::get('{id}/hos-timers', [
                'as'   => 'drivers.hos-timers',
                'uses' => 'DriversController@ajaxHosTimers',
            ]);

            Route::post('{id}/hos-timeline', [
                'as'   => 'drivers.hos-timeline',
                'uses' => 'DriversController@hosTimeline',
            ]);

            Route::get('{id}/hos-logs', [
                'as'   => 'drivers.hos-logs',
                'uses' => 'DriversController@hosAllEventsTimeline',
            ]);

            Route::get('{id}/telematics-history', [
                'as'   => 'drivers.telematics-history',
                'uses' => 'DriversController@telematicsHistory',
            ]);

            Route::post('{id}/hos-chart', [
                'as'   => 'drivers.hos-chart',
                'uses' => 'DriversController@hosEventChart',
            ]);

            Route::get('{id}/history', [
                'as'   => 'timeline.history',
                'uses' => 'DriversController@hosEventHistory',
            ]);

            Route::get('{id}/event-data', [
                'as'   => 'timeline.event-data',
                'uses' => 'DriversController@hosEventData',
            ]);

            Route::get('search', [
                'as'   => 'drivers.search',
                'uses' => 'DriversController@driverSearch',
            ]);

            Route::post('{id}/timezone-check', [
                'as'   => 'timezone.check',
                'uses' => 'DriversController@timezoneCheck',
            ]);

            Route::get('{id}/force_logout', [
                'as'   => 'drivers.force_logout',
                'uses' => 'DriversController@forceLogout',
            ]);

            Route::post('{id}/setParameterGroup', [
                'as'   => 'drivers.setParameterGroup',
                'uses' => 'DriversController@setParameterGroup',
            ]);

            Route::resource('driverParameterGroups', 'DriverParameterGroupsController');

            Route::get('{id}/force_off_duty', [
                'as'   => 'drivers.force_off_duty',
                'uses' => 'DriversController@forceOffDuty',
            ]);
        });

    });

});
