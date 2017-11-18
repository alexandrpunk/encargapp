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
    #usuarios
    $app->get('usuario','UsuarioController@index');  
    $app->get('usuario/{id}','UsuarioController@get');      
    $app->post('usuario','UsuarioController@create');      
    $app->put('usuario/{id}','UsuarioController@update');      
    $app->delete('usuario/{id}','UsuarioController@delete');

    #encargos
    $app->get('encargo','EncargoController@index');  
    $app->get('encargo/{id}','EncargoController@get');      
    $app->post('encargo','EncargoController@create');      
    $app->put('encargo/{id}','EncargoController@update');      
    $app->delete('encargo/{id}','EncargoController@delete');

    #relaciones
    $app->get('relacion','RelacionController@index');  
    $app->get('relacion/{id}','RelacionController@get');      
    $app->post('relacion','RelacionController@create');      
    $app->put('relacion/{id}','RelacionController@update');      
    $app->delete('relacion/{id}','RelacionController@delete');

    #comentarios
    $app->get('comentario','ComentarioController@index');  
    $app->get('comentario/{id}','ComentarioController@get');      
    $app->post('comentario','ComentarioController@create');      
    $app->put('comentario/{id}','ComentarioController@update');      
    $app->delete('comentario/{id}','ComentarioController@delete');
});
