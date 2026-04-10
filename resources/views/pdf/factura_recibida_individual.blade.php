<!DOCTYPE html>
<html lang="es" dir="ltr">

<head>
    <meta charset="utf-8">
    <title>{{ $tituloPdf ?? 'FACTURA' }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            color: #333;
            margin: 0;
            padding: 20px;
        }

        .header {
            width: 100%;
            margin-bottom: 24px;
        }

        .info-emisor {
            float: left;
            width: 55%;
        }

        .info-emisor h1 {
            font-size: 16px;
            margin: 0 0 8px 0;
            text-transform: uppercase;
            color: #333;
        }

        .info-emisor p {
            margin: 4px 0;
            font-size: 11px;
        }

        .bloque-factura {
            float: right;
            width: 40%;
            text-align: right;
        }

        .bloque-factura .titulo-doc {
            font-size: 22px;
            font-weight: bold;
            color: #546e7a;
            margin: 0 0 12px 0;
            letter-spacing: 1px;
        }

        .bloque-factura table {
            width: 100%;
            border-collapse: collapse;
            font-size: 11px;
        }

        .bloque-factura td {
            padding: 6px 0;
            border-bottom: 1px solid #e0e0e0;
        }

        .bloque-factura td:first-child {
            font-weight: bold;
            text-align: right;
            padding-right: 12px;
            width: 45%;
        }

        .bloque-factura td:last-child {
            text-align: right;
        }

        .clear {
            clear: both;
        }

        .cliente-info {
            background-color: #f5f5f5;
            padding: 15px;
            margin-bottom: 20px;
        }

        .cliente-info h3 {
            margin: 0 0 10px 0;
            font-size: 13px;
            color: #546e7a;
            text-transform: uppercase;
        }

        .cliente-info p {
            margin: 5px 0;
            font-size: 11px;
        }

        .factura-info {
            width: 100%;
            margin-bottom: 20px;
        }

        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .items-table th {
            background-color: #546e7a;
            color: white;
            padding: 10px;
            text-align: left;
            font-weight: bold;
        }

        .items-table td {
            padding: 8px;
            border-bottom: 1px solid #ddd;
        }

        .items-table tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .totales {
            width: 100%;
            margin-top: 20px;
        }

        .totales table {
            width: 300px;
            margin-left: auto;
            border-collapse: collapse;
        }

        .totales td {
            padding: 8px;
        }

        .totales td:first-child {
            text-align: right;
            font-weight: bold;
        }

        .totales .total-row {
            font-weight: bold;
            font-size: 14px;
            border-top: 2px solid #546e7a;
            background-color: #f5f5f5;
        }

        .descripcion {
            margin: 20px 0;
            padding: 10px;
            background-color: #f9f9f9;
            border-left: 3px solid #546e7a;
        }
    </style>
</head>

<body>
    @php
        $emisor = $factura->proveedor;
    @endphp

    <div class="header">
        <div class="info-emisor">
            <h1>{{ optional($emisor)->nombre ?? 'Proveedor' }}</h1>
            <p><strong>CIF/NIF:</strong> {{ optional($emisor)->cif ?? '—' }}</p>
            @if(optional($emisor)->direccion)
            <p>{{ $emisor->direccion }}</p>
            @endif
            @php
                $locEmisor = $emisor
                    ? trim(implode(' ', array_filter([
                        $emisor->cp ?? null,
                        $emisor->localidad ?? null,
                        optional($emisor->provincia)->nombre ?? null,
                    ])))
                    : '';
            @endphp
            @if($locEmisor !== '')
            <p>{{ $locEmisor }}</p>
            @endif
        </div>
        <div class="bloque-factura">
            <div class="titulo-doc">{{ $tituloPdf ?? 'FACTURA' }}</div>
            <table>
                <tr>
                    <td>Número</td>
                    <td>{{ $factura->nro_factura ?? '—' }}</td>
                </tr>
                <tr>
                    <td>Fecha</td>
                    <td>{{ \Carbon\Carbon::parse($factura->fecha)->format('d/m/Y') }}</td>
                </tr>
            </table>
        </div>
        <div class="clear"></div>
    </div>

    <div class="cliente-info">
        <h3>Cliente</h3>
        <p><strong>{{ $userLog->nombre_fiscal ?? 'Empresa' }}</strong></p>
        @if(filled($userLog->direccion ?? null))
        <p><strong>Dirección:</strong> {{ $userLog->direccion }}</p>
        @endif
        @php
            $locCliente = trim(implode(' ', array_filter([
                $userLog->postal_code ?? null,
                $userLog->ciudad ?? null,
                optional($userLog->provincia)->nombre ?? null,
            ])));
        @endphp
        @if($locCliente !== '')
        <p>{{ $locCliente }}</p>
        @endif
        <p><strong>CIF/NIF:</strong> {{ $userLog->cif ?? '—' }}</p>
    </div>

    @if($items && count($items) > 0)
    <table class="items-table">
        <thead>
            <tr>
                <th>Concepto</th>
                <th>Cantidad</th>
                <th>Precio</th>
                <th>IVA %</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($items as $item)
            <tr>
                <td>{{ $item->concepto ?? '-' }}</td>
                <td>{{ $item->cantidad ?? 0 }}</td>
                <td>{{ number_format($item->precio ?? 0, 2, ',', '.') }} €</td>
                <td>{{ number_format($item->iva ?? 0, 0) }}%</td>
                <td>{{ number_format($item->total ?? 0, 2, ',', '.') }} €</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif

    <div class="totales">
        <table>
            <tr class="total-row">
                <td>Total factura</td>
                <td>{{ number_format($factura->total ?? 0, 2, ',', '.') }} €</td>
            </tr>
        </table>
    </div>

    @if($factura->descripcion)
    <div class="descripcion">
        <strong>Observaciones:</strong> {{ $factura->descripcion }}
    </div>
    @endif
</body>

</html>
