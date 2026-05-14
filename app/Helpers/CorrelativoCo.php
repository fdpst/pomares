<?php

namespace App\Helpers;

use App\Models\Liquidacion;

/**
 * Número de documento CO-N (N entero, sin ceros forzados a la izquierda).
 * Serie única por usuario solo en liquidaciones. Las autofacturas usan CorrelativoAutofacturaRecibida.
 */
class CorrelativoCo
{
    public static function siguiente(int $userId): string
    {
        $max = 0;

        foreach (Liquidacion::where('user_id', $userId)->whereNotNull('nro_factura')->pluck('nro_factura') as $nro) {
            if (preg_match('/^CO-(\d+)$/i', trim((string) $nro), $m)) {
                $max = max($max, (int) $m[1]);
            }
        }

        return 'CO-' . ($max + 1);
    }
}
