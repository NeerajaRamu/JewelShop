<?php

Route::group(['prefix' => 'api', 'middleware' => 'documentation.basicauth'], function() {
    $swagger_json = storage_path().'/swagger.json';

    Route::get('/', function() use ($swagger_json) {
        if(is_readable($swagger_json)) {
            return View::make('documentation.index');
        } else {
            abort(404);
        }
    });

    Route::get('swagger.json', function() use ($swagger_json) {
        if(is_readable($swagger_json)) {
            return file_get_contents(storage_path().'/swagger.json');
        } else {
            abort(404);
        }
    });
});