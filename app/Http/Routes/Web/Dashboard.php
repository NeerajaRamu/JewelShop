<?php

Route::group(['middleware' => ['auth', 'csrf']], function () {

    Route::group(['namespace' => 'Web', 'prefix' => 'dashboard'], function () {

        // home route is needed for Illuminate/Routing/Redirector
        Route::get('', [
            'as'   => 'home',
            'uses' => 'DashboardController@index',
        ]);

        // AJAX route
        Route::get('report', [
            'as'   => 'dashboard.report',
            'uses' => 'DashboardController@dashboardReport',
        ]);

    });

});
