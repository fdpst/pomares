<?php

namespace App\Helpers;

use App\Models\FacturaRecibida;
use App\Models\Proveedor;
use Carbon\Carbon;

/**
 * Numeración de autofacturas (facturas recibidas): CO-{n}/{año}-{Nº PV}.
 * {n} crece por usuario, punto de venta y año natural. {Nº PV} = nro_proveedor del proveedor o, si no hay, su id.
 */
class CorrelativoAutofacturaRecibida
{
    public static function siguiente(int $userId, int $proveedorId, $fechaFactura): string
    {
        $fecha = $fechaFactura ? Carbon::parse($fechaFactura) : Carbon::now();
        $year = (int) $fecha->format('Y');
        $pvNum = self::numeroPuntoVenta($proveedorId);

        $rows = FacturaRecibida::query()
            ->where('user_id', $userId)
            ->where('proveedor_id', $proveedorId)
            ->whereYear('fecha', $year)
            ->whereNotNull('nro_factura')
            ->pluck('nro_factura');

        $max = 0;
        $yq = preg_quote((string) $year, '/');
        $pvq = preg_quote((string) $pvNum, '/');
        $patternNuevo = '/^CO-(\d+)\/' . $yq . '-' . $pvq . '$/i';
        $patternLegacy = '/^CO-FACTURA\/' . $yq . '-(\d+)/i';

        foreach ($rows as $nro) {
            $s = trim((string) $nro);
            if (preg_match($patternNuevo, $s, $m)) {
                $max = max($max, (int) $m[1]);
                continue;
            }
            if (preg_match($patternLegacy, $s, $m)) {
                $max = max($max, (int) $m[1]);
            }
        }

        $next = $max + 1;

        return 'CO-' . $next . '/' . $year . '-' . $pvNum;
    }

    /** Nº punto de venta del catálogo (nro_proveedor); si no está definido, id del proveedor. */
    public static function numeroPuntoVenta(int $proveedorId): int
    {
        $p = Proveedor::query()->find($proveedorId);
        if (!$p) {
            return $proveedorId;
        }
        $n = (int) ($p->nro_proveedor ?? 0);

        return $n > 0 ? $n : (int) $p->id;
    }
}
