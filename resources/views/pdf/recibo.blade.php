<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
    <meta charset="utf-8">
    <title>Recibo</title>
</head>

<body>
    <div class="header">
        @php $logoSrc = $userLog->logo_base64 ?? $userLog->logo ?? ''; @endphp
        @if(!empty($logoSrc))
        <div class="logo">
            <img height="90" width="167" style="width:auto;" src="{{ $logoSrc }}" alt="Logo empresa">
        </div>
        @endif
        <div class="legal-info">
            @if ($tipo == 'factura')
            <p style="font-size:35px; font-color:grey;">FACTURA</p>
            @elseif($tipo == 'facturaproforma')
            <p style="font-size:25px; font-color:grey;">FACTURA PROFORMA</p>
            @elseif($tipo == 'presupuesto')
            <p style="font-size:35px; font-color:grey;">PRESUPUESTO</p>
            @endif
            <h1>{!! $userLog->nombre_fiscal !!}</h1>
            <p style="font-size:12px;">
                {!! $userLog->direccion !!}<br>
                {!! $userLog->ciudad !!} {!! $userLog->provincia->nombre !!} <br>
                {!! $userLog->nombre !!} CIF/NIF: {!! $userLog->cif !!} <br>
                tel. {!! $userLog->telefono !!}
            </p>
            <span>{!! $userLog->email_comercial ?? $userLog->email !!}</span>
        </div>
    </div>

    <div class="fecha-emision">
        <p style="font-size:13px;"><strong>FECHA DE
                EMISIÓN:</strong>{{ (new DateTime($recibo->fecha))->format('d-m-Y') }}</p>
        @if ($tipo == 'presupuesto' && $recibo->fecha_tope != null)
        {{-- <p style="font-size:13px;"><strong>FECHA TOPE:</strong>{{ \Carbon\Carbon::parse($recibo->fecha_tope)->format('d-m-Y')}}</p> --}}
        @elseif($tipo == 'facturaproforma')
        <p style="font-size:13px;">
            <strong>Nro.Factura:</strong>{{ str_pad($nro_factura_prof, 4, '0', STR_PAD_LEFT) }}
        </p>
        @else
        <p style="font-size:13px;">
            <strong>Nro.Presupuesto:</strong>{{ str_pad($nro_factura, 4, '0', STR_PAD_LEFT) }}
        </p>
        @endif
    </div>

    <div class="cliente-info">
        <p style="font-size:12px;margin-left: 18px;"><strong>CLIENTE:</strong>{{ $recibo->cliente->nombre }}</p>
        <p style="font-size:12px;margin-left: 28px;"><strong>CIF/NIF:</strong>{{ $recibo->cliente->dni }}</p>
        <p style="font-size:12px;margin-left: 3px;"><strong>TELÉFONO:</strong>{{ $recibo->cliente->telefono }}</p>
        <p style="font-size:12px;"><strong>DIRECCIÓN:</strong>
            {{ $recibo->cliente->direccion .
                ' - C.P.: ' .
                $recibo->cliente->codigo_postal .
                ' - ' .
                $recibo->cliente->localidad .
                ' - ' .
                $recibo->cliente->provincia['nombre'] .
                ' - ' .
                $recibo->cliente->pais['nombre'] }}
        </p>
    </div>

    <div class="tabla-container">
        <table>
            <thead>
                <tr>
                    <th style="width:280px;text-align:left;font-size:13px" class="left-align">Descripción</th>
                    <th style="width:20px;text-align:right;font-size:13px">Cantidad</th>
                    <th style="width:60px;text-align:right;font-size:13px">Precio</th>
                    <th style="width:60px;text-align:right;font-size:13px" class="right-align">Importe</th>
                </tr>
            </thead>

            <tbody class="{{ $recibo['has_iva'] == true ? 'border-on-t-body' : '' }}">
                @foreach ($recibo->servicios as $servicio)
                @if (
                (float) $servicio['cantidad'] === floatval(0) ||
                (float) $servicio['precio'] === floatval(0) ||
                (float) $servicio['importe'] === floatval(0))
                <tr>
                    <td style="width:280px;text-align:left;font-size:12px">{{ $servicio['descripcion'] }}</td>
                    <td class="right-align"></td>
                    <td class="right-align"></td>
                    <td class="right-align"></td>
                </tr>
                @else
                <tr>
                    <td style="width:280px;text-align:left;font-size:13px">{{ $servicio['descripcion'] }}</td>
                    <td class="right-align" style="font-size:13px">{{ $servicio['cantidad'] }}</td>
                    <td class="right-align" style="font-size:13px">{{ $servicio['precio'] }}€ </td>
                    <td class="right-align" style="font-size:13px">{{ $servicio['importe'] }}€</td>
                </tr>
                @endif
                @endforeach
            </tbody>

            <tfoot>
                @if ($recibo['has_iva'] == false)
                <tr>
                    <td class="{{ $recibo['has_iva'] == false ? 'border-on-t-body' : '' }}" colspan="3"
                        style="padding-top:35px;">
                        <p style="text-transform:uppercase; font-size:11px;">
                            el sujeto pasivo de la operación es el destinatario de las operaciones <br>
                            por la aplicación de las reglas de la inversión del sujeto pasivo contenidas <br>
                            en el articulo 84 apartadouno 2ªde la ley 37/92 de iva
                        </p>
                    </td>
                    <td class="{{ $recibo['has_iva'] == false ? 'border-on-t-body' : '' }}" colspan="1"></td>
                </tr>
                @endif
                @if ($tipo == 'factura' || $tipo == 'facturaproforma')
                <tr>
                    <td style="font-size:13px; padding-top:25px !important;" class="right-align" colspan="3">
                        <strong>SUBTOTAL</strong>
                    </td>
                    <td style="font-size:13px; padding-top:25px !important;" class="right-align">
                        {{ $recibo['sub_total'] }}€
                    </td>
                </tr>
                @if ($recibo['total_descuento'] > 0)
                <tr>
                    <td style="font-size:13px;" class="right-align" colspan="3"><strong>DESCUENTO</strong>
                    </td>
                    <td style="font-size:13px;" class="right-align">
                        {{ $recibo['total_descuento'] > 0 ? $recibo['total_descuento'] : 0 }}€
                    </td>
                </tr>
                @endif
                @if ($recibo['has_iva'] == true)
                <tr>
                    <td style="font-size:13px;" class="right-align" colspan="3"><strong>IVA
                            {{ $recibo['tipo_iva'] }} %</strong></td>
                    <td style="font-size:13px;" class="right-align">
                        {{ $recibo['iva'] }}€
                    </td>
                </tr>
                @endif

                <tr>
                    <td style="font-size:13px;" class="right-align" colspan="3"><strong>TOTAL</strong></td>
                    <td style="font-size:13px;" class="right-align">
                        @if ($recibo['has_iva'] == false)
                        {{ $recibo['sub_total'] }}€
                        @endif

                        @if ($recibo['has_iva'] == true)
                        {{ $recibo['total'] }}€
                        @endif
                    </td>
                </tr>
                @else
                @if ($recibo['total_descuento'] > 0)
                <tr>
                    <td style="font-size:13px;padding-top:25px !important;" class="right-align" colspan="3">
                        <strong>SUBTOTAL</strong>
                    </td>
                    <td style="font-size:13px;padding-top:25px !important;" class="right-align">
                        {{ $recibo['sub_total'] }}€
                    </td>
                </tr>
                <tr>
                    <td style="font-size:13px;" class="right-align" colspan="3"><strong>DESCUENTO</strong>
                    </td>
                    <td style="font-size:13px;" class="right-align">
                        {{ $recibo['total_descuento'] > 0 ? $recibo['total_descuento'] : 0 }}€
                    </td>
                </tr>
                <tr>
                    <td style="font-size:13px;" class="right-align" colspan="3"><strong>TOTAL</strong></td>
                    <td style="font-size:13px;" class="right-align">
                        {{ $recibo['sub_total'] - $recibo['total_descuento'] }}€
                    </td>
                </tr>
                @else
                <tr>
                    <td style="font-size:13px;" class="right-align" colspan="3"><strong>TOTAL</strong></td>
                    <td style="font-size:13px;" class="right-align"> {{ $recibo['sub_total'] }}€</td>
                </tr>
                @endif
                <tr>
                    <td style="font-size:13px;padding-top:25px !important;" class="right-align" colspan="3"></td>
                    <td style="font-size:13px;padding-top:25px !important;" class="right-align">*IVA no incluido
                    </td>
                </tr>
                @endif
                <!--<tr>
                    <td class="right-align" colspan="3"><strong>FORMA DE PAGO:</strong></td>
                    <td class="right-align">
                        {!! $userLog->cuenta !!}
                    </td>
                </tr>-->
                <tr>
                    <td></td>
                    <th class="right-align"></th>
                </tr>

            </tfoot>
        </table>
        <table>
            <tr>
                <th></th>
                <th style="width:40%">
                    <div style=" border-bottom: 1px solid #546e7a">
                        <span style="padding-bottom:6rem; font-size:13px">CONFORMIDAD</span><br><br><br><br>
                    </div>
                </th>
            </tr>
        </table>
    </div>
    @if ($tipo == 'factura' || $tipo == 'facturaproforma')
    <div class="">
        <table style="border: solid 2px;width: 100%;margin-top:30px;">
            <thead>
                <tr>
                    <th style="font-size:13px;" class="left-align">OBSERVACIONES:</th>
                </tr>
            </thead>
            <tbody class="">
                <tr>
                    <td style="font-size:13px;" style="padding:15px;">{{ $recibo['observaciones'] }}</td>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="">
        <table style="border: solid 2px;width: 100%;margin-top:35px;">
            <thead>
                <tr>
                    <th style="font-size:13px;" class="left-align">FORMA DE PAGO:</th>
                </tr>
            </thead>

            <tbody class="">
                <tr style="font-size:13px;">
                    @if ($tipo == 'factura' || $tipo == 'facturaproforma')
                    <td style="font-size:13px;padding:15px;" class="center-align">{{ $metodo['nombre'] }}:
                        {{ $metodo['detalle'] }}
                    </td>
                    @endif
                </tr>
            </tbody>
        </table>

    </div>
    @endif

    <div class="">
        <table style="border: solid 1px;width: 100%;margin-top:30px">
            <thead>
                <tr>
                    <th class="left-align"></th>
                </tr>
            </thead>

            <tbody class="">
                <tr>
                    <td class="center-align" style="font-size:10px;">De conformidad con el Reglamento Europeo de
                        Protección de Datos (UE) 679/2016, le comunicamos que los datos objeto de este
                        tratamiento en la presente factura son responsabilidad de {!! $userLog->nombre_fiscal !!}. Le informamos
                        que los datos que nos facilita se precisan
                        para prestarle el servicio solicitado y realizar la facturación de este. Los datos se
                        conservarán mientras se mantenga la relación comercial o
                        durante los años necesarios para cumplir con las obligaciones legales. Los datos no se cederán a
                        terceros salvo en los casos en que exista una
                        obligación legal. Usted puede ejercer el derecho de acceso, rectificación, cancelación,
                        supresión y portabilidad, dirigiéndose por escrito a {!! $userLog->direccion !!}
                        {!! $userLog->ciudad !!} {!! $userLog->provincia->nombre !!}.
                    </td>
                </tr>
            </tbody>
        </table>

    </div>

    <style media="screen">
        html,
        body {
            color: #546e7a;
        }

        .header div {
            display: inline-block;
            vertical-align: top;
        }

        .header .legal-info {
            padding: 10px 0 10px 0;
            text-align: center;
            width: 100%;

        }

        .header .legal-info h1 {
            font-size: 18px;
            color: #448aff;
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
