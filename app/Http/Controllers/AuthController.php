<?php

namespace App\Http\Controllers;
use DB;
use DateTime;
use Validator;
use App\Usuario;
use App\Relacion;
use App\ResetPassword;
use Firebase\JWT\JWT;
use Illuminate\Http\Request;
use Firebase\JWT\ExpiredException;
use App\Notifications\RestablecerPassword;
use Illuminate\Support\Facades\Hash;
use Laravel\Lumen\Routing\Controller;
use Illuminate\Database\Eloquent\ModelNotFoundException;


class AuthController extends Controller {

    private $request;

    public function __construct(Request $request) {
        $this->request = $request;
    }

    protected function jwt(Usuario $usuario) {
        $payload = [
            'iss' => "encargapp", // Issuer of the token
            'sub' => $usuario->id, // Subject of the token
            'iat' => time(), // Time when JWT was issued. 
            'exp' =>  time() + 31536000 * 2 // el segundo valor es la duracion en años del token
        ];
        return JWT::encode($payload, env('JWT_SECRET'));
    } 

    public function authenticate() {
        $this->validate($this->request, [
            'email'     => 'required|email',
            'password'  => 'required'
        ]);

        // Find the Usuario by email
        $usuario = Usuario::where('email', $this->request->input('email'))->first();

        if (!$usuario) {
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
            return response()->json(['validated'=>false,'error'=>'El usuario ya esta verificado, no existe o el enlace es incorrecto'],404);
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

    public function requestPasswordReset (Request $request) {
        try {
            $usuario = Usuario::where('email', $request->email)->firstOrFail();
        } catch (ModelNotFoundException $e) {
            return response()->json(['validated'=>false,'error'=>'Este correo no esta registrado'],404);
        }
        $token = str_random(32);
        ResetPassword::create(['email'=>$usuario->email,'token'=>$token]);
        $usuario->notify(new RestablecerPassword($token));
        return response()->json(['validated'=>true,'message'=>'Se ha enviado un enlace para restablecer la contraseña'],200);
    }
    
    public function resetPassword (Request $request, $token) {
        try {
            $solicitudReset = ResetPassword::where('token',$token)->firstOrFail();
        } catch (ModelNotFoundException $e) {
            return view('base.message',[
                'message' => 'El token para restablecer tu contraseña es invalido, vuelve a solicitar la recuperacion de contraseña para que se te envie uno a tu email.',
                'titulo' => 'Token invalido'
            ]);
        }
        $dbdate = strtotime($solicitudReset->created_at);
        if (time() - $dbdate > 30 * 60) {
            return view('base.message',[
                'message' => 'El token para reestablecer tu contraseña a expirado, vuelve a solicitar la recuperacion de contraseña.',
                'titulo' => 'Token expirado'
            ]);
        }else{
            return 'token valido';
        }
        
    }
}