<?php

Route::group(['middleware' => 'auth'], function () {

    //route left here to allow cleaner bread crumbs
    Route::get('security', array('as' => 'security', function () {
        return Redirect::to('/roles');
        // return View::make('roles.index');
    }));

});
