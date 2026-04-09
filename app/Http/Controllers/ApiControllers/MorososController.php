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
        $effectiveUserId = GestorHelper::getUserId($request, $user_id);

        if (!$effectiveUserId) {
            return response()->json(['error' => 'No tiene acceso a este recurso'], 403);
        }

        // Solo facturas (NroFactura): los albaranes/notas no deben aparecer como pendientes de pago.
        // Filtro doble: Deuda.user_id y Recibo.user_id para garantizar que solo se muestren
        // pendientes del cliente/empresa correcto y no se crucen datos entre usuarios.
        $morosos = Deuda::where('user_id', '=', $effectiveUserId)
            ->with('deuda.recibo.cliente')
            ->whereColumn('total', '>', 'pagado')
            ->where('deuda_type', 'App\Models\NroFactura')
            ->whereHas('deuda', function ($q) use ($effectiveUserId) {
                $q->whereHas('recibo', function ($q2) use ($effectiveUserId) {
                    $q2->where('user_id', $effectiveUserId);
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
        $effectiveUserId = GestorHelper::getUserId($request, $request->query('user_id'));

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

        $baseQuery = Deuda::where('user_id', $effectiveUserId)
            ->whereColumn('total', '>', 'pagado')
            ->with('deuda.recibo.cliente');

        if ($esFactura) {
            $deuda = (clone $baseQuery)
                ->where('deuda_type', 'App\Models\NroFactura')
                ->whereHasMorph('deuda', ['App\Models\NroFactura'], function ($q) use ($numero, $effectiveUserId) {
                    $q->where('nro_factura', $numero)
                        ->whereHas('recibo', function ($q2) use ($effectiveUserId) {
                            $q2->where('user_id', $effectiveUserId);
                        });
                })
                ->first();
        } else {
            $deuda = (clone $baseQuery)
                ->where('deuda_type', 'App\Models\NroNota')
                ->whereHasMorph('deuda', ['App\Models\NroNota'], function ($q) use ($numero, $effectiveUserId) {
                    $q->where('nro_nota', $numero)
                        ->whereHas('recibo', function ($q2) use ($effectiveUserId) {
                            $q2->where('user_id', $effectiveUserId);
                        });
                })
                ->first();
        }

        if (!$deuda) {
            return response()->json(['error' => 'Factura o nota pendiente no encontrada'], 404);
        }

        // Asegurar filtro por user_id del recibo (gestor puede ver otro cliente)
        $recibo = $deuda->deuda->recibo ?? null;
        if (!$recibo || (int) $recibo->user_id !== (int) $effectiveUserId) {
            return response()->json(['error' => 'No tiene acceso a este recurso'], 403);
        }

        $resource = new MorososResource($deuda);
        return response()->json($resource->toArray($request), 200);
    }
}
