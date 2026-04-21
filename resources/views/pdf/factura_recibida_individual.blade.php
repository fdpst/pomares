<!DOCTYPE html>
<html lang="es" dir="ltr">

<head>
    <meta charset="utf-8">
    <title>Factura recibida</title>
    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 13px;
            color: #222;
            margin: 0;
            padding: 28px 32px 32px;
            line-height: 1.45;
        }

        .cabecera-superior {
            width: 100%;
            margin-bottom: 22px;
            overflow: hidden;
            padding-bottom: 14px;
            border-bottom: 1px solid #bbb;
        }

        .emisor-cabecera {
            float: left;
            width: 58%;
            padding-right: 2%;
        }

        .emisor-cabecera h1 {
            font-size: 20px;
            margin: 0 0 10px 0;
            font-weight: bold;
            color: #111;
        }

        .emisor-datos {
            margin-top: 4px;
            font-size: 12px;
            color: #333;
        }

        .emisor-datos p {
            margin: 4px 0;
        }

        .meta-factura {
            float: right;
            width: 38%;
            text-align: right;
            padding: 6px 0 0 10px;
        }

        .meta-factura .linea {
            margin: 8px 0;
            font-size: 12px;
        }

        .meta-factura .label {
            color: #444;
            font-size: 11px;
        }

        .meta-factura .valor-nro {
            font-size: 17px;
            font-weight: bold;
            margin-top: 3px;
            color: #111;
        }

        .meta-factura .valor-fecha {
            font-size: 15px;
            font-weight: bold;
            color: #111;
        }

        .clear {
            clear: both;
        }

        .titulo-doc {
            text-align: center;
            font-size: 16px;
            font-weight: bold;
            color: #222;
            margin: 20px 0 18px 0;
        }

        .bloque-cliente {
            width: 100%;
            margin-bottom: 22px;
            padding: 0 0 16px 0;
            border-bottom: 1px solid #ccc;
        }

        .bloque-cliente h3 {
            margin: 0 0 12px 0;
            font-size: 13px;
            color: #222;
            font-weight: bold;
        }

        .bloque-cliente p {
            margin: 6px 0;
            font-size: 12px;
            line-height: 1.45;
        }

        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .items-table th {
            background-color: #4a5f6a;
            color: white;
            padding: 10px 9px;
            text-align: left;
            font-weight: bold;
            font-size: 12px;
        }

        .items-table td {
            padding: 9px 9px;
            border-bottom: 1px solid #ddd;
            font-size: 12px;
        }

        .items-table tr:nth-child(even) {
            background-color: #f7f7f7;
        }

        .descripcion-post-tabla {
            margin-top: 14px;
            padding: 10px 12px;
            font-size: 12px;
            line-height: 1.45;
            color: #333;
            border: 1px solid #ddd;
            background: #fafafa;
        }

        .totales {
            width: 100%;
            margin-top: 18px;
        }

        .totales table {
            width: 320px;
            margin-left: auto;
            border-collapse: collapse;
        }

        .totales td {
            padding: 10px 10px;
            font-size: 13px;
        }

        .totales td:first-child {
            text-align: right;
            font-weight: bold;
        }
    </style>
</head>

<body>
    @php
        $p = $factura->proveedor;
        $fechaFmt = $factura->fecha ? \Carbon\Carbon::parse($factura->fecha)->format('d/m/Y') : '—';
        $razonSocialEmisor = $p ? ($p->nombre ?? '—') : '—';
        $razonSocialCliente = trim((string) ($userLog->nombre_fiscal ?? '')) !== ''
            ? $userLog->nombre_fiscal
            : '—';
    @endphp

    <div class="cabecera-superior">
        <div class="emisor-cabecera">
            <h1>{{ $razonSocialEmisor }}</h1>
            @if($p)
                <div class="emisor-datos">
                    <p><strong>NIF / CIF:</strong> {{ $p->cif ?? '—' }}</p>
                    <p>
                        <strong>Domicilio fiscal:</strong><br>
                        {{ trim((string) ($p->direccion ?? '')) !== '' ? trim($p->direccion) : '—' }}
                        @php
                            $emisorResto = trim(implode(' ', array_filter([
                                $p->cp ?? '',
                                $p->localidad ?? '',
                                optional($p->provincia)->nombre,
                            ])));
                        @endphp
                        @if($emisorResto !== '')<br>{{ $emisorResto }}@endif
                    </p>
                </div>
            @endif
        </div>
        <div class="meta-factura">
            <div class="linea">
                <span class="label">Nº factura</span><br>
                <span class="valor-nro">{{ $factura->nro_factura ?? '—' }}</span>
            </div>
            <div class="linea">
                <span class="label">Fecha</span><br>
                <span class="valor-fecha">{{ $fechaFmt }}</span>
            </div>
        </div>
        <div class="clear"></div>
    </div>

    <div class="titulo-doc">Factura recibida</div>

    <div class="bloque-cliente">
        <h3>Datos del cliente</h3>
        <p><strong>Razón social:</strong> {{ $razonSocialCliente }}</p>
        <p><strong>NIF / CIF:</strong> {{ $userLog->cif ?? '—' }}</p>
        <p>
            <strong>Domicilio fiscal:</strong><br>
            {{ trim((string) ($userLog->direccion ?? '')) !== '' ? trim($userLog->direccion) : '—' }}
            @php
                $clienteResto = trim(implode(' ', array_filter([
                    $userLog->postal_code ?? '',
                    $userLog->ciudad ?? '',
                    optional($userLog->provincia)->nombre,
                ])));
            @endphp
            @if($clienteResto !== '')<br>{{ $clienteResto }}@endif
        </p>
    </div>

    @php
        $descFactura = trim((string) ($factura->descripcion ?? ''));
        $hayItems = $items && count($items) > 0;
    @endphp
    @if($hayItems)
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
    @if($descFactura !== '')
    <div class="descripcion-post-tabla">
        <strong>Descripción:</strong> {{ $factura->descripcion }}
    </div>
    @endif

    <div class="totales">
        <table>
            <tr>
                <td>Total factura:</td>
                <td>{{ number_format($factura->total ?? 0, 2, ',', '.') }} €</td>
            </tr>
        </table>
    </div>
</body>

</html>
