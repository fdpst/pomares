<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
    <meta charset="utf-8">
    <title>Recibo</title>
</head>

<body>
    <table style="width:100%" class="header">
        <tr>
            <td>
                <img height="50" style="padding:1rem" src="{{ public_path() . '/logo.jpg' }}" alt="">
            </td>
            <td style="width:100%">
                <table  style="width:100%">
                    <tr>
                        <td colspan="">
                        <div style="color: #546e7a; !important; font-weight:bold">REGISTRO DE I.V.A. REPERCUTIDO<div>

                        </td>
                    </tr>
                    <tr>
                        <td colspan="1">
                            <span style="font-weight:bold">Empresa:</span> {{$empresa}}
                        </td>
                        <td>
                            <span style="font-weight:bold">Ejercicio:</span> {{$ejercicio}}
                        </td>
                        <td>
                            <span style="font-weight:bold">Fecha:</span> {{$fecha->format('d/m/Y')}}
                        </td>
                        <td style="width:20rem"></td>
                    </tr>
                    <tr>
                        <td>
                            <span style="font-weight:bold">Desde:</span> {{$desde->format('d/m/Y')}} <span style="font-weight:bold">Hasta:</span> {{$hasta->format('d/m/Y')}}
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
    @php
        $chunks = $recibos->chunk(42);
            $index = 1;
            $base = 0;
            $imp_iva =0;
            $total = 0;
            $page = 0;
            $pages = count($chunks);
            $ivas= [];
        @endphp
    @foreach($chunks as $chunk)
    @php
        $page++;
    @endphp
    <div @if($index != 1)class="page_break"@endif style="width:100%;">
    <table class="tabla" style="width:100%; margin-top:80px">
        <tr>
            <th>Nº REG.</th>
            <th>FECHA FACT.</th>
            <th>CUENTA</th>
            <th>CLIENTE</th>
            <th>C.I.F.</th>
            <th>Nº DOC.</th>
            <th>T.FACT</th>
            <th>BASE IMP.</th>
            <th>% I.V.A.</th>
            <th>IMP. IVA</th>
            <th>% R.E</th>
            <th>IMP. R.E.</th>
            <th>RETENCIÓN</th>
            <th>TOTAL</th>
            <th>TIPO IVA</th>
        </tr>
     
        @foreach ($chunk as $recibo)
        @if($index%2 == 0)
        <tr style="background-color: #EAF0F3">

        @else
        <tr>

        @endif
            <td>{{$index}}</td>
            <td>{{\Carbon\Carbon::parse($recibo->fecha)->format('d/m/Y')}}</td>
            <td>{{$recibo?->cliente?->cuentaContable?->numero??'000000000'}}</td><!--Insertar Numero de Cuenta-->
            <td>{{$recibo?->cliente?->nombre}}</td>
            <td>{{$recibo?->cliente?->dni}}</td>
            <td>A/{{$recibo->nro}}</td>
            <td>ORD</td>
            <td>{{number_format( $recibo->sub_total,2,','.'')}}</td>
            <td>{{number_format( $recibo->tipo_iva,2,','.'')}}</td>
            <td>{{number_format( $recibo->iva,2,','.'')}}</td>
            <td></td>
            <td></td>
            <td></td>
            <td>{{number_format( $recibo->total,2,','.'')}}</td>
            <td>{{$recibo->iva>0?'Régimen general':'Exento'}}</td>       
        </tr>     

      
        @php
            $index++;
            if(!isset($ivas[number_format( $recibo->tipo_iva,2,','.'')])){
                $ivas[number_format( $recibo->tipo_iva,2,','.'')] = ['base'=>0,'iva'=>0];
            }
            $ivas[number_format( $recibo->tipo_iva,2,','.'')]['iva'] +=$recibo->iva;
            $ivas[number_format( $recibo->tipo_iva,2,','.'')]['base'] +=$recibo->sub_total;

            $base += $recibo->sub_total;
            $imp_iva += $recibo->iva;
            $total += $recibo->total;
        @endphp
        
    
        @endforeach
        <tr>
            <td colspan ="4"></td>
            <td>{{$page ==$pages?'Total':'Suma y sigue'}}</td>
            <td colspan="2"></td><!--Insertar Numero de Cuenta-->
            <td>{{number_format( $base,2,','.'')}}</td>
            <td></td> 
            <td>{{number_format( $imp_iva,2,','.'')}}</td>
            <td colspan="3"></td>
            <td>{{number_format( $total,2,','.'')}}</td>
        </tr>
        
    </table>
    @if($page ==$pages)
    <div style="background-color:#EAF0F3;width:30%;padding:1rem; padding-top:0.5rem">
        <h4>Resumen IVA Repercutido</h4>
        <table style="width:100%">
                <tr>
                    <th style="text-align:left">BASE IMPONIBLE</th>
                    <th style="text-align:left">% I.V.A</th>
                    <th style="text-align:left">CUOTA I.V.A.</th>
                </tr>
                @foreach($ivas as $key=>$iva)
                <tr>
                    <td style="text-align:left">{{number_format( $iva['base'],2,','.'')}}</td>
                    <td style="text-align:left">{{$key}}</td>
                    <td style="text-align:left">{{number_format( $iva['iva'],2,','.'')}}</td>
                </tr>
                @endforeach
        </table>
    </div>
        
    @endif
    <div style="width:100%;text-align:right;" class="footer">
        {{$page}}
    </div>
    @endforeach
 
    <style media="screen">
        * {
            font-size: 8px;
        }
        .page_break { page-break-before: always; }
        .full-width {
            width: 100%;
        }

        .tabla table,
        .tabla th,
        .tabla td {
            border-collapse: collapse;
            padding: 0.25rem
        }

        .tabla {
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

       

        .border-on-t-body {
            border-bottom: solid 1px #90a4ae !important;
        }

        .border-on-f-body {
            border-bottom: solid 1px #90a4ae !important;
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
        .header {
            position: fixed;
            top: 0;
        }
        .footer {
            position: fixed;
            bottom: 0;
        }
    </style>
</body>

</html>
