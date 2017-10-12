<?php

Route::group(['middleware' => ['auth', 'csrf']], function () {

    Route::group(['namespace' => 'Web'], function () {

        Route::group(['namespace' => 'Store'], function () {

            Route::resource('apps', 'AppsController');

            Route::post('apps/upload}', [
                'as'   => 'apps.upload',
                'uses' => 'AppsController@upload',
            ]);

            Route::post('apps/remove_file}', [
                'as'   => 'apps.upload.remove',
                'uses' => 'AppsController@uploadRemove',
            ]);

            Route::get('apps/{app}/version/create', [
                'as'   => 'apps.version.create',
                'uses' => 'AppVersionsController@create',
            ]);

            Route::post('apps/{app}/version/store', [
                'as'   => 'apps.version.store',
                'uses' => 'AppVersionsController@store',
            ]);

            Route::delete('apps/{app}/version/{version_id}/destroy', [
                'as'   => 'apps.version.destroy',
                'uses' => 'AppVersionsController@destroy',
            ]);

            Route::post('apps/{app}/version/{version_id}/publish', [
                'as'   => 'apps.version.publish',
                'uses' => 'AppVersionsController@publish',
            ]);

            Route::post('apps/{app}/version/upload}', [
                'as'   => 'apps.version.upload',
                'uses' => 'AppVersionsController@upload',
            ]);

            Route::post('apps/{app}/version/remove_file', [
                'as'   => 'apps.version.upload.remove',
                'uses' => 'AppVersionsController@uploadRemove',
            ]);

            Route::post('apps/{app}/version/{version_id}/status', [
                'as'   => 'apps.version.status',
                'uses' => 'AppVersionsController@status',
            ]);

            Route::get('apps/{app}/version/{version_id}/download', [
                'as'   => 'apps.version.download',
                'uses' => 'AppVersionsController@download',
            ]);

            Route::get('apps/{app}/version/{version_id}/edit', [
                'as'   => 'apps.version.edit',
                'uses' => 'AppVersionsController@edit',
            ]);

            Route::put('apps/{app}/version/{version_id}/update', [
                'as'   => 'apps.version.update',
                'uses' => 'AppVersionsController@update',
            ]);

            Route::resource('roms', 'RomsController');

            Route::post('roms/{id}/publish', [
                'as'   => 'roms.publish',
                'uses' => 'RomsController@publish',
            ]);

            Route::post('roms/{id}/unpublish', [
                'as'   => 'roms.unpublish',
                'uses' => 'RomsController@unpublish',
            ]);

            Route::post('roms/upload', [
                'as'   => 'roms.upload',
                'uses' => 'RomsController@upload',
            ]);

            Route::post('roms/upload/{file}/remove', [
                'as'   => 'roms.upload.remove',
                'uses' => 'RomsController@uploadRemove',
            ]);

            Route::post('roms/push', [
                'as'   => 'roms.forceCheckForUpdates',
                'uses' => 'RomsController@forceCheckForUpdates',
            ]);

            Route::get('roms/{id}/download', [
                'as'   => 'roms.download',
                'uses' => 'RomsController@download',
            ]);
        });

    });

});
