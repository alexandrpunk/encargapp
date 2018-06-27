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
    #routa de testing
    $router->get('hola', function (Request $request) use ($router) {
        return 'usuario logueado '.$request->auth;
    });
    
    #relaciones
    $router->post('relacion','RelacionController@create');
    $router->get('relacion/contactos','RelacionController@getContactos');
    $router->delete('relacion/{id}','RelacionController@delete');
    // $router->put('relacion/{id}','RelacionController@update');
    // $router->get('relacion','RelacionController@index');
    // $router->get('relacion/{id}','RelacionController@get');     

    #comentarios
    // $router->get('comentario','ComentarioController@index');
    // $router->get('comentario/{id}','ComentarioController@get');
    $router->post('comentario','ComentarioController@create'); #crea un comentario
    $router->put('comentario/{id}','ComentarioController@update'); #edita el comentario indicado
    $router->delete('comentario/{id}','ComentarioController@delete'); #borra comentario indicado

    #encargos
    $router->get('encargos','EncargoController@getEncargos'); # lista todos los encargos
    $router->get('pendientes','EncargoController@getPendientes'); # lista todos los encargos
    $router->get('encargo/{id}','EncargoController@get'); #obtiene el encargo indicado
    $router->post('encargo','EncargoController@create'); #crea un encargo
    $router->put('encargo/{id}','EncargoController@update'); #edita el encargo indicado
    $router->delete('encargo/{id}','EncargoController@delete'); #borra el encargo indicado
});

$router->group(['prefix' => 'api/v1'], function($router) {
    $router->post('auth/login','AuthController@authenticate');
    $router->post('auth/recover/','AuthController@requestPasswordReset');
    // $router->post('auth/reset','AuthController@reset');
    
    #usuarios
    // $router->get('usuarios','UsuarioController@index');  
    // $router->get('usuario/{id}','UsuarioController@get');         
    // $router->post('usuario','UsuarioController@create');      
    // $router->put('usuario/{id}','UsuarioController@update');      
    // $router->delete('usuario/{id}','UsuarioController@delete');
    // $router->get('test','UsuarioController@test'); 
});

$router->get('auth/validate/{token}',[
    'as' => 'validar_email', 'uses' => 'AuthController@validar'
]);

$router->get('auth/recover/{token}',['as' => 'reset_pass', function (Request $request) {
    return view('auth.recover',['url' =>  $request->url()]);
}]);

$router->post('auth/recover/{token}','AuthController@resetPassword');