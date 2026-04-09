<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
    <meta charset="utf-8">
    <title>Orden Sepa</title>
</head>

<body>
    @include('pdf.orden.encabezado')
    @include('pdf.orden.acreedor')
    @include('pdf.orden.legal')
    @include('pdf.orden.deudor')

    <div class="footer">
        <p>TODOS LOS CAMPOS HAN DE SER CUMPLIMENTADOS OBLIGATORIAMENTE. UNA VEZ FIRMADA ESTA ORDEN
            DE DOMICILIACIÓN DEBE SER ENVIADA AL ACREEDOR PARA SU CUSTODIA.
        </p>
        <span>
            ALL GAPS ARE MANDATORY. ONCE THIS MANDATE HAS BEEN SIGNED MUST BE SENT TO CREDITOR FOR STORAGE
        </span>
    </div>

    <style media="screen">
        body {
            margin-top: -25px;
        }

        p {
            margin: 5px 0 0 0;
            font-size: 14px;

        }

        span {
            font-size: 11px;
        }

        .footer {
            width: 70%;
            margin: auto;
            text-align: center;
            margin-top: 15px;
        }

        .footer p {
            font-size: 10px !important;
            margin-bottom: 8px;

        }

        .footer span {
            font-size: 8px !important;
        }
    </style>
</body>

</html>