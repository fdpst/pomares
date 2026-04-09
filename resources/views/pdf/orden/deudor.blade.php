<div class="deudor-container">
    <table id="deudor_table">
        <tbody>
            <tr>
                <td class="right-column">
                    <p>Nombre del deudor</p>
                    <span>Name of the debtor(s)</span>
                </td>
                <td class="left-column">
                    <p>{{$cliente['nombre']}}</p>
                    <span>&nbsp;</span>
                </td>
            </tr>
            <tr>
                <td class="right-column">
                    <p>Dirección del deudor</p>
                    <span>Address 01 the debtor</span>
                </td>
                <td class="left-column">
                    <p>{{$cliente['direccion']}}</p>
                    <span>&nbsp;</span>
                </td>
            </tr>
            <tr>
                <td class="right-column">
                    <p>Código Postal - Población</p>
                    <span>Postal code and dty of the debtor </span>
                </td>
                <td class="left-column">
                    <p>{{$cliente['codigo_postal']}} - {{$cliente['localidad'] ? : 'N/A'}}</p>
                    <span>&nbsp;</span>
                </td>
            </tr>
            <tr>
                <td class="right-column">
                    <p>Provincia - País del deudor </p>
                    <span>Town - Country of the debtor </span>
                </td>
                <td class="left-column">
                    <p>{{$cliente['provincia'] ? $cliente['provincia']['nombre'] : 'N/A'}} - {{$cliente['pais'] ? $cliente['pais']['nombre'] : 'N/A'}}</p>
                    <span>&nbsp;</span>
                </td>
            </tr>
            <tr>
                <td class="right-column">
                    <p>Swift - BIC del banco deudor </p>
                    <span>Swift - SIC of the debtor bank</span>
                </td>
                <td class="left-column">
                    <p></p>
                    <span>&nbsp;</span>
                </td>
            </tr>
            <tr>
                <td class="right-column">
                    <p>Número de cuenta - IBAN</p>
                    <span>Account number 01 the debt -IBAN</span>
                </td>
                <td class="left-column">
                    <p></p>
                    <span>&nbsp;</span>
                </td>
            </tr>
        </tbody>
    </table>

    <table style="width:100%;" id="table_tipo_pago">
        <tbody>
            <tr>
                <td style="width:230px;" class="right-column">
                    <p>Tipo de pago</p>
                    <span>Type of payment</span>
                </td>

                <td class="recuadro">

                </td>

                <td style="width:90px;padding:0 15px;">
                    <p>Pago Recurrente</p>
                    <span>Recurrent payment</span>
                </td>

                <td style="width:50px;padding:0 15px;">
                    <p>o</p>
                    <span>or</span>
                </td>

                <td class="recuadro">

                </td>
                <td style="width:90px;padding:0 15px;">
                    <p>Pago Único</p>
                    <span>One-ofl payment </span>
                </td>
            </tr>
            <tr>
                <td colspan="1" class="right-column">
                    <p>Fecha - Localidad</p>
                    <span>Date - Location in wim you are signing</span>
                </td>
                <td colspan="5" class="right-column">
                    <p>{{\Carbon\Carbon::now()->format('d/m/Y')}}</p>
                    <span>&nbsp;</span>
                </td>
            </tr>
            <tr>
                <td colspan="1" class="right-column">
                    <p>Firma/s del deudor/es</p>
                    <span>Signature of the debtor </span>
                </td>
                <td colspan="5" class="right-column">
                    <p></p>
                    <span>&nbsp;</span>
                </td>
            </tr>
        </tbody>
    </table>
</div>


<style media="screen">
    .deudor-container {
        border: solid 1px;
        padding: 8px;
    }

    .recuadro {
        border: solid 1px;
        height: 10px;
        width: 30px;
    }

    #table_tipo_pago {
        margin-top: 30px;
        border-collapse: separate;
        border-spacing: 0.0em 0.5em;
    }

    #table_tipo_pago tbody tr td p {
        font-size: 11px;
    }

    #table_tipo_pago tbody tr td span {
        font-size: 8px;
    }



    #deudor_table {
        border-collapse: separate;
        border-spacing: 0.1em 0.5em;
    }

    #deudor_table tbody tr td {
        margin-bottom: 15px;
    }

    #deudor_table tbody tr td p {
        font-size: 11px;
    }

    #deudor_table tbody tr td span {
        font-size: 8px;
    }

    #deudor_table tbody tr .left-column {
        border: solid 1px;
        padding: 3px;

    }

    #deudor_table tbody tr {
        margin-bottom: 3px;
    }

    #deudor_table .right-column {
        width: 30%;
        vertical-align: top;
    }
</style>