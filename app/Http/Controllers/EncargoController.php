<?php
  
namespace App\Http\Controllers;
  
use App\Encargo;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
  
  
class EncargoController extends Controller{
  
  
    public function index() {  
        $encargos  = Encargo::all();
        return response()->json(['status'=>true,'encargos'=>$encargos],200);
    }

  
    public function get($id) {  
        $encargo  = Encargo::find($id);  
        return response()->json($encargo);
    }
  
    public function create(Request $request) {
        try {
          Encargo::create($request->all()); 
        } catch (QueryException $e) {
            return response()->json(['status'=>false,'error'=>$e],500);
        }
        return response()->json(['status'=>true,'encargo creado'],200);
    }
  
    public function delete($id) {
        $encargo  = Encargo::find($id);
        $encargo->delete(); 
        return response()->json('deleted');
    }
  
    public function update(Request $request,$id) {
        try {
            Encargo::find($id)->update($request->all());
        } catch (QueryException $e) {
                return response()->json(['status'=>false,'error'=>$e],500);
        }        
        return response()->json(['status'=>true,'encargo actualizado'],200);
    }  
}