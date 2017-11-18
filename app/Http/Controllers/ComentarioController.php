<?php
  
namespace App\Http\Controllers;
  
use App\Comentario;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
  
  
class ComentarioController extends Controller{
  
  
    public function index() {  
        $comentarios  = Comentario::all();
        return response()->json(['status'=>true,'comentarios'=>$comentarios],200);
    }
  
    public function get($id) {  
        $comentario  = Comentario::find($id);  
        return response()->json($comentario);
    }
  
    public function create(Request $request) {
        try {
          Comentario::create($request->all()); 
        } catch (QueryException $e) {
            return response()->json(['status'=>false,'error'=>$e],500);
        }
        return response()->json(['status'=>true,'comentario creado'],200);
    }
  
    public function delete($id) {
        $comentario  = Comentario::find($id);
        $comentario->delete(); 
        return response()->json(['status'=>true,'comentario borrado'],200);
    }
  
    public function update(Request $request,$id) {
        try {
            Comentario::find($id)->update($request->all());
        } catch (QueryException $e) {
                return response()->json(['status'=>false,'error'=>$e],500);
        }        
        return response()->json(['status'=>true,'comentario actualizado'],200);
    }  
}