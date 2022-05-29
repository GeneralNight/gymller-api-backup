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

Route::group(['prefix' => ''], function ($router) {
    Route::post('login', 'Auth\AuthController@login');
    Route::post('logout', 'Auth\AuthController@logout');
    Route::post('validateToken', 'Auth\AuthController@validateToken');
});


Route::post('/gym/store', 'GymController@store');

Route::get('/gym/{slug}/oppening-hours/', 'OppeningHourController@all');
Route::get('/gym/{slug}/oppening-hours/{weekDay}', 'OppeningHourController@index');
Route::post('/gym/{slug}/oppening-hours/store', 'OppeningHourController@store');
Route::put('/gym/{slug}/oppening-hours/{weekDay}/update', 'OppeningHourController@update');
Route::delete('/gym/{slug}/oppening-hours/delete/{weekDay}', 'OppeningHourController@delete');

Route::get('/gym/{slug}/workers/', 'GymWorkerController@all');
Route::get('/gym/{slug}/workers/{workerId}', 'GymWorkerController@index');
Route::post('/gym/{slug}/workers/store', 'GymWorkerController@store');
Route::put('/gym/{slug}/workers/{workerId}/update', 'GymWorkerController@update');
Route::delete('/gym/{slug}/workers/{workerId}/delete', 'GymWorkerController@delete');

Route::get('/permissions-category', 'PermissionsCategoryController@all');
Route::post('/permissions-category/store', 'PermissionsCategoryController@store');
Route::put('/permissions-category/{permCatId}/update', 'PermissionsCategoryController@update');
Route::delete('/permissions-category/{permCatId}/delete', 'PermissionsCategoryController@delete');

Route::get('/permissions', 'PermissionsController@all');
Route::post('/permissions/store', 'PermissionsController@store');
Route::put('/permissions/{permId}/update', 'PermissionsController@update');
Route::delete('/permissions/{permId}/delete', 'PermissionsController@delete');

Route::get('/gym/{slug}/positions/', 'GymPositionsController@all');
Route::get('/gym/{slug}/positions/{positionId}', 'GymPositionsController@index');
Route::post('/gym/{slug}/positions/store', 'GymPositionsController@store');
Route::put('/gym/{slug}/positions/{positionId}/update', 'GymPositionsController@update');
Route::delete('/gym/{slug}/positions/{positionId}/delete', 'GymPositionsController@delete');

Route::get('/gym/{slug}/equipaments/', 'GymEquipamentsController@all');
Route::get('/gym/{slug}/equipaments/{equipId}', 'GymEquipamentsController@index');
Route::post('/gym/{slug}/equipaments/store', 'GymEquipamentsController@store');
Route::put('/gym/{slug}/equipaments/{equipId}/update', 'GymEquipamentsController@update');
Route::delete('/gym/{slug}/equipaments/{equipId}/delete', 'GymEquipamentsController@delete');

Route::get('/gym/{slug}/exercises/', 'GymExercisesController@all');
Route::get('/gym/{slug}/exercises/{exerciseId}', 'GymExercisesController@index');
Route::post('/gym/{slug}/exercises/store', 'GymExercisesController@store');
Route::put('/gym/{slug}/exercises/{exerciseId}/update', 'GymExercisesController@update');
Route::delete('/gym/{slug}/exercises/{exerciseId}/delete', 'GymExercisesController@delete');

Route::get('/gym/{slug}/exercises-category/', 'GymExercisesCategoryController@all');
Route::post('/gym/{slug}/exercises-category/store', 'GymExercisesCategoryController@store');
Route::put('/gym/{slug}/exercises-category/{exerciseCatId}/update', 'GymExercisesCategoryController@update');
Route::delete('/gym/{slug}/exercises-category/{exerciseCatId}/delete', 'GymExercisesCategoryController@delete');


