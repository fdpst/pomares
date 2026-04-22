<?php

namespace App\Helpers;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class GestorHelper
{
    /**
     * Cuenta de datos de negocio:
     * 1) APP_SHARED_DATA_USER_ID (si está definido),
     * 2) Si el usuario es gestor/empleado (3/4) y la petición incluye un cliente asociado
     *    (header X-Selected-Cliente-Id o query/body cliente_id), ese id tras validar en `gestor_clientes`,
     * 3) En otro caso, el id del usuario autenticado.
     *
     * El cliente enviado no se acepta a ciegas: debe existir la asociación gestor→cliente.
     *
     * @deprecated El segundo parámetro se ignora; mantener solo por compatibilidad de firma.
     */
    public static function getUserId(Request $request = null, $routeUserId = null)
    {
        $user = Auth::user();

        if (!$user) {
            return null;
        }

        $shared = config('app.shared_data_user_id');
        if ($shared !== null && $shared !== '') {
            $sid = filter_var($shared, FILTER_VALIDATE_INT);
            if ($sid !== false && $sid > 0) {
                return (int) $sid;
            }
        }

        $role = (int) $user->role;
        if (in_array($role, [3, 4], true) && $request instanceof Request) {
            $cid = self::parsePositiveIntId(
                $request->header('X-Selected-Cliente-Id')
                ?? $request->query('cliente_id')
                ?? $request->input('cliente_id')
            );
            if ($cid !== null && self::userMayActAsCliente($user, $cid)) {
                return $cid;
            }
        }

        return (int) $user->id;
    }

    private static function parsePositiveIntId($raw): ?int
    {
        if ($raw === null || $raw === '') {
            return null;
        }
        $cid = filter_var($raw, FILTER_VALIDATE_INT);
        if ($cid === false || $cid <= 0) {
            return null;
        }

        return (int) $cid;
    }

    private static function userMayActAsCliente(User $user, int $clienteId): bool
    {
        return $user->clientesAsociados()->where('users.id', $clienteId)->exists();
    }

    /**
     * Perfil explícito por id en ruta (p. ej. administración de usuarios). Si no hay ruta, mismo que getUserId().
     */
    public static function resolveUserProfileId(Request $request, $routeUserId = null): ?int
    {
        $tid = filter_var($routeUserId, FILTER_VALIDATE_INT);
        if ($tid !== false && $tid > 0) {
            return $tid;
        }

        return self::getUserId($request);
    }

    /**
     * Compatibilidad: antes validaba gestor-cliente; ya no se usa lógica por rol.
     */
    public static function validateGestorAccess($clienteId)
    {
        return true;
    }

    /**
     * Si es true, listados y comprobaciones de pertenencia filtran por la columna user_id (getUserId).
     * Si es false (por defecto), cualquier usuario autenticado ve y modifica datos guardados por cualquier otro.
     */
    public static function restrictQueriesByOwnerUserId(): bool
    {
        return filter_var(config('app.restrict_queries_by_owner_user_id', false), FILTER_VALIDATE_BOOLEAN);
    }

    /**
     * Añade where user_id = contexto salvo en modo datos abiertos.
     */
    public static function applyUserIdScope(Builder $query, ?Request $request = null, string $column = 'user_id'): Builder
    {
        if (! self::restrictQueriesByOwnerUserId()) {
            return $query;
        }

        $uid = self::getUserId($request);
        if (! $uid) {
            return $query->whereRaw('0 = 1');
        }

        return $query->where($column, $uid);
    }

    /**
     * Comprueba si la fila pertenece al tenant actual; en modo abierto siempre true.
     */
    public static function ownsUserIdRow(?Request $request, $rowUserId): bool
    {
        if (! self::restrictQueriesByOwnerUserId()) {
            return true;
        }

        $uid = self::getUserId($request);
        if (! $uid) {
            return false;
        }

        return (int) $rowUserId === (int) $uid;
    }
}
