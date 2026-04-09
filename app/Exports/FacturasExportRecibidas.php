<?php

namespace App\Exports;

use App\Models\Recibo;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMapping;
use Illuminate\Support\Collection;
use Carbon\Carbon;


class FacturasExportRecibidas implements FromCollection
{
    public $facturas = [];
    function __construct($fac)
    {
        $this->request = $fac;
    }

    public function headings(): array
    {
        return [['nro_factura', 'fecha', 'cliente nombre', 'CIF', 'sub-total', 'iva', 'total']];
    }

    /**
     * @return \Illuminate\Support\Collection
     */
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
                'TOTAL'
            ]
        );

        // $this->request es una Collection de modelos FacturaRecibida, no un Request
        foreach ($this->request as $factura) {
            $proveedor = $factura->proveedor;
            $nombre = $proveedor ? $proveedor->nombre : '';
            $cif = $proveedor ? $proveedor->cif : '';
            $total = $factura->total ?? 0;
            $subtotal = $total > 0 ? ($total / 1.21) : 0;
            $iva = $total - $subtotal;
            
            $data->push(
                [
                    $factura->nro_factura ?? '',
                    $factura->fecha ? Carbon::parse($factura->fecha)->format('d-m-Y') : '',
                    $nombre,
                    $cif,
                    $subtotal,
                    $iva,
                    $total
                ]
            );
        }
        return $data;
    }
}
