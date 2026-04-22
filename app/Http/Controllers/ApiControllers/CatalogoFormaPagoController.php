<?php

namespace App\Http\Controllers\ApiControllers;

use App\Helpers\GestorHelper;
use App\Http\Controllers\Controller;
use App\Models\CatalogoFormaPago;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CatalogoFormaPagoController extends Controller
{
    /**
     * Lista las formas de pago del usuario efectivo.
     * Si no hay ninguna, crea "GIRO BANCARIO" por defecto.
     */
    public function index(Request $request)
    {
        $effectiveUserId = GestorHelper::getUserId($request);
        if (! $effectiveUserId) {
            return response()->json(['error' => 'No tiene acceso a este recurso'], 403);
        }

        $count = GestorHelper::applyUserIdScope(CatalogoFormaPago::query(), $request)->count();
        if ($count === 0) {
            CatalogoFormaPago::create([
                'user_id' => $effectiveUserId,
                'descripcion' => 'GIRO BANCARIO',
            ]);
        }

        $rows = GestorHelper::applyUserIdScope(CatalogoFormaPago::query(), $request)
            ->orderBy('descripcion')
            ->get();

        return response()->json($rows, 200);
    }

    public function store(Request $request)
    {
        $effectiveUserId = GestorHelper::getUserId($request);
        if (! $effectiveUserId) {
            return response()->json(['error' => 'No tiene acceso a este recurso'], 403);
        }

        $validated = $request->validate([
            'descripcion' => [
                'required',
                'string',
                'max:120',
                Rule::unique('catalogo_formas_pago', 'descripcion')->where(function ($q) use ($request) {
                    return GestorHelper::applyUserIdScope($q, $request);
                }),
            ],
        ]);

        $row = CatalogoFormaPago::create([
            'user_id' => $effectiveUserId,
            'descripcion' => trim($validated['descripcion']),
        ]);

        return response()->json($row, 201);
    }

    public function update(Request $request, int $id)
    {
        $effectiveUserId = GestorHelper::getUserId($request);
        if (! $effectiveUserId) {
            return response()->json(['error' => 'No tiene acceso a este recurso'], 403);
        }

        $row = GestorHelper::applyUserIdScope(CatalogoFormaPago::query(), $request)->findOrFail($id);

        $validated = $request->validate([
            'descripcion' => [
                'required',
                'string',
                'max:120',
                Rule::unique('catalogo_formas_pago', 'descripcion')
                    ->where(function ($q) use ($request) {
                        return GestorHelper::applyUserIdScope($q, $request);
                    })
                    ->ignore($row->id),
            ],
        ]);

        $row->descripcion = trim($validated['descripcion']);
        $row->save();

        return response()->json($row, 200);
    }

    public function destroy(Request $request, int $id)
    {
        $effectiveUserId = GestorHelper::getUserId($request);
        if (! $effectiveUserId) {
            return response()->json(['error' => 'No tiene acceso a este recurso'], 403);
        }

        $row = GestorHelper::applyUserIdScope(CatalogoFormaPago::query(), $request)->findOrFail($id);

        \App\Models\Proveedor::where('catalogo_forma_pago_id', $row->id)->update([
            'catalogo_forma_pago_id' => null,
        ]);

        $row->delete();

        return response()->json(['success' => true], 200);
    }
}
