<?php
  
namespace App\Http\Controllers;
  
use App\Relacion;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
  
  
class RelacionController extends Controller{
  
  
    public function index() {
        $relaciones  = Relacion::all();
        return response()->json(['status'=>true,'relaciones'=>$relaciones],200);
    }
  
    public function get($id) {  
        $relacion  = Relacion::find($id);  
        return response()->json($relacion);
    }
  
    public function create(Request $request) {
        try {
          Relacion::create($request->all()); 
        } catch (QueryException $e) {
            return response()->json(['status'=>false,'error'=>$e],500);
        }
        return response()->json(['status'=>true,'relacion creado'],200);
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
                return response()->json(['status'=>false,'error'=>$e],500);
        }        
        return response()->json(['status'=>true,'relacion actualizado'],200);
    }  
}