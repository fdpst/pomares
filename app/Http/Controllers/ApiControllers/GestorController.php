<?php

namespace App\Http\Controllers\ApiControllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GestorController extends Controller
{
    /**
     * Obtener clientes asociados al gestor autenticado
     */
    public function getClientesAsociados()
    {
        $user = Auth::user();

        if ($user->role != 3) {
            return response()->json([
                'status' => 403,
                'message' => 'Solo los gestores pueden acceder a esta función',
            ], 403);
        }

        $clientes = $user->clientesAsociados()
            ->select('users.id', 'users.name', 'users.email', 'users.nombre_fiscal')
            ->orderBy('users.name')
            ->get();

        return response()->json([
            'status' => 200,
            'message' => 'Ok',
            'clientes' => $clientes,
        ]);
    }

    /**
     * Cambiar el contexto del gestor (cliente seleccionado)
     * Esto guarda el cliente_id seleccionado en la sesión o retorna el cliente
     */
    public function cambiarContexto(Request $request)
    {
        $user = Auth::user();

        if ($user->role != 3) {
            return response()->json([
                'status' => 403,
                'message' => 'Solo los gestores pueden acceder a esta función',
            ], 403);
        }

        $clienteId = $request->input('cliente_id');

        if (!$clienteId) {
            return response()->json([
                'status' => 400,
                'message' => 'El cliente_id es requerido',
            ], 400);
        }

        // Verificar que el gestor tenga acceso a este cliente
        $cliente = $user->clientesAsociados()->where('users.id', $clienteId)->first();

        if (!$cliente) {
            return response()->json([
                'status' => 403,
                'message' => 'No tiene acceso a este cliente',
            ], 403);
        }

        return response()->json([
            'status' => 200,
            'message' => 'Contexto cambiado correctamente',
            'cliente' => $cliente,
        ]);
    }
}
