<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">

    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            color: #111;
        }

        .wrapper {
            padding: 30px;
        }

        /* ================= HEADER ================= */

        .header {
            width: 100%;
            display: table;
            margin-bottom: 10px;
        }

        .header-left,
        .header-right {
            display: table-cell;
            vertical-align: top;
        }

        .header-left {
            width: 40%;
        }

        .header-left img {
            max-width: 180px;
            max-height: 180px;
            display: block;
        }

        .header-right {
            text-align: right;
            width: 60%;
        }

        .title {
            font-size: 28px;
            letter-spacing: 1px;
            font-weight: 300;
            margin-bottom: 8px;
        }

        .company-name {
            font-weight: bold;
            font-size: 18px;
            color: #1e40af;
        }

        .separator {
            height: 3px;
            background: #1e40af;
            margin: 18px 0;
        }

        /* ================= INFO ================= */

        .info-box {
            width: 100%;
            border: 1px solid #d2d6dc;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .info-box td {
            border: 1px solid #d2d6dc;
            padding: 6px 10px;
            font-size: 12px;
            line-height: 1.25;
            vertical-align: top;
        }

        .info-header {
            font-size: 10px;
            color: #6b7280;
            font-weight: 600;
            margin-bottom: 2px;
        }

        .info-value {
            font-size: 12px;
            margin-bottom: 4px;
        }

        /* ================= ITEMS ================= */

        .items-table {
            width: 100%;
            border: 1px solid #d2d6dc;
            border-collapse: collapse;
            margin-top: 15px;
            table-layout: fixed;
        }

        .items-table th {
            background: #f4f6f8;
            padding: 8px;
            border: none;
            font-size: 12px;
            color: #374151;
            text-transform: uppercase;
        }

        .items-table td {
            padding: 7px;
            border: none;
            font-size: 12px;
        }

        .right {
            text-align: right;
        }

        .text-left {
            text-align: left;
        }

        .text-center {
            text-align: center;
        }

        /* ================= TOTALS ================= */

        .totals-table {
            width: 100%;
            border-collapse: collapse;
            border: 1px solid #d2d6dc;
            margin-top: 20px;
        }

        .totals-table td {
            border: none;
            padding: 8px 10px;
            font-size: 13px;
        }

        .totals-table .value {
            text-align: right;
        }

        /* ================= PAYMENTS ================= */

        .payments-table {
            width: 100%;
            border-collapse: collapse;
            border: 1px solid #d2d6dc;
            margin-top: 18px;
        }

        .payments-table th {
            background: #f4f6f8;
            padding: 8px;
            font-size: 12px;
            color: #374151;
            text-align: left;
            text-transform: uppercase;
        }

        .payments-table td {
            padding: 7px 8px;
            font-size: 12px;
            border-top: 1px solid #e5e7eb;
        }
    </style>

</head>

