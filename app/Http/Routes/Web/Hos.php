<?php

Route::group(['middleware' => ['auth', 'csrf']], function () {

    Route::group(['namespace' => 'Web'], function () {

        /**
         * HOS EVENT ROUTES
         */
        Route::group(['prefix' => 'event'], function () {

            Route::get('{id}/decertify', ['as' => 'event.decertify', 'uses' => 'HosEventController@decertify']);
            Route::get('{guid}', 'HosEventController@show');
            Route::post('', ['as' => 'event.store', 'uses' => 'HosEventController@store']);
            Route::post('split', ['as' => 'event.split', 'uses' => 'HosEventController@storeSplit']);

            Route::get('{id}/undo-all-edits-preview', [
                'as'   => 'event.undoAllEditsPreview',
                'uses' => 'HosEventController@undoAllEditsPreview',
            ]);
            Route::post('{id}/undo-all-edits', [
                'as'   => 'event.undoAllEdits',
                'uses' => 'HosEventController@undoAllEdits',
            ]);

            Route::get('{id}/edit-nondriver-event-form', [
                'as'   => 'event.edit-nondriver-event-form',
                'uses' => 'HosEventController@editNondriverEventForm',
            ]);
            Route::post('{id}/edit-nondriver-event', [
                'as'   => 'event.edit-nondriver-event',
                'uses' => 'HosEventController@editNondriverEvent',
            ]);

            // TODO: find out if this route is used anymore. If not, remove and figure out if related controller method/views/etc are needed
            Route::get('{guid1}/{guid2}', 'HosEventController@compare');

        });

        /**
         * HOS EVENTS - UNASSIGNED / ASSIGNED
         */
        Route::group(['prefix' => 'hos'], function () {
            Route::group(['prefix' => 'events'], function () {
                Route::group(['prefix' => 'unassigned',], function () {
                    Route::get('', [
                        'as'   => 'hos.events.unassigned',
                        'uses' => 'HosEventController@unassigned',
                    ]);
                    Route::get('validateudtfordriver', [
                        'as'   => 'hos.events.unassigned.validateudtfordriver',
                        'uses' => 'HosEventController@validateUdtForDriver',
                    ]);
                });

                Route::get('nondriver', [
                    'as'   => 'hos.events.nondriver',
                    'uses' => 'HosEventController@nondriver',
                ]);

                Route::post('assign', [
                    'as'   => 'hos.assignment.assign',
                    'uses' => 'HosAssignmentController@assign',
                ]);
            });
        });
    });

});
