<?php

namespace App\Http\Controllers\ApiControllers;

use App\Models\Servicio;
use App\Models\ServicioPrecioCambio;
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
    $effectiveUserId = GestorHelper::getUserId($request);

    if (!$effectiveUserId) {
      return response()->json(request()->has('amount') ? ['data' => [], 'total' => 0] : [], 200);
    }

    $query = GestorHelper::applyUserIdScope(
      Servicio::with(['CuentaContable', 'Iva'])->orderBy('created_at', 'DESC'),
      $request
    );

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

    if ($request->filled('fecha_desde')) {
      $fd = substr((string) $request->input('fecha_desde'), 0, 10);
      $query->whereDate('created_at', '>=', $fd);
    }
    if ($request->filled('fecha_hasta')) {
      $fh = substr((string) $request->input('fecha_hasta'), 0, 10);
      $query->whereDate('created_at', '<=', $fh);
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

    $servicio = GestorHelper::applyUserIdScope(
      Servicio::query()->where('venta', $venta)->orderBy('nro', 'DESC'),
      $request
    )->first();
    return $servicio?->nro ?? 0;
  }
  public function getServicioByid($servicio_id)
  {
    $servicio = Servicio::with(['CuentaContable', 'Iva'])->find($servicio_id);
    return response()->json($servicio, 200);
  }

  /**
   * Historial de cambios de precio (solo productos, venta = 0).
   */
  public function getServicioPrecioCambios(Request $request, $servicio_id)
  {
    $effectiveUserId = GestorHelper::getUserId($request);

    if (!$effectiveUserId) {
      return response()->json(['error' => 'No tiene acceso a este recurso'], 403);
    }

    $servicio = GestorHelper::applyUserIdScope(
      Servicio::query()->where('id', (int) $servicio_id)->where('venta', 0),
      $request
    )->first();

    if (!$servicio) {
      return response()->json(['error' => 'Producto no encontrado'], 404);
    }

    $rows = GestorHelper::applyUserIdScope(
      ServicioPrecioCambio::query()->where('servicio_id', $servicio->id)->orderByDesc('id'),
      $request
    )->get(['precio_anterior', 'precio_nuevo', 'created_at']);

    return response()->json($rows, 200);
  }

  public function saveServicio(Request $request)
  {
    $effectiveUserId = GestorHelper::getUserId($request);

    if (!$effectiveUserId) {
      return response()->json(['error' => 'No tiene acceso a este recurso'], 403);
    }

    $nuevoPrecio = $this->parseHelper->parseEuroNumber($request->precio);
    $venta = (int) $request->venta;

    $existente = null;
    $idRaw = $request->input('id');
    if ($idRaw !== null && $idRaw !== '' && $idRaw !== 'null') {
      $existente = GestorHelper::applyUserIdScope(
        Servicio::query()->where('id', $idRaw),
        $request
      )->first();
    }

    $servicio = Servicio::updateOrCreate(['id' => $request->id], [
      'nro' => $request->nro,
      'descripcion' => $request->descripcion,
      'precio' => $nuevoPrecio,
      'iva_percent' => $request->iva_percent !== null ? floatval($request->iva_percent) : 0,
      'venta' => $request->venta,
      'user_id' => $effectiveUserId,
      'iva_id' => $request->iva_id ?? null,
    ]);

    if ($venta === 0 && $existente !== null && $this->precioServicioHaCambiado((float) ($existente->precio ?? 0), (float) $nuevoPrecio)) {
      ServicioPrecioCambio::create([
        'servicio_id' => (int) $servicio->id,
        'user_id' => (int) $effectiveUserId,
        'precio_anterior' => (float) ($existente->precio ?? 0),
        'precio_nuevo' => (float) $nuevoPrecio,
      ]);
    }
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


  private function precioServicioHaCambiado(float $anterior, float $nuevo): bool
  {
    return round($anterior, 4) !== round($nuevo, 4);
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
