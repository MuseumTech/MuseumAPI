<?php

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
    return "MuseumTech API v1.0";
});

$router->group(['prefix' => 'api', 'middleware' => 'auth'], function () use ($router) {
    $router->get('reservations',  ['uses' => 'ReservationController@index']);
    $router->get('reservations/{id}', ['uses' => 'ReservationController@show']);
    $router->post('reservations', ['middleware' => 'auth:create:reservations', 'uses' => 'ReservationController@create']);
    $router->delete('reservations/{id}', ['middleware' => 'auth:delete:reservations', 'uses' => 'ReservationController@delete']);
    $router->put('reservations/{id}', ['middleware' => 'auth:update:reservations', 'uses' => 'ReservationController@update']);
});