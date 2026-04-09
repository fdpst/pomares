<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
    <meta charset="utf-8">
    <title>Albaran Enviado</title>
</head>

<body>

    <div class="header">
        <div class="logo">
            <img
                width="200"
                height="200"
                src="{{ URL::asset('storage/users/userId_' . $userLog->id .'/' .$userLog->avatar) }}" alt=" IMAGE ">
        </div>
        <div class="legal-info">
            @php
            $title = 'ALBARÁN';
            $nroLabel = 'Nro.Albaran:';

            if (isset($tipo)) {
            switch($tipo) {
            case 'factura':
            $title = 'FACTURA';
            $nroLabel = 'Nro.Factura:';
            break;
            case 'facturarectificativa':
            $title = 'FACTURA RECTIFICATIVA';
            $nroLabel = 'Nro.Factura:';
            break;
            case 'facturaproforma':
            $title = 'FACTURA PROFORMA';
            $nroLabel = 'Nro.Factura:';
            break;
            case 'presupuesto':
            $title = 'PRESUPUESTO';
            $nroLabel = 'Nro.Presupuesto:';
            break;
            case 'nota':
            default:
            $title = 'ALBARÁN';
            $nroLabel = 'Nro.Albaran:';
            break;
            }
            }
            @endphp
            <p style="font-size:50px; font-color:grey;">{{ $title }}</p>
            <h1>{!! $userLog->nombre_fiscal !!}</h1>
            <p>
                {!! $userLog->direccion !!}<br>
                {!! $userLog->ciudad !!} {!! $userLog->provincia->nombre !!} <br>
                {!! $userLog->nombre !!} CIF/NIF: {!! $userLog->cif !!} <br>
                tel. {!! $userLog->telefono !!}
            </p>
            <span>{!! $userLog->email_comercial ?? $userLog->email !!}</span>
        </div>
    </div>

    <div class="fecha-emision">
        <p><strong>FECHA DE EMISIÓN:</strong>{{$fecha_emision}}</p>
        <p><strong>{{ $nroLabel }}</strong>{{ str_pad($nro_factura, 4, '0', STR_PAD_LEFT) }}</p>
        <p><strong>{{ $title }}</strong></p>
    </div>

    <div class="cliente-info">
        <p style="font-size:12px;margin-left: 18px;"><strong>CLIENTE:</strong>{{ $cliente->nombre }}</p>
        <p style="font-size:12px;margin-left: 28px;"><strong>CIF/NIF:</strong>{{ $cliente->dni }}</p>
        <p style="font-size:12px;margin-left: 3px;"><strong>TELÉFONO:</strong>{{ $cliente->telefono }}</p>
        <p style="font-size:12px;"><strong>DIRECCIÓN:</strong>
            @if(isset($cliente->direccion))
            {{
                $cliente->direccion??'' . ' - C.P.: ' . 
                $cliente->codigo_postal??'' . ' - ' . 
                $cliente->localidad??''  . ' - ' . 
                $cliente->provincia['nombre'] . ' - ' . 
                $cliente->pais['nombre'] 
            }}
            @endif
        </p>
    </div>

    <div class="tabla-container">
        @php
        $docType = $tipo ?? 'nota';
        $documentColumns = $documentColumns ?? \App\Helpers\DocumentColumnsHelper::filterByDocType(
        \App\Helpers\DocumentColumnsHelper::defaults(),
        $docType
        );
        $formatValue = function ($servicio, $column) {
        $value = data_get($servicio, $column['field'] ?? '');
        switch ($column['format'] ?? 'text') {
        case 'currency':
        return number_format((float) ($value ?? 0), 2, ',', '.') . '€';
        case 'percent':
        return $value !== null ? $value . '%' : '';
        case 'number':
        return $value ?? '';
        default:
        return $value ?? '';
        }
        };
        $alignClass = function ($align) {
        return match ($align) {
        'center' => 'center-align',
        'end' => 'right-align',
        default => 'left-align',
        };
        };
        @endphp
        <table>
            <thead>
                <tr>
                    @foreach ($documentColumns as $column)
                    <th
                        class="{{ $alignClass($column['align'] ?? 'start') }} {{ !$loop->last ? '' : '' }}"
                        style="{{ isset($column['width']) ? 'width: ' . $column['width'] . '%;' : '' }}">
                        {{ $column['label'] ?? '' }}
                    </th>
                    @endforeach
                </tr>
            </thead>

            <tbody class="border-on-t-body">
                @foreach($data as $servicio)


                <tr>
                    @foreach ($documentColumns as $column)
                    <td class="{{ $alignClass($column['align'] ?? 'start') }}">
                        {{ $formatValue($servicio, $column) }}
                    </td>
                    @endforeach
                </tr>

                @endforeach
            </tbody>

            <tfoot>
                <tr>
                    <td style="padding-top:25px !important;" class="right-align" colspan="3"><strong>TOTAL</strong></td>
                    <td style="padding-top:25px !important;" class="right-align">{{$total }}€</td>
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
