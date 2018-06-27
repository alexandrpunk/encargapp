<?php
  
namespace App\Http\Controllers;
  
use App\Encargo;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
  
  
class EncargoController extends Controller{
  
  
    // public function index() {  
    //     $encargos  = Encargo::all();
    //     return response()->json(['status'=>true,'encargos'=>$encargos],200);
    // }

    public function getEncargos() {  
        $encargos = Encargo::where('id_asignador', Auth::user()->id)->where('id_responsable','!=', Auth::user()->id);
        return response()->json(['encargos'=>$encargos],200);
    }

    public function getPendientes() {  
        $encargos  =  Encargo::where('id_responsable', $request->auth->id);
        return response()->json(['pendientes'=>$encargos],200);
    }

  
    public function get($id) {  
        $encargo  = Encargo::find($id);  
        return response()->json($encargo);
    }
  
    public function create(Request $request) {
        try {
          Encargo::create($request->all()); 
        } catch (QueryException $e) {
            return response()->json(['error'=>$e],500);
        }
        return response()->json(['message'=>'encargo creado'],200);
    }
  
    public function delete($id) {
        $encargo  = Encargo::find($id);
        $encargo->delete(); 
        return response()->json(['message'=>'encargo borrado'],200);
    }
  
    public function update(Request $request,$id) {
        try {
            Encargo::find($id)->update($request->all());
        } catch (QueryException $e) {
                return response()->json(['error'=>$e],500);
        }        
        return response()->json(['message'=>'encargo actualizado'],200);
    }  
}