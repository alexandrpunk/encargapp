<?php

namespace App\Http\Controllers;

use Validator;
use App\Usuario;
use App\Relacion;
use Firebase\JWT\JWT;
use Illuminate\Http\Request;
use Firebase\JWT\ExpiredException;
use Illuminate\Support\Facades\Hash;
use Laravel\Lumen\Routing\Controller;

class AuthController extends Controller {
    /**
     * The request instance.
     *
     * @var \Illuminate\Http\Request
     */
    private $request;

    /**
     * Create a new controller instance.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return void
     */
    public function __construct(Request $request) {
        $this->request = $request;
    }

    /**
     * Create a new token.
     * 
     * @param  \App\Usuario   $usuario
     * @return string
     */
    protected function jwt(Usuario $usuario) {
        $payload = [
            'iss' => "encargapp", // Issuer of the token
            'sub' => $usuario->id, // Subject of the token
            'iat' => time(), // Time when JWT was issued. 
            'exp' =>  time() + 31536000 * 2 // el segundo valor es la duracion en años del token
            // 'exp' => time() + 60*60 // Expiration time
        ];
        
        // As you can see we are passing `JWT_SECRET` as the second parameter that will 
        // be used to decode the token in the future.
        return JWT::encode($payload, env('JWT_SECRET'));
    } 

    /**
     * Authenticate a Usuario and return the token if the provided credentials are correct.
     * 
     * @param  \App\Usuario   $usuario 
     * @return mixed
     */
    public function authenticate(Usuario $usuario) {
        $this->validate($this->request, [
            'email'     => 'required|email',
            'password'  => 'required'
        ]);

        // Find the Usuario by email
        $usuario = Usuario::where('email', $this->request->input('email'))->first();

        if (!$usuario) {
            // You wil probably have some sort of helpers or whatever
            // to make sure that you have the same response format for
            // differents kind of responses. But let's return the 
            // below respose for now.
            return response()->json([
                'error' => 'Email does not exist.'
            ], 400);
        }

        // Verify the password and generate the token
        if (Hash::check($this->request->input('password'), $usuario->password)) {
            return response()->json([
                'token' => $this->jwt($usuario)
            ], 200);
        }

        // Bad Request response
        return response()->json([
            'error' => 'Email or password is wrong.'
        ], 400);
    }

    public function validar ($token) {
        try {
            $usuario = Usuario::where('email_token',$token)->firstOrFail();
        } catch (ModelNotFoundException $e) {
            return response()->json(['validated'=>false,'error'=>'El usuario ya esta verificado, no existe o el enlace es incorrecto'],500);
        }

        $usuario->verificado();
        Relacion::create([
            'id_usuario' => $usuario->id,
            'id_contacto' => $usuario->id,
            'status' => 1
        ]);
            
        # se actualizan todas las relaciones en estado 2
        Relacion::where(function ($query) use ($usuario) {
                $query->where('id_contacto', $usuario->id)
                    ->orWhere('id_usuario', $usuario->id);
        })
        ->where('status', 2)
        ->update(['status' => 1]);

        // Auth::login($usuario, true);
        return response()->json(['validated'=>true,'message'=>'Usuario validado, ya puede iniciar sesion'],200);
    }
}
