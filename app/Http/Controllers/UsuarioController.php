<?php
  
namespace App\Http\Controllers;
  
use App\Usuario;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
  
  
class UsuarioController extends Controller{
  
  
    public function index() {  
        $usuario  = Usuario::all();
        return response()->json($usuario);  
    }
  
    public function get($id) {  
        $usuario  = Usuario::find($id);  
        return response()->json($usuario);
    }
  
    public function create(Request $request) {
        try {
          Usuario::create($request->all()); 
        } catch (QueryException $e) {
            return response()->json(['status'=>false,'error'=>$e],500);
        }
        return response()->json(['status'=>true,'usuario creado'],200);
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