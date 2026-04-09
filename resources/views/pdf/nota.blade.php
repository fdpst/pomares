<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
    <meta charset="utf-8">
    <title>NOTA</title>
</head>

<body>
    <div class="header">

        <!--div class="logo">
             
            <img 
            width="200"
            height="200"
             
            src="{{ URL::asset('storage/users/userId_' . $userLog->id .'/' .$userLog->avatar) }}" alt=" IMAGE ">
        </!--div-->
        <div class="legal-info">
            <h1 style="font-size:50px; font-color:grey;">NOTA</h1>
            <!--h1>{!! $userLog->nombre_fiscal  !!}</!--h1>
            <p>
                {!! $userLog->direccion  !!}<br>
                {!! $userLog->ciudad  !!} {!! $userLog->provincia->nombre  !!} <br>
                {!! $userLog->nombre  !!} CIF/NIF: {!! $userLog->cif  !!} <br>
                tel. {!! $userLog->telefono  !!}
            </p>
            <span>{!! $userLog->email_comercial ?? $userLog->email !!}</span><-->
        </div>
    </div>

    <div class="fecha-emision">
        <p><strong>FECHA DE EMISIÓN:</strong>{{ $recibo->fecha }}</p>
        <p><strong>Nro.Nota:</strong>{{ str_pad($nro_factura, 4, '0', STR_PAD_LEFT) }}</p>
        <!--<p><strong>NOTA</strong></p>-->
    </div>

    <div class="cliente-info">
        <p style="font-size:13px;"><strong>CLIENTE:</strong>{{ $recibo->cliente->nombre }}</p>
        <p style="font-size:13px;"><strong>CIF/NIF:</strong>{{ $recibo->cliente->dni }}</p>
        <p style="font-size:13px;"><strong>DIRECCIÓN:</strong>{{ $recibo->cliente->direccion . ' - ' . $recibo->cliente->codigo_postal . ' ' . $recibo->cliente->localidad . ' - ' . $recibo->cliente->provincia['nombre'] . ' - ' . $recibo->cliente->pais['nombre'] }}</p>
        <p style="font-size:13px;"><strong>TELÉFONO:</strong>{{ $recibo->cliente->telefono }}</p>
    </div>

    <div class="tabla-container">
        <table>
            <thead>
                <tr>
                    <th class="left-align">Descripción</th>
                    <th>cantidad</th>
                    <th>precio</th>
                    <th class="right-align">importe</th>
                </tr>
            </thead>

            <tbody class="border-on-t-body">
                @foreach($recibo->servicios as $servicio)
                @if((float) $servicio['cantidad'] === floatval(0) || (float) $servicio['precio'] === floatval(0) || (float) $servicio['importe'] === floatval(0))
                <tr>
                    <td>{{ $servicio['descripcion'] }}</td>
                    <td class="center-align"></td>
                    <td class="center-align"></td>
                    <td class="right-align"></td>
                </tr>
                @else
                <tr>
                    <td>{{ $servicio['descripcion'] }}</td>
                    <td class="center-align">{{ $servicio['cantidad'] }}</td>
                    <td class="center-align">{{ $servicio['precio'] }}€ </td>
                    <td class="right-align">{{ $servicio['importe'] }}€</td>
                </tr>
                @endif
                @endforeach
            </tbody>

            <tfoot>
                @if($recibo['total_descuento'] != 0)
                <tr>
                    <td style="padding-top:25px !important;" class="right-align" colspan="3"><strong>SUBTOTAL</strong></td>
                    <td style="padding-top:25px !important;" class="right-align">{{ $recibo['sub_total'] }}€</td>
                </tr>
                <tr>
                    <td style="padding-top:10px !important;" class="right-align" colspan="3"><strong>DESCUENTO</strong></td>
                    <td style="padding-top:10px !important;" class="right-align">{{ $recibo['total_descuento'] }}€</td>
                </tr>
                @endif
                <tr>
                    <td style="padding-top:10px !important;" class="right-align" colspan="3"><strong>TOTAL</strong></td>
                    <td style="padding-top:10px !important;" class="right-align">{{ $recibo['total'] }}€</td>
                </tr>
            </tfoot>
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

        .header .logo {
            padding: 10px 0 10px 0;
            width: 45%;

        }

        .header .logo img {
            width: 280px;
        }

        .header .legal-info {
            padding: 10px 0 10px 0;
            text-align: center;
            width: 52%;

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
            margin-top: 20px;
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
            padding: 7px 0;
        }

        .tabla-container table tbody tr td:first-child {
            padding: 10px 0 !important;
        }

        .tabla-container table tfoot tr td {
            padding: 5px 0;
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
