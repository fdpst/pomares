<?php

namespace App\Http\Controllers\ApiControllers;

use App\Models\User;
use App\Models\TiposGasto;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\ProveedorRequest;
use App\Helpers\GestorHelper;

class TiposGastoController extends Controller
{
  public function getTiposGasto(Request $request, $user_id = null){
    $effectiveUserId = GestorHelper::getUserId($request, $user_id);
    if (!$effectiveUserId) {
      return response()->json([], 200);
    }
    $tiposGasto = TiposGasto::where('user_id', '=', $effectiveUserId)->orderBy('id', 'DESC')->get();
    return response()->json($tiposGasto, 200);
  }

  /*public function getProveedorByid($proveedor_id){
    $proveedor = Proveedor::find($proveedor_id);
    return response()->json($proveedor, 200);
  }*/

  public function saveTiposGasto(Request $request){
    $effectiveUserId = GestorHelper::getUserId($request, $request->user_id);

    if (!$effectiveUserId) {
      return response()->json(['error' => 'No tiene acceso a este recurso'], 403);
    }

    $user = User::find($effectiveUserId);

    $id = $request->filled('id') ? $request->id : null;

    $exists = $user->tipo_gasto()->where('nombre', $request->nombre)->exists();

    if($exists){
       return response()->json(['mensaje' => 'Tipo de gasto ya existente'], 301);
    }

    $tipo_gasto = $user->tipo_gasto()->updateOrCreate(['id' => $id], ['nombre' => $request->nombre]);
    return response()->json($tipo_gasto, 200);
  }

  public function deleteTiposGasto(Request $request, $tipos_gasto_id){
    $effectiveUserId = GestorHelper::getUserId($request);

    if (!$effectiveUserId) {
      return response()->json(['error' => 'No tiene acceso a este recurso'], 403);
    }

    $tiposGasto = TiposGasto::where('id', $tipos_gasto_id)->where('user_id', $effectiveUserId)->first();
    if (!$tiposGasto) {
      return response()->json(['error' => 'Tipo de gasto no encontrado'], 404);
    }
    $tiposGasto->delete();
    return response()->json($tiposGasto, 200);
  }
}
