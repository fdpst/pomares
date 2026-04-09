<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
    <meta charset="utf-8">
    <title>Recibo</title>
</head>

<body>
    @php $logoSrc = $userLog->logo_base64 ?? $userLog->logo ?? ''; @endphp
    <table>
        <tr>
            <td>
                @if(!empty($logoSrc))
                <img height="110" style="padding:1rem" src="{{ $logoSrc }}" alt="Logo empresa">
                @endif
            </td>
            <td>
                Listado de Facturas. Ejercicio EJERCICIO {{ date('Y') }}.
            </td>
        </tr>
    </table>

    <table class="border full-width">
        <tr>
            <th>Fecha</th>
            <th>Nº Factura</th>
            <th>Cliente</th>
            <th>Población</th>
            <th>Artículos</th>
            <th>Importe</th>
            <th>Cobrada</th>
        </tr>
        @foreach ($data as $dato)
            @php
                $recibo = $dato['recibo'];
                $nro_factura = $dato['nro_factura'];
            @endphp
            <tr>
                <td>
                    {{ \Carbon\Carbon::parse($recibo->fecha)->format('d-m-Y') }}
                </td>
                <td>
                    {{ substr($recibo->nro_factura->Anio->year, 2) . str_pad($nro_factura, 4, '0', STR_PAD_LEFT) }}
                </td>
                <td>
                    {{ $recibo->cliente?->nombre }}
                </td>
                <td>
                    @if (isset($recibo->cliente->localidad))
                        {{ $recibo->cliente->localidad }}
                    @endif
                </td>
                <td>
                    {{ count($recibo['servicios']) }}
                </td>
                <td>
                    {{ $recibo['total'] }}
                </td>
                <td>
                    {{ $recibo['pagado'] }}
                </td>
            </tr>
        @endforeach
    </table>

    <style media="screen">
        * {
            font-size: 12px;
        }

        .full-width {
            width: 100%;
        }

        .border table,
        .border th,
        .border td {
            border: 1px solid #448aff;
            border-collapse: collapse;
            padding: 0.25rem
        }

        .border {
            border: solid 1px #448aff;
            border-collapse: collapse;
        }

        html,
        body {
            color: #546e7a;
        }

        .header div {
            display: inline-block;
            vertical-align: top;
        }

        .header .logo {
            padding: 20px 0 10px 0;
            width: 100%;

        }

        .header .logo img {
            width: auto;
            height: 150px;
        }

        .header .legal-info {
            padding: 0 0 10px 0;
            text-align: center;
            width: 100%;

        }

        .header .legal-info h1 {
            font-size: 18px;

            text-transform: uppercase;
            margin-top: 0;
        }

        .header .legal-info p {
            margin-bottom: 5px;
            text-transform: capitalize;
        }

        .fecha-emision p strong {
            margin-right: 10px;
        }

        .fecha-emision p {
            margin-bottom: 8px;
            margin-top: 0;
        }

        .fecha-emision {
            border-bottom: solid 3px #448aff;
        }

        .cliente-info p {
            text-transform: uppercase;
            margin-bottom: 6px;
            margin-top: 0px;
        }

        .cliente-info p:first-child {
            margin-top: 6px;
        }

        .cliente-info p strong {
            margin-right: 15px;
            margin-bottom: 3px;
        }

        .tabla-container table {
            width: 100%;
            margin-top: 15px;
            border-collapse: collapse;
            border-spacing: 0;
        }

        .tabla-container table thead tr th {
            border-bottom: solid 1px #90a4ae;
            text-transform: uppercase;
            padding-bottom: 3px;
            text-align: center;
        }

        .border-on-t-body {
            border-bottom: solid 1px #90a4ae !important;
        }

        .border-on-f-body {
            border-bottom: solid 1px #90a4ae !important;
        }

        .tabla-container table tbody tr td {
            padding: 3px 0;
        }

        .tabla-container table tbody tr td:first-child {
            padding: 3px 0 !important;
        }

        .tabla-container table tfoot tr td {
            padding: 3px 0;
        }

        .center-align {
            text-align: center;
        }

        .right-align {
            text-align: right !important;
        }

        .left-align {
            text-align: left !important;
        }
    </style>
</body>

</html>
