<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Factura</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 10px;
            margin: 0;
            padding: 0;
            /* Quitamos el padding del body para que el container controle todo */
            box-sizing: border-box;
            color: #333;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .container {
            width: 100%;
            max-width: 700px;
            margin: 0 auto;
            border: none;
            /* Elimina el borde/marco exterior de la factura */
            padding: 30px;
            /* Padding de 30px para toda la factura */
            box-sizing: border-box;
            display: flex;
            flex-direction: column;
            flex-grow: 1;
            min-height: 100vh;
            /* Ajustado para el nuevo padding del body */
        }

        .top-section {
            text-align: center;
            margin-bottom: 30px;
        }

        .logo {
            width: 120px;
            height: auto;
            margin-bottom: 20px;
        }

        .header-content {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            /* Alinea los elementos por su parte inferior */
            margin-bottom: 20px;
        }

        .client-info {
            width: 50%;
        }

        .client-info h2 {
            font-size: 14px;
            margin-bottom: 10px;
            text-transform: uppercase;
        }

        .client-info p {
            margin: 0;
            line-height: 1.6;
        }

        .company-info {
            text-align: left;
            border: 1px solid black;
            /* Borde negrito */
            padding: 10px;
            width: 30%;
            box-sizing: border-box;
        }

        .company-info p {
            margin: 0;
            line-height: 1.4;
        }

        .invoice-details-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .invoice-details-table th,
        .invoice-details-table td {
            border: 1px solid black;
            /* Borde negrito */
            padding: 8px;
            text-align: left;
        }

        .invoice-details-table th {
            background-color: #f2f2f2;
            font-weight: normal;
        }

        /* Ajuste de ancho de columnas para invoice-details-table */
        .invoice-details-table th:nth-child(1),
        /* Número de factura */
        .invoice-details-table td:nth-child(1) {
            width: 25%;
        }

        .invoice-details-table th:nth-child(2),
        /* Fecha */
        .invoice-details-table td:nth-child(2) {
            width: 20%;
        }

        .invoice-details-table th:nth-child(3),
        /* Descripción (la más amplia) */
        .invoice-details-table td:nth-child(3) {
            width: 55%;
        }


        .product-section {
            flex-grow: 1;
            display: flex;
            flex-direction: column;
        }

        .full-width-table {
            width: 100%;
            border-collapse: collapse;
            border: 1px solid black;
            /* Borde negrito para el marco exterior de la tabla de productos/totales */
            flex-grow: 1;
            display: table;
            table-layout: fixed;
            /* Mantiene los anchos de columna fijos */
        }

        .full-width-table thead,
        .full-width-table tfoot {
            display: table-row-group;
        }

        .full-width-table tbody {
            display: table-row-group;
            height: 100%;
            position: relative;
        }


        .full-width-table th {
            border: 1px solid black;
            /* Bordes negritos */
            padding: 8px;
            text-align: left;
            background-color: #f2f2f2;
            font-weight: normal;
        }

        /* Ajuste de ancho de columnas para full-width-table (tabla de productos) */
        .full-width-table th:nth-child(1) {
            /* Nro */
            width: 10%;
        }

        .full-width-table th:nth-child(2) {
            /* Cantidad */
            width: 15%;
        }

        .full-width-table th:nth-child(3) {
            /* Descripción (la más amplia) */
            width: 45%;
            /* Ajusta este porcentaje según veas en la prueba */
        }

        .full-width-table th:nth-child(4) {
            /* Precio */
            width: 15%;
        }

        .full-width-table th:nth-child(5) {
            /* Subtotal */
            width: 15%;
        }


        .full-width-table td {
            padding: 8px;
            text-align: left;
            height: 25px;
            /* Altura estándar para las filas de productos con contenido */
            border-left: 1px solid black;
            /* Bordes negritos */
            border-right: 1px solid black;
            /* Bordes negritos */
            border-bottom: 1px solid black;
            /* Bordes negritos */
        }

        .full-width-table tbody tr:first-of-type td {
            border-top: none;
        }

        .stretch-row td {
            height: auto;
            min-height: 100px;
            /* Asegúrate de que esta altura sea suficiente para empujar el tfoot */
            border-bottom: none !important;
            border-left: none !important;
            border-right: none !important;
            border-top: none !important;
        }

        /* Alineación de precios de productos a la izquierda */
        .full-width-table .price,
        .full-width-table .subtotal {
            text-align: left;
            /* Cambiado a izquierda */
        }

        /* Alineación de montos totales a la derecha */
        .full-width-table .total-amount {
            text-align: right;
        }


        /* Bordes y estilos específicos para las filas de totales (en tfoot) */
        /* Quitamos todos los bordes por defecto de las celdas del tfoot */
        .full-width-table tfoot td {
            border: none;
            padding: 8px;
        }

        /* Aplicamos bordes horizontales a las filas de totales */
        .full-width-table tfoot .total-row-item,
        .full-width-table tfoot .total-row {
            border-top: 1px solid black;
            border-bottom: 1px solid black;
        }

        /* La celda del QR tiene sus bordes específicos */
        .qr-cell {
            text-align: center;
            vertical-align: top;
            padding: 10px;
            border: 1px solid black !important;
            /* Borde negrito para el cuadro del QR */
            border-right: none !important;
            /* Elimina su borde derecho para que se fusione */
            border-bottom: 1px solid black !important;
            /* Asegurar borde inferior */
        }

        /* Clases para las celdas de etiqueta y monto en el tfoot */
        .total-label-cell {
            text-align: left;
            font-weight: normal;
            border-left: 1px solid black !important;
            /* Borde izquierdo visible */
            border-right: none !important;
            /* ¡Elimina el borde derecho para fusionar con la celda de monto! */
        }

        .total-amount-cell {
            text-align: right;
            border-left: none !important;
            /* ¡Elimina el borde izquierdo para fusionar con la celda de etiqueta! */
            border-right: 1px solid black !important;
            /* Borde derecho visible */
        }

        /* Estilo para la fila TOTAL */
        .full-width-table tfoot .total-row td {
            font-weight: bold;
            background-color: #f2f2f2;
        }

        .qr-code img {
            width: 80px;
            height: 80px;
            border: 1px solid black;
            /* Borde negrito */
            margin-bottom: 5px;
        }

        .qr-code p {
            font-size: 9px;
            margin: 0;
            line-height: 1.4;
        }

        .signature-block {
            margin-top: 2rem;
            text-align: right;
        }

        .signature-img {
            max-height: 48px;
            max-width: 180px;
            display: inline-block;
        }

        .signature-label {
            font-size: 9px;
            margin: 2px 0 0 0;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="top-section">
            <img src="{{ $business['logo'] }}" alt="logo" class="logo">
        </div>

        <div class="header-content">
            <div class="client-info">
                <h2>{{ strtoupper($customer['name']) }}</h2>
                <p>{{ $customer['name'] }}</p>
                <p> {{ $customer['locality'] . '(' . $customer['province'] . ')' }}</p>
                <p>CIF/NIF: {{ $customer['cif'] }}</p>
                <p>Tel: {{ $customer['phone'] ?? '' }}</p>
                <p>Email: {{ $customer['email'] }}</p>
            </div>
            <div class="company-info">
                <p>{{ $business['name'] }}</p>
                <p>{{ $business['address'] }}</p>
                <p>{{ $business['postal_code'] }}</p>
                <p>{{ $business['province'] }}</p>
                <p>{{ $business['cif'] }}</p>
            </div>
        </div>

        <table class="invoice-details-table">
            <thead>
                <tr>
                    <th>Número de factura</th>
                    <th>Fecha</th>
                    <th>Descripción</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>{{ $invoice_header['invoice_number'] }}</td>
                    <td>{{ $invoice_header['date'] }}</td>
                    <td>{{ $invoice_header['description'] }}</td>
                </tr>
            </tbody>
        </table>

        <div class="product-section">
            <table class="full-width-table">
                <thead>
                    <tr>
                        <th class="col-nro">Nro</th>
                        <th class="col-cantidad">Cantidad</th>
                        <th class="col-descripcion">Descripción</th>
                        <th class="col-precio">Precio</th>
                        <th class="col-subtotal">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($details as $detail)
                    <tr>
                        <td>{{ $detail['nro'] }}</td>
                        <td>{{ $detail['quantity'] }}</td>
                        <td>{{ $detail['description'] }}</td>
                        <td class="price">{{ $detail['price'] }}€</td>
                        <td class="subtotal">{{ $detail['subtotal'] }}€</td>
                    </tr>
                    @endforeach
                    <tr class="stretch-row">
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                </tbody>
            </table>
            <div class="footer clearfix">
                <div class="qr">
                    @if (
                    !empty($invoice_header['invoice_qr_string']) &&
                    !empty($invoice_header['invoice_qr_img']) &&
                    $business['has_electronic_billing']
                    )
                    <img src="{{ $invoice_header['invoice_qr_img'] }}" alt="">
                    <span> Código Veri*Factu: {{ $invoice_header['invoice_qr_string'] }}</span>
                    @endif
                </div>
                <div class="totales">
                    <table>
                        <tbody>
                            @foreach ($details as $detail)
                            <tr>
                                <td>Subtotal</td>
                                <td>{{ $invoice_totals['subtotal'] }} €</td>
                            </tr>
                            <tr>
                                <td>IVA 21%</td>
                                <td>{{ $invoice_totals['iva'] }} €</td>
                            </tr>
                            <tr>
                                <td>Total</td>
                                <td>{{ $invoice_totals['total'] }} €</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        </thead>

        </table>
        <div class="footer clearfix">
            <div class="qr">
                @if (
                !empty($invoice_header['invoice_qr_string']) &&
                !empty($invoice_header['invoice_qr_img']) &&
                $business['has_electronic_billing']
                )
                <img src="{{ $invoice_header['invoice_qr_img'] }}" alt="">
                <span> Código Veri*Factu: {{ $invoice_header['invoice_qr_string'] }}</span>
                @endif
            </div>
            <div class="totales">
                <table>
                    <tbody>
                        @foreach ($details as $detail)
                        <tr>
                            <td>{{ $detail['nro'] }}</td>
                            <td>{{ $detail['quantity'] }}</td>
                            <td>{{ $detail['description'] }}</td>
                            <td class="price">{{ $detail['price'] }}€</td>
                            <td class="subtotal">{{ $detail['subtotal'] }}€</td>
                        </tr>
                        @endforeach
                        <tr class="stretch-row">
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                    </tbody>
                    <tfoot>
                        <tr class="total-row-item">
                            <td rowspan="3" colspan="3" class="qr-cell">
                                @if (!empty($invoice_header['invoice_qr_string']) && !empty($invoice_header['invoice_qr_img']))
                                <img src="{{ $invoice_header['invoice_qr_img'] }}" alt="">
                                <p>Código Verifi*Factu: {{ $invoice_header['invoice_qr_string'] }}
                                </p>
                                @endif
                            </td>
                            <td class="total-label-cell">SUBTOTAL</td>
                            <td class="total-amount-cell">{{ $invoice_totals['subtotal'] }} €</td>
                        </tr>
                        <tr class="total-row-item">
                            <td class="total-label-cell">IVA 21 %</td>
                            <td class="total-amount-cell">{{ $invoice_totals['iva'] }} €</td>
                        </tr>
                        <tr class="total-row">
                            <td class="total-label-cell">TOTAL</td>
                            <td class="total-amount-cell">{{ $invoice_totals['total'] }} €</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

        @if (!empty($signature))
        <div class="signature-block">
            <img src="{{ $signature }}" alt="Firma" class="signature-img">
            <p class="signature-label">Fdo.</p>
        </div>
        @endif
</body>

</html>
