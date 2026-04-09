<?php

namespace App\Http\Controllers\ApiControllers;

use Storage;
use App\Http\Resources\ParteTrabajoResource;
use App\Http\Resources\PresupuestoSelectResource;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Models\Recibo;
use App\Models\NroParteTrabajo;
use App\Models\NroPresupuesto;
use App\Helpers\GestorHelper;

class ParteTrabajoController extends Controller
{
  
  public function getParteTrabajo(Request $request, $user_id){
    // Usar el helper para obtener el user_id correcto (cliente_id si es gestor)
    $effectiveUserId = GestorHelper::getUserId($request, $user_id);
    
    if (!$effectiveUserId) {
      return response()->json(['error' => 'No tiene acceso a este recurso'], 403);
    }
    
    //return NroParteTrabajo::with('presupuesto')->get();
    $partes_trabajo = Recibo::where('user_id', '=', $effectiveUserId)->with('nro_parte_trabajo.presupuesto.recibo')
        ->whereHas('nro_parte_trabajo')
        ->orderBy('created_at', 'DESC')
        ->get();
    //return $partes_trabajo;
    $parte_trabajo_resource = ParteTrabajoResource::collection($partes_trabajo);
    return response()->json($parte_trabajo_resource, 200);
  }

  public function getPrespuestos(Request $request){
    // # START busca presupuestos segun usuario id
    // #  cambiado oscar para numeracion correcta de albaranes
    // Usar el helper para obtener el user_id correcto (cliente_id si es gestor)
    $effectiveUserId = GestorHelper::getUserId($request);
    
    if (!$effectiveUserId) {
      return response()->json(['error' => 'No tiene acceso a este recurso'], 403);
    }
    
    // $presupuestos = NroPresupuesto::with('recibo:id,total')->get(); // ANTIGUA LINEA
    $presupuestos = NroPresupuesto::with('recibo:id,total')->where(['user_id' => $effectiveUserId])->get();
    // # END busca presupuestos segun usuario id
    $presupuestos_resource = PresupuestoSelectResource::collection($presupuestos);
    return response()->json($presupuestos_resource, 200);
  }

  public function getPresupuestoAsociado($recibo_id){
    $recibo = Recibo::with('nro_parte_trabajo.presupuesto.recibo')->where('id', $recibo_id)->get()->first();
    $presupuesto = $recibo->nro_parte_trabajo->presupuesto;
    if($presupuesto){
      return response()->json(new PresupuestoSelectResource($presupuesto), 200);
    }
    return response()->json(null, 200);
  }

  public function deleteParteTrabajo($recibo_id){
    $recibo = Recibo::find($recibo_id);
    $recibo->nro_parte_trabajo()->delete();
    return response()->json('parte trabajo eliminado', 200);
  }
}
