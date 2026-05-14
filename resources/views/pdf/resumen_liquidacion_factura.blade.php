<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Resumen de liquidación</title>
    <style>
        body { font-family: DejaVu Sans, Arial, sans-serif; font-size: 10.5px; color: #111; margin: 22px 28px; }
        .cabecera { width: 100%; border-collapse: collapse; margin-bottom: 14px; }
        .cabecera td { border: none; vertical-align: top; padding: 0; }
        .cabecera-fecha { font-size: 11px; width: 42%; }
        .cabecera-emisor { text-align: right; font-size: 10px; line-height: 1.35; }
        h1 { font-size: 15px; margin: 10px 0 12px 0; text-align: center; letter-spacing: 0.02em; }
        .intro { text-align: justify; line-height: 1.45; margin: 0 0 16px 0; font-size: 10px; }
        table.datos { width: 100%; border-collapse: collapse; margin-bottom: 6px; }
        table.datos th, table.datos td { border: 1px solid #333; padding: 5px 7px; vertical-align: top; }
        table.datos th { font-size: 9px; text-transform: uppercase; background: #f0f0f0; font-weight: bold; }
        table.datos td.num { text-align: right; white-space: nowrap; }
        table.datos td.concepto { font-size: 9.5px; }
        table.datos tfoot td { font-weight: bold; border-top: 1px solid #333; }
        table.datos tfoot .label-total { text-align: right; border-right: none; }
        table.datos tfoot .solo-num { border-left: none; }
        h2.ded { font-size: 10.5px; margin: 18px 0 8px 0; font-weight: bold; }
        .pie-liquidar { margin-top: 22px; width: 100%; font-size: 11px; }
        .pie-liquidar td { border: none; padding: 6px 0; vertical-align: middle; }
        .pie-liquidar .lbl { font-weight: normal; }
        .pie-liquidar .val { text-align: right; font-weight: bold; font-size: 12px; }
        .nota { font-size: 8.5px; color: #555; margin-top: 14px; line-height: 1.35; }
    </style>
</head>
<body>
    <table class="cabecera">
        <tr>
            <td class="cabecera-fecha">{{ $fecha_documento ?? '' }}</td>
            <td class="cabecera-emisor">
                @foreach($emisor_lineas ?? [] as $ln)
                    {{ $ln }}<br>
                @endforeach
            </td>
        </tr>
    </table>

    <h1>LIQUIDACION: {{ $codigo_resumen ?? '' }}</h1>

    <p class="intro">
        De acuerdo con sus notas de pedidos, se ha producido el despacho a su consignación con fecha arriba indicada de la mercancía descrita que le confiamos por cuenta de Repsol Butano, S. A. y por nuestra cuenta y de la cual se desprende la siguiente liquidación:
    </p>

    <table class="datos">
        <thead>
            <tr>
                <th class="num" style="width:11%">Cantidad</th>
                <th style="width:49%">Conceptos</th>
                <th class="num" style="width:18%">Precio</th>
                <th class="num" style="width:20%">Importes</th>
            </tr>
        </thead>
        <tbody>
            @foreach($filas as $f)
                <tr>
                    <td class="num">{{ $f['cantidad'] ?? '' }}</td>
                    <td class="concepto">{{ $f['concepto'] ?? '' }}</td>
                    <td class="num">@if(($f['precio_unit'] ?? '') !== ''){{ $f['precio_unit'] }}&nbsp;€@endif</td>
                    <td class="num">@if(($f['importe'] ?? '') !== ''){{ $f['importe'] }}&nbsp;€@endif</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="3" class="label-total"></td>
                <td class="num">{{ $total_importe ?? '0,00' }}&nbsp;€</td>
            </tr>
        </tfoot>
    </table>

    @if(!empty($mostrar_deducciones))
        <h2 class="ded">A DEDUCIR POR COMISIONES:</h2>
        <table class="datos">
            <thead>
                <tr>
                    <th class="num" style="width:11%">Cantidad</th>
                    <th style="width:49%">Conceptos</th>
                    <th class="num" style="width:18%">Precio</th>
                    <th class="num" style="width:20%">Importes</th>
                </tr>
            </thead>
            <tbody>
                @foreach($filas_deducciones ?? [] as $d)
                    <tr>
                        <td class="num">{{ $d['cantidad'] ?? '' }}</td>
                        <td class="concepto">{{ $d['concepto'] ?? '' }}</td>
                        <td class="num">@if(($d['precio'] ?? '') !== ''){{ $d['precio'] }}&nbsp;€@endif</td>
                        <td class="num">@if(($d['importe'] ?? '') !== ''){{ $d['importe'] }}&nbsp;€@endif</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="3" class="label-total"></td>
                    <td class="num">−{{ $total_deducciones ?? '0,00' }}&nbsp;€</td>
                </tr>
            </tfoot>
        </table>
    @endif

    <table class="pie-liquidar">
        <tr>
            <td class="val">Importe a liquidar</td>
            <td class="val">{{ $importe_liquidar ?? ($total_importe ?? '0,00') }}&nbsp;€</td>
        </tr>
    </table>

  
</body>
</html>
