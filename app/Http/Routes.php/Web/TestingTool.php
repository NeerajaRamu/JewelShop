<?php

Route::group(['middleware' => ['auth', 'csrf'], 'namespace' => 'Web'], function () {

    Route::group(['namespace' => 'TestingTool', 'prefix' => 'scenariotest'], function () {

        Route::get('{scenario_id}/edit', [
            'as'   => 'scenariotest.edit',
            'uses' => 'ScenarioTestController@edit',
        ]);
        Route::put('{scenario_id}', [
            'as'   => 'scenariotest.update',
            'uses' => 'ScenarioTestController@update',
        ]);

        Route::resource('', 'ScenarioTestController', [
            'only'  => [
                'index',
                'create',
                'store',
            ],
            'names' => [
                'index'  => 'scenariotest.index',
                'create' => 'scenariotest.create',
                'store'  => 'scenariotest.store',
            ],
        ]);

        Route::post('run', [
            'as'   => 'scenariotest.run',
            'uses' => 'ScenarioTestController@run',
        ]);
        Route::get('{scenario_id}/data/edit', [
            'as'   => 'scenariotest.data.edit',
            'uses' => 'ScenarioTestController@editData',
        ]);
        Route::post('{scenario_id}/data/store', [
            'as'   => 'scenariotest.data.store',
            'uses' => 'ScenarioTestController@storeData',
        ]);

    });

});
