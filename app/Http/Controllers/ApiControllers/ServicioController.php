<?php

namespace App\Http\Controllers\ApiControllers;

use App\Models\Servicio;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\ProveedorRequest;
use App\Models\CategoriaCuentaContable;
use App\Models\CuentaContable;
use App\Models\Iva;
use App\Helpers\ParseHelper;
use App\Helpers\GestorHelper;

class ServicioController extends Controller
{
  protected $parseHelper;

  public function __construct(ParseHelper $parseHelper)
  {
    $this->parseHelper = $parseHelper;
  }

  public function getServicios(Request $request, $user_id = null)
  {
    $effectiveUserId = GestorHelper::getUserId($request, $user_id);

    if (!$effectiveUserId) {
      return response()->json(request()->has('amount') ? ['data' => [], 'total' => 0] : [], 200);
    }

    $query = Servicio::with(['CuentaContable', 'Iva'])
      ->where('user_id', '=', $effectiveUserId)
      ->orderBy('created_at', 'DESC');

    if ($request->venta != null) {
      $query->where('venta', $request->venta);
    }

    if ($request->filled('search') && is_string($request->search)) {
      $search = $request->search;
      $query->where(function ($q) use ($search) {
        $q->where('descripcion', 'LIKE', '%' . $search . '%')
          ->orWhere('nro', 'LIKE', '%' . $search . '%');
      });
    }

    $amount = $request->input('amount');
    if ($request->has('amount') && (int) $amount !== -1) {
      $itemsPerPage = (int) $amount ?: 15;
      $paginated = $query->paginate($itemsPerPage, ['*'], 'page', $request->input('page', 1));
      return response()->json([
        'data' => $paginated->items(),
        'total' => $paginated->total(),
        'current_page' => $paginated->currentPage(),
        'last_page' => $paginated->lastPage(),
      ], 200);
    }

    $servicios = $query->get();
    return response()->json($servicios, 200);
  }
  public function getLastNumber(Request $request, $venta)
  {
    $effectiveUserId = GestorHelper::getUserId($request);

    if (!$effectiveUserId) {
      return response()->json(0, 200);
    }

    $servicio = Servicio::where('venta', $venta)->where('user_id', $effectiveUserId)
      ->orderBy('nro', 'DESC')->first();
    return $servicio?->nro ?? 0;
  }
  public function getServicioByid($servicio_id)
  {
    $servicio = Servicio::with(['CuentaContable', 'Iva'])->find($servicio_id);
    return response()->json($servicio, 200);
  }

  public function saveServicio(Request $request)
  {
    $effectiveUserId = GestorHelper::getUserId($request, $request->user_id);

    if (!$effectiveUserId) {
      return response()->json(['error' => 'No tiene acceso a este recurso'], 403);
    }

    $servicio = Servicio::updateOrCreate(['id' => $request->id], [
      'nro' => $request->nro,
      'descripcion' => $request->descripcion,
      'precio' => $this->parseHelper->parseEuroNumber($request->precio),
      'iva_percent' => $request->iva_percent !== null ? floatval($request->iva_percent) : 0,
      'venta' => $request->venta,
      'user_id' => $effectiveUserId,
      'iva_id' => $request->iva_id ?? null,
    ]);
    if ($request->cuenta_contable) {
      $cuenta_contable = $this->crearCuentaContable($request->cuenta_contable, $servicio);
      $servicio->id_cuenta_contable = $cuenta_contable->id;
      $servicio->save();
    }
    return response()->json($servicio, 200);
  }

  public function deleteServicio($id)
  {
    $servicio = Servicio::find($id);
    $servicio->delete();
    return response()->json($servicio, 200);
  }


  private function crearCuentaContable($cuenta_contable, $servicio)
  {
    try {
      $categoria = CategoriaCuentaContable::find($cuenta_contable['id_categoria']);
      $cuenta = str_pad($categoria->cuenta, 9 - strlen(strval($servicio->nro)), '0') . strval($servicio->nro);

      $cuenta_c = CuentaContable::updateOrCreate([
        'id' => $servicio->id_cuenta_contable,
      ], [
        'numero' => $cuenta,
        'partida' => $cuenta_contable['partida'],
        'id_categoria' => $cuenta_contable['id_categoria']
      ]);

      return $cuenta_c;
    } catch (\Exception $e) {
      return ['code' => 400, 'error' => $e->getMessage()];
    }
  }
}
