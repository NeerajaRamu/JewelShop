<?php

Route::group(['middleware' => ['auth', 'csrf']], function () {

    Route::group(['namespace' => 'Web'], function () {

        Route::get('deployments/{id}/settings', [
            'as'   => 'deployments.settings',
            'uses' => 'DeploymentController@settings',
        ]);

        Route::post('deployments/{id}/settings', [
            'as'   => 'deployments.settings',
            'uses' => 'DeploymentController@saveSettings',
        ]);

        Route::resource('deployments', 'DeploymentController');

        Route::resource('resources', 'AssetsController');
        Route::resource('users', 'UsersController');

        /**
         * Shift updates via web
         */
        Route::post('shifts', 'ShiftsController@store');
        Route::post('shifts/{id}/update', 'ShiftsController@update');
        Route::post('shifts/{id}/delete', 'ShiftsController@delete');

        /**
         * Push Notifications
         */
        Route::get('push_notifications/subscriptions', [
            'as'   => 'pushnotifications.subscriptions',
            'uses' => 'PushNotificationsController@subscriptions',
        ]);

        Route::get('push_notifications', [
            'as'   => 'pushnotifications.index',
            'uses' => 'PushNotificationsController@index',
        ]);

        Route::post('push_notifications', [
            'as'   => 'pushnotifications.send',
            'uses' => 'PushNotificationsController@send',
        ]);

        Route::get('roles', [
            'as'   => 'roles.index',
            'uses' => 'RolesController@index',
        ]);

        Route::get('roles/create', [
            'as'   => 'roles.create',
            'uses' => 'RolesController@create',
        ]);

        Route::get('roles/{id}/edit', [
            'as'   => 'roles.edit',
            'uses' => 'RolesController@edit',
        ]);

        Route::put('roles/{id}/update', [
            'as'   => 'roles.update',
            'uses' => 'RolesController@update',
        ]);

        Route::post('roles/store', [
            'as'   => 'roles.store',
            'uses' => 'RolesController@store',
        ]);

        Route::get('permissions', [
            'as'   => 'permissions.index',
            'uses' => 'PermissionsController@index',
        ]);

        Route::get('permissions/create', [
            'as'   => 'permissions.create',
            'uses' => 'PermissionsController@create',
        ]);

        Route::post('permissions/store', [
            'as'   => 'permissions.store',
            'uses' => 'PermissionsController@store',
        ]);

        Route::get('permissions/{id}/edit', [
            'as'   => 'permissions.edit',
            'uses' => 'PermissionsController@edit',
        ]);

        Route::put('permissions/{id}/update', [
            'as'   => 'permissions.update',
            'uses' => 'PermissionsController@update',
        ]);

        /**
         * Cancel queued update item for user
         */
        Route::get('user/{id}/queue/{type}/delete', [
            'as'   => 'delete.queue',
            'uses' => 'UsersController@deleteQueueItem',
        ]);

        // terminate driver
        Route::post('user/{id}/terminate', [
            'as'   => 'users.terminate',
            'uses' => 'UsersController@terminate',
        ]);

        // unterminate driver
        Route::post('user/{id}/unterminate', [
            'as'   => 'users.unterminate',
            'uses' => 'UsersController@unterminate',
        ]);

        Route::group([
            'namespace' => 'IntegrationLayer',
            'prefix'    => 'integration-layer',
        ], function () {

            Route::get('messages', [
                'as'   => 'integration-layer.messages.index',
                'uses' => 'MessagesController@index',
            ]);

            Route::get('messages/queue_depth', [
                'as'   => 'integration-layer.messages.view',
                'uses' => 'MessagesTypesController@index',
            ]);

            Route::get('messages/{id}', [
                'as'   => 'integration-layer.messages.show',
                'uses' => 'MessagesController@show',
            ]);
        });

        Route::resource(
            'locations', 'LocationController',
            [
                'only' => ['index', 'create', 'store', 'show',],
            ]
        );

        // Kibana Analytics
        Route::get('analytics-visualizations/{id}/destroy', [
            'as'   => 'analytics-visualizations.destroy',
            'uses' => 'AnalyticsVisualizationController@destroy',
        ]);
        Route::resource(
            'analytics-visualizations', 'AnalyticsVisualizationController',
            [
                'except' => ['destroy',],
            ]
        );

        //Alerts Configurations
        Route::get('alerts-configurations', [
            'as'   => 'alerts-configurations.index',
            'uses' => 'AlertController@index',
        ]);

        Route::post('alerts-configurations/save', [
            'as'   => 'alerts-configurations.save',
            'uses' => 'AlertController@updateAlertsConfig',
        ]);
    });

});
