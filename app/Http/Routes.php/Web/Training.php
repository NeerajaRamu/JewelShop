<?php

Route::group([
    'middleware' => ['auth', 'csrf'],
    'namespace'  => 'Web\Training',
    'prefix'     => 'training',
], function () {
    Route::resource(
        'lessons',
        'LessonsController',
        [
            'names' => [
                'index'   => 'training.lessons.list',
                'create'  => 'training.lessons.create',
                'store'   => 'training.lessons.store',
                'edit'    => 'training.lessons.edit',
                'update'  => 'training.lessons.update',
                'destroy' => 'training.lessons.destroy',
            ],
        ]
    );

    Route::get('lessons/{id}/publish', [
        'as'   => 'training.lessons.publish',
        'uses' => 'LessonsController@publish',
    ]);

    Route::get('lessons/{id}/unpublish', [
        'as'   => 'training.lessons.unpublish',
        'uses' => 'LessonsController@unpublish',
    ]);

    // Lesson Pages
    // @url /training/lessons/{lessonId}/pages
    Route::group([
        'namespace' => 'Lesson',
        'prefix'    => 'lessons',
    ], function () {
        Route::get('{lessonId}/pages', [
            'as'   => 'training.lessons.pages.list',
            'uses' => 'PagesController@index',
        ]);

        Route::get('{lessonId}/pages/create', [
            'as'   => 'training.lessons.pages.create',
            'uses' => 'PagesController@create',
        ]);

        Route::post('{lessonId}/pages', [
            'as'   => 'training.lessons.pages.store',
            'uses' => 'PagesController@store',
        ]);

        Route::get('{lessonId}/pages/{id}/edit', [
            'as'   => 'training.lessons.pages.edit',
            'uses' => 'PagesController@edit',
        ]);

        Route::put('{lessonId}/pages/{id}', [
            'as'   => 'training.lessons.pages.update',
            'uses' => 'PagesController@update',
        ]);

        Route::delete('{lessonId}/pages/{id}', [
            'as'   => 'training.lessons.pages.destroy',
            'uses' => 'PagesController@destroy',
        ]);

        Route::get('{lessonId}/pages/ordering', [
            'as'   => 'training.lessons.pages.ordering',
            'uses' => 'PagesController@ordering',
        ]);

        Route::post('{lessonId}/pages/ordering', [
            'as'   => 'training.lessons.pages.ordering.store',
            'uses' => 'PagesController@storeOrdering',
        ]);
    });
});
