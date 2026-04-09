<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="utf-8">
    @php
        $serie = $recibo->serie?->serie ?? '';
        $nroRecibo = '';
        $title = '';
        $isFactura = false;
        $invoice_footer = $userLog->invoiceFooter()?->value ?? '';

        if ($tipo == 'factura') {
            $nroRecibo =
                $serie . substr($recibo->nro_factura->Anio->year, 2) . str_pad($nro_factura, 4, '0', STR_PAD_LEFT);
            $title = 'Factura';
            $isFactura = true;
        } elseif ($tipo == 'facturarectificativa') {
            $nroRecibo =
                $serie .
                substr($recibo->nro_factura_rectificativa->Anio->year, 2) .
                str_pad($nro_factura, 4, '0', STR_PAD_LEFT);
            $title = 'Factura Rectificativa';
            $isFactura = true;
        } elseif ($tipo == 'facturaproforma') {
            $nroRecibo =
                $serie . substr($recibo->nro_factura_prof->Anio->year, 2) . str_pad($nro_factura, 4, '0', STR_PAD_LEFT);
            $title = 'Factura Proforma';
            $isFactura = true;
        } elseif ($tipo == 'nota') {
            $nroRecibo = substr($recibo->nro_nota->Anio->year, 2) . str_pad($nro_factura, 4, '0', STR_PAD_LEFT);
            $title = 'Albarán';
        } elseif ($tipo == 'orden') {
            $nroRecibo = substr($recibo->nro_orden->Anio->year, 2) . str_pad($nro_factura, 4, '0', STR_PAD_LEFT);
            $title = 'Orden';
        } elseif ($tipo == 'presupuesto') {
            $nroRecibo = substr($recibo->nro_presupuesto->Anio->year, 2) . str_pad($nro_factura, 4, '0', STR_PAD_LEFT);
            $title = 'Presupuesto';
        } elseif ($tipo == 'parte_trabajo') {
            $nroRecibo =
                substr($recibo->nro_parte_trabajo->Anio->year, 2) . str_pad($nro_factura, 4, '0', STR_PAD_LEFT);
            $title = 'Parte de trabajo';
        } else {
            $nroRecibo = 'Sin asignar';
        }
    @endphp

    <title>{{ $title ?: 'Recibo' }} {{ $nroRecibo }}</title>

    <style>
        * body {
            font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;
        }

        body {
            margin: 0;
        }

        @page {
            margin: 160px 40px 120px 40px;
        }

        .contenido {
            margin: 0;
        }

        .table-container {
            min-height: 0;
            overflow: visible;
            page-break-after: auto;
        }

        .avoid-break {
            page-break-inside: avoid;
            break-inside: avoid;
        }

        table {
            border-collapse: collapse;
            width: 100%;
        }

        thead {
            display: table-header-group;
        }

        tfoot {
            display: table-footer-group;
        }

        tr,
        td,
        th {
            page-break-inside: avoid;
            break-inside: avoid;
        }

        .full-width {
            width: 100%;
        }

        .border {
            border: solid 1px #000;
            border-collapse: collapse;
        }

        .border-right {
            border-right: solid 1px #000;
        }

        .border-bottom {
            border-bottom: solid 1px #000;
        }

        .border-top {
            border-top: solid 1px #000;
        }

        .text-center {
            text-align: center;
        }

        .text-left {
            text-align: left;
        }

        .text-right {
            text-align: right;
        }

        .text-uppercase {
            text-transform: uppercase
        }

        .font-size-20 {
            font-size: 20px;
        }

        .font-size-18 {
            font-size: 18px;
        }

        .font-size-16 {
            font-size: 16px;
        }

        .font-size-14 {
            font-size: 14px;
        }

        .font-size-12 {
            font-size: 12px;
        }

        .font-size-10 {
            font-size: 10px;
        }

        .font-size-8 {
            font-size: 8px;
        }

        .pa-5 {
            padding: 5px
        }
    </style>
</head>

