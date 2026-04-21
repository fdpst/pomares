<?php

namespace App\Http\Controllers\ApiControllers;

use App\Helpers\GestorHelper;
use App\Http\Controllers\Controller;
use App\Models\Proveedor;
use App\Models\ProveedorComision;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ProveedorComisionController extends Controller
{
    public function index(Request $request, int $proveedor_id)
    {
        $effectiveUserId = GestorHelper::getUserId($request, $request->query('user_id'));

        if (! $effectiveUserId) {
            return response()->json(['error' => 'No tiene acceso a este recurso'], 403);
        }

        $proveedor = Proveedor::where('user_id', $effectiveUserId)->find($proveedor_id);
        if (! $proveedor) {
            return response()->json(['error' => 'Punto de venta no encontrado'], 404);
        }

        $rows = ProveedorComision::with('servicio')
            ->where('proveedor_id', $proveedor_id)
            ->get();

        return response()->json($rows, 200);
    }

    public function store(Request $request)
    {
        $effectiveUserId = GestorHelper::getUserId($request, $request->input('user_id'));

        if (! $effectiveUserId) {
            return response()->json(['error' => 'No tiene acceso a este recurso'], 403);
        }

        $request->validate([
            'proveedor_id' => 'required|integer',
            'servicio_id' => [
                'required',
                'integer',
                Rule::unique('proveedor_comisiones', 'servicio_id')->where(function ($q) use ($request) {
                    return $q->where('proveedor_id', $request->proveedor_id);
                }),
            ],
            'tipo' => 'required|in:porcentaje,importe',
            'valor' => 'required|numeric|min:0',
        ], [
            'servicio_id.unique' => 'Ya existe una comisión para este producto en este punto de venta.',
        ]);

        $proveedor = Proveedor::where('user_id', $effectiveUserId)->find($request->proveedor_id);
        if (! $proveedor) {
            return response()->json(['error' => 'Punto de venta no encontrado'], 404);
        }

        $row = ProveedorComision::create([
            'proveedor_id' => $proveedor->id,
            'servicio_id' => $request->servicio_id,
            'tipo' => $request->tipo,
            'valor' => $request->valor,
            'user_id' => $effectiveUserId,
        ]);

        return response()->json($row->load('servicio'), 201);
    }

    public function update(Request $request, int $id)
    {
        $effectiveUserId = GestorHelper::getUserId($request, $request->input('user_id'));

        if (! $effectiveUserId) {
            return response()->json(['error' => 'No tiene acceso a este recurso'], 403);
        }

        $comision = ProveedorComision::find($id);
        if (! $comision) {
            return response()->json(['error' => 'Comisión no encontrada'], 404);
        }

        $proveedor = Proveedor::where('user_id', $effectiveUserId)->find($comision->proveedor_id);
        if (! $proveedor) {
            return response()->json(['error' => 'Punto de venta no encontrado'], 404);
        }

        $request->validate([
            'proveedor_id' => 'required|integer',
            'servicio_id' => [
                'required',
                'integer',
                Rule::unique('proveedor_comisiones', 'servicio_id')
                    ->where(function ($q) use ($comision) {
                        return $q->where('proveedor_id', $comision->proveedor_id);
                    })
                    ->ignore($comision->id),
            ],
            'tipo' => 'required|in:porcentaje,importe',
            'valor' => 'required|numeric|min:0',
        ], [
            'servicio_id.unique' => 'Ya existe una comisión para este producto en este punto de venta.',
        ]);

        $comision->servicio_id = $request->servicio_id;
        $comision->tipo = $request->tipo;
        $comision->valor = $request->valor;
        $comision->save();

        return response()->json($comision->fresh('servicio'), 200);
    }

    public function destroy(Request $request, int $id)
    {
        $effectiveUserId = GestorHelper::getUserId($request, $request->query('user_id'));

        if (! $effectiveUserId) {
            return response()->json(['error' => 'No tiene acceso a este recurso'], 403);
        }

        $comision = ProveedorComision::find($id);
        if (! $comision) {
            return response()->json(['error' => 'Comisión no encontrada'], 404);
        }

        $proveedor = Proveedor::where('user_id', $effectiveUserId)->find($comision->proveedor_id);
        if (! $proveedor) {
            return response()->json(['error' => 'Punto de venta no encontrado'], 404);
        }

        $comision->delete();

        return response()->json(['success' => true], 200);
    }
}
