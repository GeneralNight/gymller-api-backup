<?php

/** @var \Laravel\Lumen\Routing\Router $router */
use Illuminate\Support\Facades\Route;
/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', function () use ($router) {
    return $router->app->version();
});

Route::get('/login', 'Auth\AuthController@login');

Route::post('/gym/store', 'GymController@store');

Route::get('/gym/{gymId}/oppening-hours/', 'OppeningHourController@all');
Route::post('/gym/{gymId}/oppening-hours/store', 'OppeningHourController@store');
Route::put('/gym/{gymId}/oppening-hours/update/{weekDay}', 'OppeningHourController@update');
Route::delete('/gym/{gymId}/oppening-hours/delete/{weekDay}', 'OppeningHourController@delete');

Route::get('/gym/{gymId}/workers/', 'GymWorkerController@all');
Route::post('/gym/{gymId}/workers/store', 'GymWorkerController@store');

Route::get('/gym/{gymId}/positions/', 'GymPositionsController@all');
Route::post('/gym/{gymId}/positions/store', 'GymPositionsController@store');
Route::put('/gym/{gymId}/positions/{positionId}/update', 'GymPositionsController@update');
Route::delete('/gym/{gymId}/positions/{positionId}/delete', 'GymPositionsController@delete');

Route::group(['middleware' => 'auth:api'], function () {


});
