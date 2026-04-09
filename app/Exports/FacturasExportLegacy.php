<?php

namespace App\Exports;

use Carbon\Carbon;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;

/**
 * Formato histórico de exportación de facturas enviadas (7 columnas).
 * Se usa para empresas distintas a la configurada en FacturaController.
 */
class FacturasExportLegacy implements FromCollection
{
    public $request = [];

    public function __construct($fac)
    {
        $this->request = $fac;
    }

    public function collection()
    {
        $data = new Collection();
        $data->push(
            [
                'NUMERO FACTURA',
                'FECHA',
                'NOMBRE',
                'CIF',
                'SUB-TOTAL',
                'IVA',
                'TOTAL',
            ]
        );

        foreach ($this->request as $factura) {
            $nroFactura = $factura->nro_factura ? $factura->nro_factura->nro_factura : '';
            $clienteNombre = $factura->cliente ? $factura->cliente->nombre : '';
            $clienteDni = $factura->cliente ? ($factura->cliente->dni ?? $factura->cliente->cif ?? '') : '';
            $total = $factura->total ?? 0;
            $subtotal = $total > 0 ? ($total / 1.21) : 0;
            $iva = $total - $subtotal;

            $data->push(
                [
                    $nroFactura,
                    Carbon::parse($factura->fecha)->format('d-m-Y'),
                    $clienteNombre,
                    $clienteDni,
                    $subtotal,
                    $iva,
                    $total,
                ]
            );
        }

        return $data;
    }
}
