<!DOCTYPE html>
<html lang="es" dir="ltr">

<head>
    <meta charset="utf-8">
    <title>Factura Recibida</title>
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
            margin-bottom: 30px;
        }

        .logo {
            float: left;
            width: 30%;
        }

        .logo img {
            height: 80px;
        }

        .info-empresa {
            float: right;
            width: 65%;
            text-align: right;
        }

        .info-empresa h1 {
            font-size: 18px;
            margin: 0 0 10px 0;
            text-transform: uppercase;
        }

        .info-empresa p {
            margin: 5px 0;
            font-size: 11px;
        }

        .clear {
            clear: both;
        }

        .titulo {
            text-align: center;
            font-size: 24px;
            font-weight: bold;
            margin: 30px 0;
            color: #546e7a;
        }

        .factura-info {
            width: 100%;
            margin-bottom: 30px;
        }

        .factura-info table {
            width: 100%;
            border-collapse: collapse;
        }

        .factura-info td {
            padding: 8px;
            border-bottom: 1px solid #ddd;
        }

        .factura-info td:first-child {
            font-weight: bold;
            width: 150px;
        }

        .proveedor-info {
            background-color: #f5f5f5;
            padding: 15px;
            margin-bottom: 20px;
        }

        .proveedor-info h3 {
            margin: 0 0 10px 0;
            font-size: 14px;
            color: #546e7a;
        }

        .proveedor-info p {
            margin: 5px 0;
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
    <div class="header">
        <div class="logo">
            <img src="{{ public_path() . '/logo.jpg' }}" alt="Logo">
        </div>
        <div class="info-empresa">
            <h1>{{ $userLog->nombre_fiscal ?? 'Empresa' }}</h1>
            <p>{{ $userLog->direccion ?? '' }}</p>
            <p>{{ $userLog->ciudad ?? '' }} {{ $userLog->provincia->nombre ?? '' }}</p>
            <p>CIF/NIF: {{ $userLog->cif ?? '' }}</p>
            <p>Tel: {{ $userLog->telefono ?? '' }} | Email: {{ $userLog->email_comercial ?? $userLog->email ?? '' }}</p>
        </div>
        <div class="clear"></div>
    </div>

    <div class="titulo">FACTURA RECIBIDA</div>

    <div class="factura-info">
        <table>
            <tr>
                <td>Número de Factura:</td>
                <td>{{ $factura->nro_factura ?? '-' }}</td>
            </tr>
            <tr>
                <td>Fecha:</td>
                <td>{{ \Carbon\Carbon::parse($factura->fecha)->format('d/m/Y') }}</td>
            </tr>
            <tr>
                <td>Total:</td>
                <td>{{ number_format($factura->total ?? 0, 2, ',', '.') }} €</td>
            </tr>
        </table>
    </div>

    <div class="proveedor-info">
        <h3>DATOS DEL PROVEEDOR</h3>
        <p><strong>Nombre:</strong> {{ $factura->proveedor->nombre ?? '-' }}</p>
        <p><strong>CIF:</strong> {{ $factura->proveedor->cif ?? '-' }}</p>
        @if($factura->proveedor->direccion ?? null)
        <p><strong>Dirección:</strong> {{ $factura->proveedor->direccion }}</p>
        @endif
    </div>

    @if($factura->descripcion)
    <div class="descripcion">
        <strong>Descripción:</strong> {{ $factura->descripcion }}
    </div>
    @endif

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
            <tr>
                <td>Total:</td>
                <td>{{ number_format($factura->total ?? 0, 2, ',', '.') }} €</td>
            </tr>
        </table>
    </div>
</body>

</html>
