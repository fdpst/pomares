<?php

namespace App\Http\Controllers\ApiControllers;

use App\Helpers\GestorHelper;
use App\Http\Controllers\Controller;
use App\Models\Proveedor;
use App\Models\ProveedorComision;
use App\Models\Servicio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class ProveedorComisionController extends Controller
{
    public function index(Request $request, int $proveedorId)
    {
        $effectiveUserId = GestorHelper::getUserId($request);
        if (!$effectiveUserId) {
            return response()->json(['error' => 'No tiene acceso'], 403);
        }

        $proveedor = Proveedor::where('user_id', $effectiveUserId)->find($proveedorId);
        if (!$proveedor) {
            return response()->json(['error' => 'Distribuidor no encontrado'], 404);
        }

        $rows = ProveedorComision::with('servicio')
            ->where('proveedor_id', $proveedorId)
            ->orderBy('id')
            ->get();

        return response()->json($rows, 200);
    }

    public function store(Request $request)
    {
        $effectiveUserId = GestorHelper::getUserId($request, $request->user_id);
        if (!$effectiveUserId) {
            return response()->json(['error' => 'No tiene acceso'], 403);
        }

        $validator = Validator::make($request->all(), [
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
            'servicio_id.unique' => 'Ya existe una comisión para este producto en este distribuidor.',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        if ($request->tipo === 'porcentaje' && (float) $request->valor > 100) {
            return response()->json(['errors' => ['valor' => ['El porcentaje no puede superar 100.']]], 422);
        }

        $proveedor = Proveedor::where('user_id', $effectiveUserId)->find($request->proveedor_id);
        if (!$proveedor) {
            return response()->json(['error' => 'Distribuidor no encontrado'], 404);
        }

        $servicio = Servicio::where('user_id', $effectiveUserId)->where('venta', 0)->find($request->servicio_id);
        if (!$servicio) {
            return response()->json(['error' => 'Producto no encontrado o no es de compra'], 404);
        }

        $row = ProveedorComision::create([
            'proveedor_id' => $proveedor->id,
            'servicio_id' => $servicio->id,
            'tipo' => $request->tipo,
            'valor' => $request->valor,
            'user_id' => $effectiveUserId,
        ]);

        return response()->json($row->load('servicio'), 201);
    }

    public function update(Request $request, int $id)
    {
        $effectiveUserId = GestorHelper::getUserId($request, $request->user_id);
        if (!$effectiveUserId) {
            return response()->json(['error' => 'No tiene acceso'], 403);
        }

        $comision = ProveedorComision::find($id);
        if (!$comision || $comision->user_id != $effectiveUserId) {
            return response()->json(['error' => 'Comisión no encontrada'], 404);
        }

        $proveedor = Proveedor::where('user_id', $effectiveUserId)->find($comision->proveedor_id);
        if (!$proveedor) {
            return response()->json(['error' => 'Distribuidor no encontrado'], 404);
        }

        $validator = Validator::make($request->all(), [
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
            'servicio_id.unique' => 'Ya existe una comisión para este producto en este distribuidor.',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        if ($request->tipo === 'porcentaje' && (float) $request->valor > 100) {
            return response()->json(['errors' => ['valor' => ['El porcentaje no puede superar 100.']]], 422);
        }

        $servicio = Servicio::where('user_id', $effectiveUserId)->where('venta', 0)->find($request->servicio_id);
        if (!$servicio) {
            return response()->json(['error' => 'Producto no encontrado o no es de compra'], 404);
        }

        $comision->update([
            'servicio_id' => $servicio->id,
            'tipo' => $request->tipo,
            'valor' => $request->valor,
        ]);

        return response()->json($comision->fresh()->load('servicio'), 200);
    }

    public function destroy(Request $request, int $id)
    {
        $effectiveUserId = GestorHelper::getUserId($request);
        if (!$effectiveUserId) {
            return response()->json(['error' => 'No tiene acceso'], 403);
        }

        $comision = ProveedorComision::find($id);
        if (!$comision || $comision->user_id != $effectiveUserId) {
            return response()->json(['error' => 'Comisión no encontrada'], 404);
        }

        $comision->delete();

        return response()->json(['message' => 'Eliminada'], 200);
    }
}
