<?php

namespace App\Http\Controllers\ApiControllers;

use App\Http\Resources\PresupuestoResource;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Models\Recibo;
use App\Models\Nropresupuesto;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use App\Helpers\GestorHelper;

class PresupuestoController extends Controller
{
  public function getRecibos(Request $request, $user_id = null){
    // Usar el helper para obtener el user_id correcto (cliente_id si es gestor)
    $effectiveUserId = GestorHelper::getUserId($request, $user_id);
    
    if (!$effectiveUserId) {
      return response()->json(['error' => 'No tiene acceso a este recurso'], 403);
    }
    
    $presupuestos = PresupuestoResource::collection(Recibo::where('user_id', '=', $effectiveUserId)->whereHas('nro_presupuesto')->with('cliente')->orderBy('id', 'DESC')->get());
    return response()->json($presupuestos, 200);
  }

  public function deletePresupuesto($recibo_id){
    try{
      DB::beginTransaction();

      $recibo = Recibo::find($recibo_id);

      $recibo->nro_presupuesto()->delete();

      if(Storage::disk('recibos')->exists($recibo->presupuesto_url)){
        Storage::disk('recibos')->delete($recibo->presupuesto_url);
      }
      $recibo->delete();

      DB::commit();
      return response()->json('presupuesto eliminado', 200);
    }catch(\Exception $e){
      DB::rollBack();
      return response()->json('error al eliminar el presupuesto', 500);
    }
  }
}
