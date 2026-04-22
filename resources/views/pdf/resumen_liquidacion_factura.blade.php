<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Resumen de liquidación</title>
    <style>
        body { font-family: DejaVu Sans, Arial, sans-serif; font-size: 11px; color: #222; margin: 24px; }
        h1 { font-size: 18px; margin: 0 0 8px 0; }
        .meta { margin-bottom: 18px; color: #444; font-size: 11px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 8px; }
        th, td { border: 1px solid #bbb; padding: 6px 8px; text-align: left; vertical-align: top; }
        th { background: #f5f5f5; font-size: 10px; text-transform: uppercase; }
        td.num { text-align: right; white-space: nowrap; }
        .concepto { font-size: 10px; }
        tfoot td { font-weight: bold; background: #f9f9f9; }
        tfoot .label-total { text-align: right; }
    </style>
</head>
<body>
    <h1>LIQUIDACION: {{ $codigo_resumen ?? '' }}</h1>
    <div class="meta">
        Autofactura:
        @if($factura->nro_factura)
            {{ $factura->nro_factura }}
        @else
            #{{ $factura->id }}
        @endif
        @if($factura->fecha)
            — Fecha {{ \Carbon\Carbon::parse($factura->fecha)->format('d/m/Y') }}
        @endif
        @if($factura->proveedor)
            — Punto de venta: {{ $factura->proveedor->nombre }}
        @endif
    </div>

    <table>
        <thead>
            <tr>
                <th class="num" style="width:12%">Cantidad</th>
                <th style="width:46%">Concepto</th>
                <th class="num" style="width:20%">Precio unitario aplicado</th>
                <th class="num" style="width:20%">Importe</th>
            </tr>
        </thead>
        <tbody>
            @foreach($filas as $f)
                <tr>
                    <td class="num">{{ $f['cantidad'] ?? '' }}</td>
                    <td class="concepto">{{ $f['concepto'] ?? '' }}</td>
                    <td class="num">{{ $f['precio_unit'] ?? '' }} €</td>
                    <td class="num">{{ $f['importe'] ?? '' }} €</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="3" class="label-total">Total</td>
                <td class="num">{{ $total_importe ?? '0,00' }} €</td>
            </tr>
        </tfoot>
    </table>
    <p style="font-size:9px;color:#666;margin-top:16px;">
        Precio unitario con el descuento % de la línea. Si coincide con el catálogo actual, el concepto añade «(Desde el …)» con la fecha de la liquidación; si no, «(Hasta el …)» según historial de precios o esa misma fecha.
    </p>
</body>
</html>
