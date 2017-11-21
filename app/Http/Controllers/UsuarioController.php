<?php
  
namespace App\Http\Controllers;

use Validator;
use App\Usuario;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Contracts\Auth\PasswordBroker;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use App\Notifications\ValidarEmail;
use App\Notifications\Invitacion;
  
  
class UsuarioController extends Controller{
  
  
    public function index() {  
        $usuarios  = Usuario::all();
        return response()->json(['status'=>true,'usuarios'=>$usuarios],200);
    }
  
    public function get($id) {  
        $usuario  = Usuario::find($id);  
        return response()->json($usuario);
    }
  
    // public function create(Request $request) {
    //     try {
    //       Usuario::create($request->all()); 
    //     } catch (QueryException $e) {
    //         return response()->json(['status'=>false,'error'=>$e],500);
    //     }
    //     return response()->json(['status'=>true,'usuario creado'],200);
    // }

    public function create (Request $request) {
        $validator = Validator::make($request->all(), [
            'nombre' => 'required|max:100',
            'apellido' => 'required|max:100',
            'email' => 'required|max:100|unique:Usuarios|email',
            'telefono' => 'digits:10|nullable',
            'password' => 'required|min:8|max:15'
        ]);
        $data=[
            'nombre' => $request->nombre,
            'apellido' => $request->apellido,
            'email' => $request->email,
            'telefono' => $request->telefono,
            'email_token' => str_random(10),
            'password' => $request->password,
            'status' => 3
        ];
            
        if ($validator->passes()) {
            $usuario = Usuario::create($data);
        } else {
            $failedRules = $validator->failed();
            if (isset($failedRules['email']['Unique'])) {
                #verifica si el error de la validacion es porque el email ya esta registrado
                #si ya esta registrado ese email se verifica si no es un usario on invitacion pendiente
                #se recupera el usuario
                $usuario = Usuario::where('email',$request->email)->first();
                #se revisa que el usuario existente tenga status 2 y se registran los datos reales
                if ($usuario->status == 2 ) {#si tiene estado 2 quiere decir que es una invitacion pendiente
                    $usuario->update($data); #se registra el usuario
                } else if ($usuario->status == 3) {
                    return response()->json(['registro'=>false, 'errors' => 'Este usuario esta registrado pero falta verificar su correo (revisa en tu bandeja de trada).'],500);
                    // return redirect('/registro')
                    //     ->withErrors('Este usuario esta registrado pero falta verificar su correo (revisa en tu bandeja de trada).')
                    //     ->withInput();
                } else {
                    return response()->json(['registro'=>false, 'errors' => 'El correo electronico que intentas usar ya esta en uso.'],500);
                    // return redirect('/registro')
                    //     ->withErrors('El correo electronico que intentas usar ya esta en uso.')
                    //     ->withInput();
                } 
            } else {
                return response()->json(['registro'=>false, 'errors' =>$validator->errors()],500);
                // return redirect('/registro')
                //     ->withErrors($validator)
                //     ->withInput();
            }
        }
        $usuario->notify(new ValidarEmail());
        return response()->json(['registro'=>true,'usuario creado'],200);
    }
  
    public function delete($id) {
        $usuario  = Usuario::find($id);
        $usuario->delete(); 
        return response()->json(['status'=>true,'usuario borado'],200);
    }
  
    public function update(Request $request,$id) {
        try {
            Usuario::find($id)->update($request->all());
        } catch (QueryException $e) {
                return response()->json(['status'=>false,'error'=>$e],500);
        }        
        return response()->json(['status'=>true,'usuario actualizado'],200);
    }  
}