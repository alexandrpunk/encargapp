<?php
  
namespace App\Http\Controllers;
  
use App\Usuario;
use App\Relacion;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Database\QueryException;

class RelacionController extends Controller{
  
  
    public function index() {
        $relaciones  = Relacion::all();
        return response()->json(['status'=>true,'relaciones'=>$relaciones],200);
    }
  
    public function get($id) {  
        $relaciones  = Relacion::where('usuario',$id)->get();
        return response()->json(['status'=>true,'relaciones'=>$relaciones],200);
    }
  
    public function create(Request $request) {
        try {
          Relacion::create(['id_usuario'=>$request->usuario,'id_contacto'=>$request->contacto,'status'=>1]);
          Relacion::create(['id_usuario'=>$request->contacto,'id_contacto'=>$request->usuario,'status'=>1]);
        } catch (QueryException $e) {
            return response()->json(['status'=>false,'error'=>$e->errorInfo],500);
        }
        return response()->json(['status'=>true,'relacion creada'],200);
    }
  
    public function delete($id) {
        $relacion  = Relacion::find($id);
        $relacion->delete(); 
        return response()->json('deleted');
    }
  
    public function update(Request $request,$id) {
        try {
            Relacion::find($id)->update($request->all());
        } catch (QueryException $e) {
                return response()->json(['status'=>false,'error'=>$e->errorInfo],500);
        }        
        return response()->json(['status'=>true,'relacion actualizado'],200);
    }

    public function getContactos (Request $request) {
        $contactos = Relacion::with('contacto')->where('id_usuario',$request->auth->id)->get();
        return response()->json(['status'=>true,'contactos'=>$contactos],200);
    }
}