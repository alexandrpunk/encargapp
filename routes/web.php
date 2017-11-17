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
    return $router->app->version();
});

$router->group(['prefix' => 'api/v1'], function($app) { 
    $app->get('encargo','EncargoController@index');  
    $app->get('encargo/{id}','EncargoController@getEncargo');      
    $app->post('encargo','EncargoController@createEncargo');      
    $app->put('encargo/{id}','EncargoController@updateEncargo');      
    $app->delete('encargo/{id}','EncargoController@deleteEncargo');
});
