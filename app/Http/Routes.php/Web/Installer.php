<?php

Route::group([
    'middleware' => ['auth', 'csrf'],
    'namespace'  => 'Web\Installer',
    'prefix'     => 'installer',
], function () {

    Route::group([
        'prefix'    => 'reports',
    ], function () {
        Route::get('/', [
            'as'   => 'installer.reports.list',
            'uses' => 'ReportsController@index',
        ]);

        Route::get('{id}', [
            'as'   => 'installer.reports.show',
            'uses' => 'ReportsController@show',
        ]);
    });
});
