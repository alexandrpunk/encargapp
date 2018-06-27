<?php
  
namespace App\Http\Controllers;

use Validator;
use App\Usuario;
use Illuminate\Http\Request;
use App\Notifications\Invitacion;
use App\Notifications\ValidarEmail;
use App\Http\Controllers\Controller;
use Illuminate\Database\QueryException;

class UsuarioController extends Controller{
  
  
    // public function index() {  
    //     $usuarios  = Usuario::all();
    //     return response()->json(['status'=>true,'usuarios'=>$usuarios],200);
    // }
  
    public function get($id) {  
        $usuario  = Usuario::find($id);  
        return response()->json(['user_data'=>$usuario],200);
    }
  
    public function create (Request $request) {
        $validator = Validator::make($request->all(), [
            'nombre' => 'required|max:100',
            'apellido' => 'required|max:100',
            'email' => 'required|max:100|unique:usuarios|email',
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
                    return response()->json(['errors' => 'Este usuario esta registrado pero falta verificar su correo (revisa en tu bandeja de trada).'],500);
                } else {
                    return response()->json(['errors' => 'El correo electronico que intentas usar ya esta en uso.'],500);
                } 
            } else {
                return response()->json(['errors' =>$validator->errors()],500);
            }
        }
        $usuario->notify(new ValidarEmail());
        return response()->json(['message'=>'usuario creado'],200);
    }
  
    public function delete($id) {
        $usuario  = Usuario::find($id);
        $usuario->delete(); 
        return response()->json(['message'=>'usuario borado'],200);
    }
  
    public function update(Request $request,$id) {
        try {
            Usuario::find($id)->update($request->all());
        } catch (QueryException $e) {
                return response()->json(['error'=>$e],500);
        }        
        return response()->json(['message'=>'usuario actualizado'],200);
    }
}