<body>
    @php
        $showLote = $show_lote ?? false;
        $hasIvaColumn = $isFactura;

        $loteWidth = $showLote ? '10%' : '0%';
        $articuloWidth = $showLote ? '18%' : '22%';
        $descripcionWidth = $showLote ? '26%' : '32%';
        $cantidadWidth = '10%';
        $precioWidth = '10%';
        $ivaWidth = $hasIvaColumn ? '12%' : '0%';
        $importeWidth = $hasIvaColumn
            ? ($showLote ? '14%' : '16%')
            : ($showLote ? '26%' : '28%');
    @endphp

    @include('pdf.header', ['title' => $title, 'cliente' => $recibo->cliente, 'userLog' => $userLog])
    @include('pdf.footer', ['invoice_footer' => $invoice_footer])

    <main class="contenido">
        <table class="border full-width font-size-12">
            <thead>
                <tr class="border-bottom">
                    <th style="width: 30%" class="border-right">
                        @if ($tipo == 'nota')
                            Número de albarán
                        @elseif ($tipo == 'presupuesto')
                            Número de presupuesto
                        @elseif ($tipo == 'orden')
                            Número de orden
                        @elseif ($tipo == 'parte_trabajo')
                            Número de parte de trabajo
                        @else
                            Número de factura
                        @endif
                    </th>
                    <th style="width: 20%" class="border-right">
                        Fecha
                    </th>
                    <th style="width: 50%">
                        Descripción
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="border-right text-center pa-5">
                        {{ $nroRecibo }}
                    </td>
                    <td class="border-right text-center pa-5">
                        {{ $fecha }}
                    </td>
                    <td class="pa-5">
                        {{ $recibo['observaciones'] }}
                    </td>
                </tr>
            </tbody>
        </table>

        <!-- Tabla de productos/servicios -->
        <div class="table-container full-width font-size-12" style="margin-top: 20px;">
            <table class="border full-width">
                <thead>
                    <tr class="border-bottom">
                        @if ($showLote)
                            <th style="width: {{ $loteWidth }}" class="border-right">
                                Lote
                            </th>
                        @endif
                        <th style="width: {{ $articuloWidth }}" class="border-right">
                            Artículo
                        </th>
                        <th style="width: {{ $descripcionWidth }}" class="border-right">
                            Descripción
                        </th>
                        <th style="width: {{ $cantidadWidth }}" class="border-right">
                            Cantidad
                        </th>
                        <th style="width: {{ $precioWidth }}" class="border-right">
                            Precio
                        </th>
                        @if ($hasIvaColumn)
                            <th style="width: {{ $ivaWidth }}" class="border-right">
                                IVA (%)
                            </th>
                        @endif
                        <th style="width: {{ $importeWidth }}">
                            Importe
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $lineas = $recibo->servicios;
                        $total = 0;
                        $ivas = [];
                        $subtotales = [];
                    @endphp

                    @foreach ($lineas as $index => $servicio)
                        @php
                            $articulo = optional($servicio->Servicio)->descripcion;
                            if (!$articulo && !empty($servicio->id_servicio)) {
                                $articulo = $servicio->id_servicio;
                            }
                            $articulo = $articulo ?? '-';
                        @endphp
                        <tr>
                            @if ($showLote)
                                <td class="border-right pa-5">{{ $servicio->lote ?? '-' }}</td>
                            @endif
                            <td class="border-right pa-5">{{ $articulo }}</td>
                            <td class="border-right pa-5">{{ $servicio->descripcion }}</td>
                            <td class="border-right text-center pa-5">{{ $servicio->cantidad }}</td>
                            <td class="border-right text-center pa-5">
                                {{ number_format($servicio->precio, 2, ',', '.') }}</td>
                            @if ($hasIvaColumn)
                                <td class="border-right text-center pa-5">{{ $servicio->iva_percent }}</td>
                            @endif
                            <td class="text-center pa-5">{{ number_format($servicio->importe, 2, ',', '.') }}</td>
                        </tr>

                        @php
                            $total += $servicio->cantidad * $servicio->precio;
                            $tipo_iva = isset($servicio->iva_percent) ? $servicio->iva_percent : 21;

                            $iva_producto = ($servicio->importe * $tipo_iva) / 100;
                            $total_segun_iva = $servicio->cantidad * $servicio->precio;

                            // Verificar si ya existe una entrada para este tipo de IVA en el arreglo $ivas
                            if (array_key_exists($tipo_iva, $ivas)) {
                                // Si ya existe, sumar el IVA actual al valor existente
                                $ivas[$tipo_iva] += $iva_producto;
                                $subtotales[$tipo_iva] += $total_segun_iva;
                            } else {
                                // Si no existe, agregar una nueva entrada con el IVA actual
                                $ivas[$tipo_iva] = $iva_producto;
                                $subtotales[$tipo_iva] = $total_segun_iva;
                            }
                        @endphp
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Totales -->
        <table class="full-width font-size-12" style="margin-top: 10px">
            <tr>
                {{-- Si es factura (enviada, proforma o rectificativa) y el usuario tiene factura electrónica --}}
                @if ($isFactura && $userLog->has_electronic_billing)
                    <td colspan="2" class="border-right text-center pa-5">
                        <table>
                            <tr>
                                <td>
                                    <div style="width: 100px; height: 100px;">
                                        <img width="100%" height="100%" class="qr"
                                            src="{{ $recibo->qr_code_electronic_invoice }}"
                                            alt="codigo qr de factura electronica">
                                    </div>
                                </td>
                                <td>
                                    <strong>
                                        Código Veri*Factu: {{ $recibo->qr_code_electronic_invoicing_string }}.
                                        Factura verificable en la sede electrónica de la AEAT
                                    </strong>
                                </td>
                            </tr>
                        </table>
                    </td>
                    <td colspan="3" class="text-center">
                        <table style="width: 100%; border-collapse: collapse; border-spacing: 0;">
                            <tr>
                                <td
                                    style="text-align: right; padding: 5px; border-bottom: 1px solid #000; border-right: none;">
                                    <strong class="text-uppercase">SubTotal:</strong>
                                </td>
                                <td
                                    style="text-align: right; padding: 5px; border-bottom: 1px solid #000; border-left: none;">
                                    {{ number_format($total, 2, ',', '.') }} €
                                </td>
                            </tr>

                            @if ($isFactura)
                                @foreach ($ivas as $tipo_iva => $iva_valor)
                                    <tr>
                                        <td
                                            style="text-align: right; padding: 5px; border-bottom: 1px solid #000; border-right: none;">
                                            <strong>IVA {{ $tipo_iva }}%:</strong>
                                        </td>
                                        <td
                                            style="text-align: right; padding: 5px; border-bottom: 1px solid #000; border-left: none;">
                                            {{ number_format($iva_valor, 2, ',', '.') }} €
                                        </td>
                                    </tr>
                                @endforeach
                            @endif

                            {{-- Recordar que nota es el albaran --}}
                            @if ($tipo != 'nota')
                                <tr>
                                    <td
                                        style="text-align: right; padding: 5px; border-bottom: 1px solid #000; border-right: none;">
                                        <strong class="text-uppercase">descuento:</strong>
                                    </td>
                                    <td
                                        style="text-align: right; padding: 5px; border-bottom: 1px solid #000; border-left: none;">
                                        {{ number_format($recibo->total_descuento ?? 0, 2, ',', '.') }} €
                                    </td>
                                </tr>
                            @endif

                            <tr>
                                <td style="text-align: right; padding: 5px; font-weight: bold;">
                                    <strong class="text-uppercase">Total:</strong>
                                </td>
                                <td style="text-align: right; padding: 5px; font-weight: bold;">
                                    {{ number_format($total + array_sum($ivas) - $recibo->total_descuento, 2, ',', '.') }}
                                    €
                                </td>
                            </tr>
                        </table>
                    </td>
                @else
                    {{-- En caso contrario No se muestra la seccion del QR --}}
                    <td width="32%"></td>
                    <td width="32%"></td>
                    <td width="36%" class="text-center">
                        <table class="border full-width">
                            <tr>
                                <td style="text-align: right; padding: 5px; border-right: none;">
                                    <strong>Subtotal:</strong>
                                </td>
                                <td style="text-align: right; padding: 5px; border-left: none;">
                                    {{ number_format($total, 2, ',', '.') }} €
                                </td>
                            </tr>

                            @if ($isFactura)
                                @foreach ($ivas as $tipo_iva => $iva_valor)
                                    <tr>
                                        <td style="text-align: right; padding: 5px; border-right: none;">
                                            <strong>IVA {{ $tipo_iva }}%:</strong>
                                        </td>
                                        <td style="text-align: right; padding: 5px; border-left: none;">
                                            {{ number_format($iva_valor, 2, ',', '.') }} €
                                        </td>
                                    </tr>
                                @endforeach
                            @endif

                            {{-- Recordar que nota es el albaran --}}
                            @if ($tipo != 'nota')
                                <tr>
                                    <td style="text-align: right; padding: 5px; border-right: none;">
                                        <strong>Descuento:</strong>
                                    </td>
                                    <td style="text-align: right; padding: 5px; border-left: none;">
                                        {{ number_format($recibo->total_descuento ?? 0, 2, ',', '.') }} €
                                    </td>
                                </tr>
                            @endif

                            <tr>
                                <td style="text-align: right; padding: 5px; font-weight: bold;">
                                    <strong>Total:</strong>
                                </td>
                                <td style="text-align: right; padding: 5px; font-weight: bold;">
                                    {{ number_format($total + array_sum($ivas) - $recibo->total_descuento, 2, ',', '.') }}
                                    €
                                </td>
                            </tr>
                        </table>
                    </td>
                @endif
            </tr>
        </table>

        <!-- Métodos de pago -->
        @if (isset($userLog->metodos_pago))
            @php
                $metodosPago = $userLog->metodos_pago;
            @endphp

            @if (
                $metodosPago->pago_uno_activo ||
                    $metodosPago->pago_dos_activo ||
                    $metodosPago->pago_tres_activo ||
                    $metodosPago->pago_cuatro_activo ||
                    $metodosPago->pago_cinco_activo)
                <table class="border full-width font-size-12 avoid-break" style="margin-top: 10px">
                    <thead>
                        <tr class="border-bottom">
                            <th colspan="2"><strong>Métodos de pago</strong></th>
                        </tr>
                    </thead>
                    <tbody>
                        @if ($metodosPago->pago_uno_activo)
                            <tr>
                                <td class="border-right pa-5">Transferencia Bancaria</td>
                                <td class="border-right pa-5">{{ $metodosPago->pago_uno }}</td>
                            </tr>
                        @endif
                        @if ($metodosPago->pago_dos_activo)
                            <tr>
                                <td class="border-right pa-5">Giro Bancario</td>
                                <td class="border-right pa-5">{{ $metodosPago->pago_dos }}</td>
                            </tr>
                        @endif
                        @if ($metodosPago->pago_tres_activo)
                            <tr>
                                <td class="border-right pa-5">Efectivo</td>
                                <td class="border-right pa-5">{{ $metodosPago->pago_tres }}</td>
                            </tr>
                        @endif
                        @if ($metodosPago->pago_cuatro_activo)
                            <tr>
                                <td class="border-right pa-5">Paypal</td>
                                <td class="border-right pa-5">{{ $metodosPago->pago_cuatro }}</td>
                            </tr>
                        @endif
                        @if ($metodosPago->pago_cinco_activo)
                            <tr>
                                <td class="border-right pa-5">Bizum</td>
                                <td class="border-right pa-5">{{ $metodosPago->pago_cinco }}</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            @endif
        @endif
    </main>
</body>

</html>
