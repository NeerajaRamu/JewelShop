<?php

Route::group(['middleware' => ['auth', 'csrf']], function () {

    Route::group(['namespace' => 'Web'], function () {

        Route::group(['namespace' => 'Form', 'prefix' => 'form'], function () {

            Route::get('definitions', [
                'as'   => 'form.definitions.index',
                'uses' => 'DefinitionController@index',
            ]);

            Route::get('definitions/{definitionId}/submissions', [
                'as'   => 'form.submissions.definition',
                'uses' => 'SubmissionController@definitionSubmissions',
            ]);

            Route::get('shift/{shiftId}/submissions', [
                'as'   => 'form.submissions.shift',
                'uses' => 'SubmissionController@shiftSubmissions',
            ]);

            Route::get('submissions', [
                'as'   => 'form.submissions.index',
                'uses' => 'SubmissionController@index',
            ]);

            Route::get('submissions/{id}', [
                'as'   => 'form.submissions.show',
                'uses' => 'SubmissionController@show',
            ]);

        });

    });

});
