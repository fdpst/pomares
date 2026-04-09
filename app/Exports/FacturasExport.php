<?php

namespace App\Exports;

use App\Models\Recibo; 
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMapping;
use Illuminate\Support\Collection;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class FacturasExport implements FromCollection, WithStyles, ShouldAutoSize
{
    public $facturas = [];
    public $request = [];
    private array $metodosCobro = [
        'pago_uno' => 'Transferencia Bancaria',
        'pago_dos' => 'Giro Bancario',
        'pago_tres' => 'Efectivo',
        'pago_cuatro' => 'Paypal',
        'pago_cinco' => 'Bizum',
    ];

    function __construct($fac){
        $this->request = $fac;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $data = new Collection();
        $data->push(
            [
            'Cliente-NIF',
            'Cliente- Nombre o razón social',
            'Cliente - País',
            'número',
            'fecha',
            'Totales-Base imponible 4% IVA',
            'Concepto-% descuento',
            'Totales-Cuota 4% IVA',
            'Total Factura',
            'Tipo ingreso',
            'Notas privadas',
            'Tipo operación',
            'Fecha operación',
            'Estado',
            'Cobro - Fecha',
            'Cobro - Importe',
            'Cobro - Método de cobro',
            'Cobro - Tipo de método de cobro',
            'Cobro - Número de cuenta o tarjeta',
            ]
        );

        // $this->request es una Collection de modelos Recibo, no un Request
        foreach($this->request as $factura){
            $nroFactura = $factura->nro_factura ? $factura->nro_factura->nro_factura : '';
            $clienteNombre = $factura->cliente ? $factura->cliente->nombre : '';
            $clienteDni = $factura->cliente ? ($factura->cliente->dni ?? $factura->cliente->cif ?? '') : '';
            $clientePais = $factura->cliente?->pais?->nombre ?? '';
            $total = $factura->total ?? 0;
            $tipoIva = (float) ($factura->tipo_iva ?? 0);
            $subtotal = (float) ($factura->sub_total ?? 0);
            if ($subtotal <= 0 && $total > 0 && $tipoIva >= 0) {
                $subtotal = $tipoIva > 0 ? ($total / (1 + ($tipoIva / 100))) : $total;
            }
            $iva = (float) ($factura->iva ?? ($total - $subtotal));
            $descuentoPorcentaje = (float) ($factura->porcentaje_descuento ?? 0);
            $clientePaisNormalizado = strtolower(trim((string) $clientePais));
            $tipoOperacion = in_array($clientePaisNormalizado, ['españa', 'espana', 'spain'], true)
                ? 'Nacional'
                : 'Internacional';
            $estado = $factura->pagado ? 'Pagada' : 'Pendiente';
            $metodoCodigo = $factura->metodo_pago ?? '';
            $metodoNombre = $this->metodosCobro[$metodoCodigo] ?? $metodoCodigo;
            $tipoMetodoCobro = $metodoCodigo ? 'Manual' : '';
            $tipoIngreso = '700';
            
            $data->push(
                [
                $clienteDni,
                $clienteNombre,
                $clientePais,
                $nroFactura,
                Carbon::parse($factura->fecha)->format('d-m-Y'),
                number_format($subtotal, 2, ',', '.'),
                number_format($descuentoPorcentaje, 2, ',', '.') . '%',
                number_format($iva, 2, ',', '.'),
                number_format((float) $total, 2, ',', '.'),
                $tipoIngreso,
                $factura->observaciones ?? '',
                $tipoOperacion,
                Carbon::parse($factura->fecha)->format('d-m-Y'),
                $estado,
                $factura->fecha_pago ? Carbon::parse($factura->fecha_pago)->format('d-m-Y') : '',
                $factura->pagado ? number_format((float) $total, 2, ',', '.') : '',
                $metodoNombre,
                $tipoMetodoCobro,
                $factura->detalle_pago ?? '',
                ]
            );
        }
        return $data;
    }

    public function styles(Worksheet $sheet): array
    {
        $lastColumn = 'S';
        $headerRange = "A1:{$lastColumn}1";

        $sheet->getStyle($headerRange)->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['argb' => 'FF000000'],
            ],
            'fill' => [
                'fillType' => 'solid',
                'startColor' => ['argb' => 'FFD9E1F2'],
            ],
            'alignment' => [
                'horizontal' => 'center',
                'vertical' => 'center',
            ],
        ]);

        return [];
    }
}