<body>

    @php
    $invoice_footer = $invoice_footer ?? ($userLog->invoiceFooter()?->value ?? '');
    @endphp
    @include('pdf.footer', ['invoice_footer' => $invoice_footer])

    <div class="wrapper">

        <!-- HEADER -->
        <div class="header">
            <div class="header-left">
                @php $logoSrc = $logo_base64 ?? $userLog->logo_base64 ?? ''; @endphp
                @if(!empty($logoSrc))
                <img src="{{ $logoSrc }}" alt="Logo">
                @endif
            </div>

            <div class="header-right">
                @php
                $title = 'ALBARÁN';
                $nroLabel = 'Nº ALBARÁN:';

                if (isset($tipo)) {
                switch($tipo) {
                case 'factura':
                $title = 'FACTURA';
                $nroLabel = 'Nº FACTURA:';
                break;
                case 'facturarectificativa':
                $title = 'FACTURA RECTIFICATIVA';
                $nroLabel = 'Nº FACTURA:';
                break;
                case 'facturaproforma':
                $title = 'FACTURA PROFORMA';
                $nroLabel = 'Nº FACTURA:';
                break;
                case 'presupuesto':
                $title = 'PRESUPUESTO';
                $nroLabel = 'Nº PRESUPUESTO:';
                break;
                case 'nota':
                default:
                $title = 'ALBARÁN';
                $nroLabel = 'Nº ALBARÁN:';
                break;
                }
                }
                @endphp
                <div class="title">{{ $title }}</div>

                <div class="company-name">{{ $userLog->nombre_fiscal ?? $userLog->nombre }}</div>

                {{ $userLog->direccion ?? '' }}<br>
                {{ $userLog->ciudad ?? '' }} {{ $userLog->provincia->nombre ?? '' }}<br>
                CIF/NIF: {{ $userLog->cif ?? '' }}<br>
                @if(!empty($userLog->telefono)) Tel: {{ $userLog->telefono }}<br>@endif
                @if(!empty($userLog->email_comercial ?? $userLog->email)) {{ $userLog->email_comercial ?? $userLog->email }} @endif
            </div>
        </div>

        <!-- SEPARATOR -->
        <div class="separator"></div>

        <!-- INFO -->
        <table class="info-box">
            <tr>
                <td style="width: 28%;">
                    <div class="info-header">FECHA DE EMISIÓN:</div>
                    <div class="info-value">{{ $fecha_emision }}</div>

                    <div class="info-header">{{ $nroLabel }}</div>
                    <div class="info-value">{{ str_pad($nro_factura, 4, '0', STR_PAD_LEFT) }}</div>
                </td>

                <td style="width: 72%;">
                    <div class="info-header">CLIENTE:</div>
                    <div class="info-value">{{ $cliente->nombre ?? '' }}</div>

                    <div class="info-header">CIF/NIF:</div>
                    <div class="info-value">{{ $cliente->dni ?? '' }}</div>

                    <div class="info-header">TELÉFONO:</div>
                    <div class="info-value">{{ $cliente->telefono ?? '' }}</div>

                    <div class="info-header">DIRECCIÓN:</div>
                    <div class="info-value">
                        {{ $cliente->direccion ?? '' }}
                        @if(!empty($cliente->codigo_postal)) - C.P.: {{ $cliente->codigo_postal }} @endif
                        @if(!empty($cliente->localidad)) - {{ $cliente->localidad }} @endif
                        @if(!empty($cliente->provincia?->nombre)) - {{ $cliente->provincia->nombre }} @endif
                        @if(!empty($cliente->pais?->nombre)) - {{ $cliente->pais->nombre }} @endif
                    </div>
                </td>
            </tr>
        </table>

        <!-- ITEMS -->
        @php
        $isFactura = isset($tipo) && ($tipo == 'factura' || $tipo == 'facturarectificativa' || $tipo == 'facturaproforma');
        $ivas = [];
        $subtotal = 0;
        // 2 por defecto; si alguna línea tiene 3 decimales (o se mezclan 2 y 3), usamos 3 para todo el documento
        $documentDecimals = 2;
        $docType = $tipo ?? 'nota';
        $documentColumns = $documentColumns ?? \App\Helpers\DocumentColumnsHelper::filterByDocType(
        \App\Helpers\DocumentColumnsHelper::defaults(),
        $docType
        );
        $formatValue = function ($servicio, $column) {
        $field = $column['field'] ?? '';
        if (empty($field)) {
        return '';
        }
        // Intentar obtener el valor de diferentes formas
        $value = null;
        if (is_array($servicio)) {
        $value = $servicio[$field] ?? null;
        } elseif (is_object($servicio)) {
        $value = $servicio->$field ?? $servicio->getAttribute($field) ?? null;
        }
        // Si aún no se encontró, usar data_get como fallback
        if ($value === null) {
        $value = data_get($servicio, $field);
        }
        switch ($column['format'] ?? 'text') {
        case 'currency':
        return \App\Helpers\PriceHelper::formatWithSymbol((float) ($value ?? 0));
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
        'center' => 'text-center',
        'end' => 'right',
        default => 'text-left',
        };
        };
        @endphp
        <table class="items-table">

            <thead>
                <tr>
                    @foreach ($documentColumns as $column)
                    <th
                        class="{{ $alignClass($column['align'] ?? 'start') }}"
                        style="{{ isset($column['width']) ? 'width: ' . $column['width'] . '%;' : '' }}">
                        {{ $column['label'] ?? '' }}
                    </th>
                    @endforeach
                </tr>
            </thead>

            <tbody>
                @foreach($data as $servicio)
                @php
                // Obtener importe de forma segura (array o objeto)
                $importe = is_array($servicio) ? ($servicio['importe'] ?? 0) : ($servicio->importe ?? 0);
                $subtotal += $importe;
                if (\App\Helpers\PriceHelper::decimalPlaces((float) $importe) === 3) {
                $documentDecimals = 3;
                }
                if ($isFactura) {
                // Obtener iva_percent de forma segura (array o objeto)
                $tipo_iva = is_array($servicio)
                ? ($servicio['iva_percent'] ?? 21)
                : (isset($servicio->iva_percent) ? $servicio->iva_percent : 21);
                $iva_producto = ($importe * $tipo_iva) / 100;

                if (array_key_exists($tipo_iva, $ivas)) {
                $ivas[$tipo_iva] += $iva_producto;
                } else {
                $ivas[$tipo_iva] = $iva_producto;
                }
                }
                @endphp
                <tr>
                    @foreach ($documentColumns as $column)
                    <td class="{{ $alignClass($column['align'] ?? 'start') }}">
                        {{ $formatValue($servicio, $column) }}
                    </td>
                    @endforeach
                </tr>
                @endforeach
            </tbody>
        </table>

        <!-- TOTALS -->
        <table class="totals-table">
            <tr>
                <td>Subtotal</td>
                <td class="value">{{ \App\Helpers\PriceHelper::formatWithDecimals($subtotal, $documentDecimals) }} €</td>
            </tr>
            @if($isFactura && isset($recibo->total_descuento))
            <tr>
                <td>Descuento</td>
                <td class="value">{{ \App\Helpers\PriceHelper::formatWithDecimals((float) ($recibo->total_descuento ?? 0), $documentDecimals) }} €</td>
            </tr>
            @endif
            @if($isFactura && count($ivas) > 0)
            @foreach($ivas as $tipo_iva => $iva_valor)
            <tr>
                <td style="{{ $loop->first ? 'padding-top: 12px;' : '' }}">Iva {{ $tipo_iva }}%:</td>
                <td class="value" style="{{ $loop->first ? 'padding-top: 12px;' : '' }}">{{ \App\Helpers\PriceHelper::formatWithDecimals($iva_valor, $documentDecimals) }} €</td>
            </tr>
            @endforeach
            @endif
            <tr>
                @php
                $total_iva = $isFactura ? array_sum($ivas) : 0;
                $total_descuento = (isset($recibo->total_descuento) && isset($tipo) && $tipo != 'nota') ? $recibo->total_descuento : 0;
                $total_final = $subtotal + $total_iva - $total_descuento;
                @endphp
                <td><strong>TOTAL</strong></td>
                <td class="value"><strong>{{ \App\Helpers\PriceHelper::formatWithDecimals($total_final, $documentDecimals) }} €</strong></td>
            </tr>
        </table>

        <!-- MÉTODOS DE PAGO -->
        @php
        $metodosPago = $userLog->metodos_pago ?? null;
        $hayMetodosActivos = $metodosPago && (
        $metodosPago->pago_uno_activo ||
        $metodosPago->pago_dos_activo ||
        $metodosPago->pago_tres_activo ||
        $metodosPago->pago_cuatro_activo ||
        $metodosPago->pago_cinco_activo
        );
        @endphp

        @if($hayMetodosActivos)
        <table class="payments-table">
            <thead>
                <tr>
                    <th colspan="2">Métodos de pago</th>
                </tr>
            </thead>
            <tbody>
                @if ($metodosPago->pago_uno_activo)
                <tr>
                    <td style="width: 40%;">Transferencia Bancaria</td>
                    <td>{{ $metodosPago->pago_uno }}</td>
                </tr>
                @endif
                @if ($metodosPago->pago_dos_activo)
                <tr>
                    <td>Giro Bancario</td>
                    <td>{{ $metodosPago->pago_dos }}</td>
                </tr>
                @endif
                @if ($metodosPago->pago_tres_activo)
                <tr>
                    <td>Efectivo</td>
                    <td>{{ $metodosPago->pago_tres }}</td>
                </tr>
                @endif
                @if ($metodosPago->pago_cuatro_activo)
                <tr>
                    <td>Paypal</td>
                    <td>{{ $metodosPago->pago_cuatro }}</td>
                </tr>
                @endif
                @if ($metodosPago->pago_cinco_activo)
                <tr>
                    <td>Bizum</td>
                    <td>{{ $metodosPago->pago_cinco }}</td>
                </tr>
                @endif
            </tbody>
        </table>
        @endif

        @if(isset($tipo) && in_array($tipo, ['factura', 'facturaproforma', 'facturarectificativa']) && !empty($signature))
        <div style="margin-top: 2rem; text-align: right;">
            <img src="{{ $signature }}" alt="Firma" style="max-height: 48px; max-width: 180px;">
            <p style="font-size: 9px; margin: 2px 0 0 0;">Fdo.</p>
        </div>
        @endif

    </div>

</body>

</html>
