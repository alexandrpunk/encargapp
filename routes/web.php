<?php
use Illuminate\Http\Request;
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



$router->group(['prefix' => 'api/v1','middleware' => 'jwt.auth'], function($router) {
    $router->get('/hola', function (Request $request) use ($router) {
        return 'usuario logueado '.$request->auth;
        // return view('hola', ['user' => $request->auth]);
    });
});

$router->group(['prefix' => 'api/v1'], function($router) {
    $router->post('auth/login','AuthController@authenticate');
    $router->post('auth/recover/{email}','AuthController@recover');
    $router->get('auth/validate/{token}',[
        'as' => 'validar_email', 'uses' => 'AuthController@validar'
    ]);
    // $router->post('auth/reset','AuthController@reset');

    #usuarios
    $router->get('usuario','UsuarioController@index');  
    $router->get('usuario/{id}','UsuarioController@get');         
    $router->post('usuario','UsuarioController@create');      
    $router->put('usuario/{id}','UsuarioController@update');      
    $router->delete('usuario/{id}','UsuarioController@delete');

    #encargos
    $router->get('encargo','EncargoController@index');  
    $router->get('encargo/{id}','EncargoController@get');      
    $router->post('encargo','EncargoController@create');      
    $router->put('encargo/{id}','EncargoController@update');      
    $router->delete('encargo/{id}','EncargoController@delete');

    #relaciones
    $router->get('relacion','RelacionController@index');  
    $router->get('relacion/{id}','RelacionController@get');      
    $router->post('relacion','RelacionController@create');      
    $router->put('relacion/{id}','RelacionController@update');      
    $router->delete('relacion/{id}','RelacionController@delete');

    #comentarios
    $router->get('comentario','ComentarioController@index');  
    $router->get('comentario/{id}','ComentarioController@get');      
    $router->post('comentario','ComentarioController@create');      
    $router->put('comentario/{id}','ComentarioController@update');      
    $router->delete('comentario/{id}','ComentarioController@delete');
});
