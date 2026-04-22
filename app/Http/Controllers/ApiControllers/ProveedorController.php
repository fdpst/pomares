<?php

namespace App\Http\Controllers\ApiControllers;

use App\Models\CuentaContable;
use App\Models\Proveedor;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\ProveedorRequest;
use App\Models\CategoriaCuentaContable;
use App\Helpers\GestorHelper;

class ProveedorController extends Controller
{
  public function getProveedores(Request $request, $user_id = null){
    // Usar el helper para obtener el user_id correcto (cliente_id si es gestor)
    $effectiveUserId = GestorHelper::getUserId($request);
    
    if (!$effectiveUserId) {
      return response()->json(['error' => 'No tiene acceso a este recurso'], 403);
    }
    
    $proveedores = GestorHelper::applyUserIdScope(Proveedor::query(), $request)->orderBy('created_at', 'DESC')->get();
    return response()->json($proveedores, 200);
  }

  public function getProveedorByid($proveedor_id){
    $proveedor = Proveedor::with(['cuentaContable'])->find($proveedor_id);
    return response()->json($proveedor, 200);
  }

  public function saveProveedor(ProveedorRequest $request){
    // Obtener el user_id correcto usando el helper (cliente_id si es gestor)
    $effectiveUserId = GestorHelper::getUserId($request);
    
    if (!$effectiveUserId) {
      return response()->json(['error' => 'No tiene acceso a este recurso'], 403);
    }
    
    // Preparar los datos del proveedor
    $proveedorData = $request->all();
    
    // Sobrescribir el user_id con el correcto (cliente_id si es gestor)
    $proveedorData['user_id'] = $effectiveUserId;
    
    $proveedor = Proveedor::updateOrCreate(['id' => $request->id], $proveedorData);

    if($request->cuenta_contable){
      $response = $this->crearCuentaContable($request->cuenta_contable, $proveedor);
      $proveedor->id_cuenta_contable = $response->id;
      $proveedor->save();
    }

    return response()->json($proveedor, 200);
  }

  private function crearCuentaContable($cuenta_contable, $proveedor){
    try{
      //410000010
      $categoria = CategoriaCuentaContable::find($cuenta_contable['id_categoria']);
      $cuenta = str_pad( $categoria->cuenta,9-strlen(strval($proveedor->nro_proveedor)),'0').strval($proveedor->nro_proveedor);
      
      $cuenta_c = CuentaContable::updateOrCreate([
        'id'=>$proveedor->id_cuenta_contable
      ],[
        'numero' => $cuenta,
        'partida' => $cuenta_contable['partida'],
        'id_categoria'=>$cuenta_contable['id_categoria']
      ]);

      return $cuenta_c;
    }catch(Exception $e){
      return ['code' => 400, 'error' => $e->getMessage()];
    }
  }

  public function deleteProveedor($proveedor_id){
    $proveedor = Proveedor::find($proveedor_id);
    $proveedor->delete();
    return response()->json($proveedor, 200);
  }

  public function getLastId(){
    try{
      $proveedor = Proveedor::orderBy('nro_proveedor','DESC')->first();
      $last_id = $proveedor?->nro_proveedor ?? 0;
      return response()->json(['success' => $last_id], 200);
    }catch(\Exception $e){
      return response()->json(['error' => $e->getMessage()], 400);
    }
  }
}
