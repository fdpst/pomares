<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class GestorHelper
{
    /**
     * Obtiene el user_id correcto según el rol del usuario
     * Si es gestor, retorna el cliente_id seleccionado
     * Si no es gestor, retorna el user_id del usuario autenticado o el proporcionado
     * 
     * @param Request|null $request
     * @param int|null $routeUserId - user_id de la ruta (opcional)
     * @return int|null
     */
    public static function getUserId(Request $request = null, $routeUserId = null)
    {
        $user = Auth::user();
        
        // Si no hay usuario autenticado, retornar null
        if (!$user) {
            return null;
        }
        
        // Si es gestor, verificar si hay un cliente seleccionado
        if ($user->role == 3) {
            // Intentar obtener el cliente_id del header
            // Laravel puede leer headers personalizados de diferentes formas
            $clienteId = null;
            if ($request) {
                // Intentar diferentes formas de leer el header
                $clienteId = $request->header('X-Selected-Cliente-Id');
                // Si no funciona, intentar sin el prefijo HTTP_ que Laravel a veces agrega
                if (!$clienteId) {
                    $clienteId = $request->header('HTTP_X_SELECTED_CLIENTE_ID');
                }
                // Otra forma alternativa
                if (!$clienteId) {
                    $allHeaders = $request->headers->all();
                    foreach ($allHeaders as $key => $value) {
                        if (strtolower($key) === 'x-selected-cliente-id' || strtolower($key) === 'http-x-selected-cliente-id') {
                            $clienteId = is_array($value) ? $value[0] : $value;
                            break;
                        }
                    }
                }
            }
            
            // Si no está en el header, intentar del body (para POST/PUT/PATCH)
            // Esta es la forma más confiable porque el interceptor de axios lo agrega al body
            if (!$clienteId && $request) {
                $clienteId = $request->input('cliente_id');
            }
            
            // Si no está en el body, intentar del query parameter (para GET)
            if (!$clienteId && $request) {
                $clienteId = $request->query('cliente_id');
            }
            
            // NO usar el primer cliente como fallback automático
            // El gestor debe seleccionar explícitamente un cliente
            // Si no hay cliente_id, retornar null para que la validación falle
            
            // Si hay un cliente_id, validar que el gestor tenga acceso
            if ($clienteId) {
                // Convertir a entero para comparación
                $clienteId = (int) $clienteId;
                $cliente = $user->clientesAsociados()->where('users.id', $clienteId)->first();
                if ($cliente) {
                    return $clienteId;
                }
            }
            
            // Si no hay cliente seleccionado o no tiene acceso, retornar null
            // NO usar el routeUserId porque sería el user_id del gestor, no del cliente
            return null;
        }
        
        // Para otros roles, retornar el user_id de la ruta si existe, sino el del usuario autenticado
        return $routeUserId ?? $user->id;
    }
    
    /**
     * Valida que el gestor tenga acceso al cliente especificado
     */
    public static function validateGestorAccess($clienteId)
    {
        $user = Auth::user();
        
        if ($user->role != 3) {
            return true; // No es gestor, no necesita validación
        }
        
        $cliente = $user->clientesAsociados()->where('users.id', $clienteId)->first();
        return $cliente !== null;
    }
}

