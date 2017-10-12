<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

/**
 * Autoload all routes in Http/Routes directory
 */
foreach (File::AllFiles(__DIR__ . '/Routes') as $partial) {
    require $partial->getPathname();
}

//Default route - load after all other reoutes are checked
Route::get('/', function () {
    return Redirect::to('/dashboard');
});
