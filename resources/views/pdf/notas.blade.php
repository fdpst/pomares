<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
    <meta charset="utf-8">
    <title>NOTAS</title>
</head>

<body>
    @foreach($data as $dato)
    @php
    $recibo = $dato['recibo'];
    $fecha = $dato['fecha'];
    $userLog = $dato['userLog'];
    $nro_nota = $dato['nro_nota'];
    $tipo = $dato['tipo'];
    @endphp
    <div class="header">
        <table class="full-width">
            <tr>
                <td style="width:60%">
                    <div class="logo">
                        {{-- @if($userLog->avatar)
                            <img src="{{ public_path() . '/storage/users/userId_' . $userLog->id .'/' . $userLog->avatar }}" alt="">
                        @else
                        <img height="90" src="{{public_path() . '/default.png'}}" alt="">
                        @endif --}}

                        <img height="110" style="padding:1rem" src="{{public_path() . '/logo.jpg'}}" alt="">
                        <h4>
                            NOTA
                        </h4>
                    </div>
                </td>
                <td style="width:40%">
                    <div class="legal-info">
                        <h1>{!! $userLog->nombre_fiscal !!}</h1>
                        <p style="font-size:12px;">
                            {!! $userLog->direccion !!}<br>
                            {!! $userLog->ciudad !!} {!! '('.$userLog->provincia->nombre.')' !!} <br>
                            {!! $userLog->nombre !!} CIF/NIF: {!! $userLog->cif !!} <br>
                            tel. {!! $userLog->telefono !!}<br>
                            Email: {!! $userLog->email_comercial ?? $userLog->email !!} <br>
                        </p>
                        <div class="border full-width" style="margin: 0.75rem 0 0.75rem 0.75rem">
                            <p style="font-size:12px;text-align:justify;padding-left: 1rem;padding-bottom:0.5rem">
                                <span style="font-weight:bold">{{$recibo?->cliente?->nombre}}</span><br>
                                <span>{{$recibo?->cliente?->direccion}}</span><br>
                                <span>{{$recibo?->cliente?->codigo_postal . ' ' .   $recibo->cliente?->localidad}}</span><br>
                                <span>{{$recibo?->cliente?->provincia?->nombre}}</span><br>
                                <span>{{$recibo?->cliente?->dni }}</span>
                            </p>
                        </div>
                    </div>
                </td>
            </tr>
        </table>
    </div>

    <div class="full-width " style="font-size:13px;">
        <table class="border full-width">
            <tr>
                <th style="width:20%">
                    Número de nota
                </th>
                <th style="width:15%">
                    Fecha
                </th>
                <th>
                    Descripción
                </th>
            </tr>
            <tr>
                <td>
                    {{-- {{ substr($recibo->nro_nota->Anio->year,2). str_pad($nro_nota, 4, '0', STR_PAD_LEFT) }} --}}
                    {{ $nro_nota ? str_pad($nro_nota, 4, '0', STR_PAD_LEFT) : null }}
                </td>
                <td>
                    {{ $fecha }}
                </td>
                <td>
                    {{ $recibo['observaciones']}}
                </td>
            </tr>
        </table>
    </div>

    <div class="full-width" style="padding-top:1rem; height:50% !important">
        <div style="height: 80%;  border-left: 1px solid #448aff;  border-right: 1px solid #448aff; ">
            <table class="border full-width" style="padding:0rem;border-left:none;border-right:none">
                <thead>
                    <tr>
                        <th style="width:20px;text-align:left;font-size:13px ;border-left:none !important">Nro</th>
                        <th style="width:20px;text-align:left;font-size:13px ;border-left:none !important">Cantidad</th>
                        <th style="width:280px;text-align:left;font-size:13px" class="left-align">Descripción</th>
                        <th style="width:60px;text-align:left;font-size:13px ">Precio</th>
                        <th style="width:60px;text-align:left;font-size:13px ;border-right:none" class="left-align">Subtotal</th>
                    </tr>
                </thead>

                <tbody style="height: 80%;">
                    @foreach($recibo->servicios as $servicio)
                    @if($servicio['importe'] != null)
                    <tr>
                        <td style="font-size:13px ;border-left:none !important">{{ $servicio?->Servicio?->nro??'' }}</td>
                        <td style="font-size:13px ;border-left:none !important">{{ $servicio['cantidad'] }}</td>
                        <td style="width:280px;text-align:left;font-size:13px">{{ $servicio['descripcion'] }}</td>
                        <td style="font-size:13px">{{ $servicio['precio'] }}€ </td>
                        <td style="font-size:13px;border-right:none">{{ $servicio['importe'] }}€</td>
                    </tr>
                    @else
                    <tr>
                        <td style="font-size:13px ;border-left:none !important">{{ $servicio?->Servicio?->nro??'' }}</td>

                        <td style="font-size:13px ;border-left:none !important">{{ $servicio['cantidad'] }}</td>
                        <td style="width:280px;text-align:left;font-size:13px">{{ $servicio['descripcion'] }}</td>
                        <td style="font-size:13px">{{ $servicio['precio'] }}</td>
                        <td style="font-size:13px;border-right:none">{{ $servicio['importe'] }}</td>
                    </tr>
                    @endif
                    @endforeach
                </tbody>
            </table>
        </div>
        <table class="border full-width">
            <tfoot>
                {{-- @if($recibo['has_iva'] == false)
                        <tr>
                            <td class="{{ ($recibo['has_iva'] == false) ? 'border-on-t-body' : ''}}" colspan="3" style="padding-top:35px;">
                <p style="text-transform:uppercase; font-size:11px;">
                    el sujeto pasivo de la operación es el destinatario de las operaciones <br>
                    por la aplicación de las reglas de la inversión del sujeto pasivo contenidas <br>
                    en el articulo 84 apartadouno 2ªde la ley 37/92 de iva
                </p>
                </td>
                <td class="{{ ($recibo['has_iva'] == false) ? 'border-on-t-body' : ''}}" colspan="1"></td>
                </tr>
                @endif
                @if($tipo == 'factura' || $tipo == 'facturaproforma')
                <tr>
                    <td style="font-size:13px; border-right:none" class="right-align" colspan="3"><strong>SUBTOTAL</strong></td>
                    <td style="font-size:13px; border-left: none" class="right-align">{{ $recibo['sub_total'] }}€</td>
                </tr>
                @if($recibo['total_descuento'] > 0)
                <tr>
                    <td style="font-size:13px;border-right:none" class="right-align" colspan="3"><strong>DESCUENTO</strong></td>
                    <td style="font-size:13px;border-left: none" class="right-align">{{ $recibo['total_descuento'] > 0 ? $recibo['total_descuento'] : 0 }}€</td>
                </tr>
                @endif
                @if($recibo['has_iva'] == true)
                <tr>
                    <td style="font-size:13px;border-right:none" class="right-align" colspan="3"><strong>IVA {{ $recibo['tipo_iva'] }} %</strong></td>
                    <td style="font-size:13px;border-left: none" class="right-align">
                        {{ $recibo['iva'] }}€
                    </td>
                </tr>
                @endif

                <tr>
                    <td style="font-size:13px;border-right:none" class="right-align" colspan="3"><strong>TOTAL</strong></td>
                    <td style="font-size:13px;border-left: none" class="right-align">
                        @if($recibo['has_iva'] == false)
                        {{ $recibo['sub_total'] }}€
                        @endif

                        @if($recibo['has_iva'] == true)
                        {{ $recibo['total'] }}€
                        @endif
                    </td>
                </tr>
                @else --}}
                @if($recibo['total_descuento'] > 0)
                <tr>
                    <td style="font-size:13px; !important;border-right:none" class="right-align" colspan="3"><strong>SUBTOTAL</strong></td>
                    <td style="font-size:13px; !important;border-left: none" class="right-align">{{ $recibo['sub_total'] }}€</td>
                </tr>
                <tr>
                    <td style="font-size:13px;border-right:none" class="right-align" colspan="3"><strong>DESCUENTO</strong></td>
                    <td style="font-size:13px;border-left: none" class="right-align">{{ $recibo['total_descuento'] }}€</td>
                </tr>
                {{-- <tr>
                            <td style="font-size:13px;border-right:none" class="right-align" colspan="3"><strong>TOTAL</strong></td>
                            <td style="font-size:13px;border-left: none" class="right-align"> {{ $recibo['sub_total'] - $recibo['total_descuento'] }}€</td>
                </tr> --}}
                @endif
                <tr>
                    <td style="font-size:13px;border-right:none" class="right-align" colspan="3"><strong>TOTAL</strong></td>
                    <td style="font-size:13px;border-left: none" class="right-align"> {{ $recibo['total'] }}€</td>
                </tr>

                {{-- <tr>
                            <td style="font-size:13px;padding-top:25px !important;border-right:none" class="right-align" colspan="3"></td>
                            <td style="font-size:13px;padding-top:25px !important;border-left: none" class="right-align">*IVA no incluido</td>
                        </tr>
                    @endif --}}
                <!--<tr>
                        <td class="right-align" colspan="3"><strong>FORMA DE PAGO:</strong></td>
                        <td class="right-align">
                            {!! $userLog->cuenta  !!}
                        </td>
                    </tr>-->
            </tfoot>
        </table>
    </div>

    <!--div class="full-width" style="text-align: center">
            <h2>Visitanos en www.easypoolspain.com</h2>
            <p style="font-size:13px"><span>También puede encontrarnos en Instagram y Facebook </span><br>
                <span>Nº de cuenta; La Caixa ES52 2100 4624 8822 0007 9925</span>

            </p>
        </div-->
    @endforeach

    <style media="screen">
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
