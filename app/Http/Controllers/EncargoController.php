<?php
  
namespace App\Http\Controllers;
  
use App\Encargo;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
  
  
class EncargoController extends Controller{
  
  
    public function index() {  
        $encargos  = Encargo::all();
        return response()->json($encargos);  
    }
  
    public function getEncargo($id) {  
        $encargo  = Encargo::find($id);  
        return response()->json($encargo);
    }
  
    public function createEncargo(Request $request) {  
        $encargo = new Encargo([
            'encargo' => $request->input('encargo'),
            'id_asignador' => $request->input('id_asignador'),
            'id_responsable' => $request->input('id_responsable'),
            'fecha_plazo' => $request->input('fecha_plazo'),
        ]);
        $encargo->save();
        // $encargo = Encargo::create($request->all());  
        return response()->json(['status'=>true,'encargo guardado'],200);
        // dd($request);
    }
  
    public function deleteEncargo($id) {
        $encargo  = Encargo::find($id);
        $encargo->delete(); 
        return response()->json('deleted');
    }
  
    public function updateEncargo(Request $request,$id) {
        $encargo  = Encargo::find($id);
        $encargo->encargo = $request->input('encargo');
        $encargo->id_asignador = $request->input('id_asignador');
        $encargo->id_responsable = $request->input('id_responsable');
        $encargo->fecha_plazo = $request->input('fecha_plazo');
        $encargo->save();  
        return response()->json($encargo);
    }  
}