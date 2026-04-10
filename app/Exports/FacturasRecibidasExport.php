<?php

namespace App\Exports;

use App\Models\Recibo; 
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithDrawings;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Illuminate\Support\Collection;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class FacturasRecibidasExport implements 
FromCollection, WithHeadings,WithCustomStartCell,ShouldAutoSize,WithDrawings,WithEvents
{
    public $data = [];
    function __construct($data){
        $this->data = $data;
    }
    public function registerEvents(): array
{
    return [
        AfterSheet::class => function(AfterSheet $event) {
            $data = $this->data;
            $event->sheet->setCellValue('C1', 'REGISTRO I.V.A.');
            $event->sheet->setCellValue('C2', 'Empresa:'.$data['empresa']);
            $event->sheet->setCellValue('C3', 'Desde:'.$data['desde']->format('d/m/Y'));
            $event->sheet->setCellValue('D3', 'Hasta:'.$data['hasta']->format('d/m/Y'));
            $event->sheet->setCellValue('D2', 'Ejercico:'.$data['ejercicio']);
            $event->sheet->setCellValue('E2', 'Fecha:'.$data['fecha']->format('d/m/Y'));

        }
    ];
}
    public function drawings()
    {
        $drawing = new Drawing();
        $drawing->setName('Logo');
        $drawing->setDescription('Martí Pomares, S.L');
        $drawing->setPath(public_path('/logo.jpg'));
        $drawing->setHeight(90);
        $drawing->setCoordinates('A1');

        return $drawing;
    }
    public function headings(): array
    {
        return ['Nº REG.',
        'FECHA FACT.',
        'CUENTA',
        'CLIENTE',
        'C.I.F.',
        'Nº DOC.',
        'T.FACT',
        'BASE IMP.',
        '% I.V.A.',
        'IMP. IVA',
        '% R.E',
        'IMP. R.E.',
        'RETENCIÓN',
        'TOTAL',
        'TIPO IVA',
        'CLAVE OP.',
        'PAÍS',
        'NIF EXTRANJERO',
        'OBSERVACIONES',];
    }
    public function startCell(): string
    {
        return 'A7';
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $data = new Collection();
        $index = 1;
        $base = 0;
        $imp_iva =0;
        $total = 0;
        $page = 0;
        $ivas= [];
        
        foreach($this->data['recibos'] as $recibo){
            $subtotal = $recibo->cantidad*$recibo->precio;
        $tipo_iva=  $recibo->iva;
        $iva = $subtotal*$tipo_iva/100;
            $data->push([
                $index,
                \Carbon\Carbon::parse($recibo->factura?->fecha)->format('d/m/Y'),
                $recibo?->factura?->proveedor?->cuentaContable?->numero??'000000000',
                $recibo?->factura?->proveedor?->nombre,
                $recibo?->factura?->proveedor?->cif,
                $recibo?->factura?->nro_factura,
                'ORD',
                number_format( $subtotal,2,','.''),
                number_format( $tipo_iva,2,','.''),
                number_format( $iva,2,','.''),
                '',
                '',
                '',
                number_format( $recibo->total,2,','.''),
                $recibo->iva>0?'Régimen general':'Exento',
                '',
                '',
                '',
                ''
            ]);
            $index++;
            if(!isset($ivas[number_format( $tipo_iva,2,','.'')])){
                $ivas[number_format( $tipo_iva,2,','.'')] = ['base'=>0,'iva'=>0];
            }
            $ivas[number_format( $tipo_iva,2,','.'')]['iva'] +=$iva;
            $ivas[number_format( $tipo_iva,2,','.'')]['base'] +=$subtotal;

            $base += $subtotal;
            $imp_iva += $iva;
            $total += $recibo->total;
        }
        $data->push([
            '','','','',
            'Total',
            '','',
            number_format( $base,2,','.''),
            '',
            number_format( $imp_iva,2,','.''),
            '','','',
            number_format( $total,2,','.''),
            '',
            '',
            '',
            '',
        ]);
        $data->push(['']);
        $data->push(['']);
        $data->push(['']);
        $data->push([
            'BASE IMPONIBLE',
            '% I.V.A',
            'CUOTA I.V.A.',
        ]);
        foreach($ivas as $key=>$iva){
            $data->push([
                number_format( $iva['base'],2,','.''),
                $key,
                number_format( $iva['iva'],2,','.''),
        
            ]);

        }
        return $data;
    }
}
