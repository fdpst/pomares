<?php

namespace App\Http\Controllers\ApiControllers;

use App\Http\Controllers\Controller;
use App\Http\Resources\MorososResource;
use Illuminate\Http\Request;
use App\Models\Deuda;
use App\Helpers\GestorHelper;

class MorososController extends Controller
{
    public function getMorosos(Request $request, $user_id = null)
    {
        // Usar el helper para obtener el user_id correcto (cliente_id si es gestor)
        $effectiveUserId = GestorHelper::getUserId($request);

        if (!$effectiveUserId) {
            return response()->json(['error' => 'No tiene acceso a este recurso'], 403);
        }

        // Solo facturas (NroFactura): los albaranes/notas no deben aparecer como pendientes de pago.
        $morosos = GestorHelper::applyUserIdScope(Deuda::query(), $request)
            ->with('deuda.recibo.cliente')
            ->whereColumn('total', '>', 'pagado')
            ->where('deuda_type', 'App\Models\NroFactura')
            ->whereHas('deuda', function ($q) use ($request) {
                $q->whereHas('recibo', function ($q2) use ($request) {
                    GestorHelper::applyUserIdScope($q2, $request);
                });
            })
            ->orderBy('created_at', 'DESC')
            ->get();
        $morosos_resource = MorososResource::collection($morosos);
        return response()->json($morosos_resource, 200);
    }

    /**
     * Devuelve la información de una factura/nota pendiente de pago por identi y tipo.
     * Uso: ?identi=1&tipo=Factura o ?identi=1&tipo=Nota
     */
    public function getFacturaPendienteInfo(Request $request)
    {
        $effectiveUserId = GestorHelper::getUserId($request);

        if (!$effectiveUserId) {
            return response()->json(['error' => 'No tiene acceso a este recurso'], 403);
        }

        $identi = $request->query('identi');
        $tipo = $request->query('tipo');

        if (!$identi || !$tipo) {
            return response()->json(['error' => 'Faltan identi o tipo'], 422);
        }

        $numero = ltrim((string) $identi, '0') ?: '0';
        $esFactura = in_array(strtolower($tipo), ['factura', 'fac'], true);

        $baseQuery = GestorHelper::applyUserIdScope(Deuda::query(), $request)
            ->whereColumn('total', '>', 'pagado')
            ->with('deuda.recibo.cliente');

        if ($esFactura) {
            $deuda = (clone $baseQuery)
                ->where('deuda_type', 'App\Models\NroFactura')
                ->whereHasMorph('deuda', ['App\Models\NroFactura'], function ($q) use ($numero, $request) {
                    $q->where('nro_factura', $numero)
                        ->whereHas('recibo', function ($q2) use ($request) {
                            GestorHelper::applyUserIdScope($q2, $request);
                        });
                })
                ->first();
        } else {
            $deuda = (clone $baseQuery)
                ->where('deuda_type', 'App\Models\NroNota')
                ->whereHasMorph('deuda', ['App\Models\NroNota'], function ($q) use ($numero, $request) {
                    $q->where('nro_nota', $numero)
                        ->whereHas('recibo', function ($q2) use ($request) {
                            GestorHelper::applyUserIdScope($q2, $request);
                        });
                })
                ->first();
        }

        if (!$deuda) {
            return response()->json(['error' => 'Factura o nota pendiente no encontrada'], 404);
        }

        // Asegurar filtro por user_id del recibo (gestor puede ver otro cliente)
        $recibo = $deuda->deuda->recibo ?? null;
        if (!$recibo || !\App\Helpers\GestorHelper::ownsUserIdRow($request, $recibo->user_id)) {
            return response()->json(['error' => 'No tiene acceso a este recurso'], 403);
        }

        $resource = new MorososResource($deuda);
        return response()->json($resource->toArray($request), 200);
    }
}
