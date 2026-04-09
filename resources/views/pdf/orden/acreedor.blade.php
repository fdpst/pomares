<div class="referencia-container">
    <div class="referencia">
        <p>Referencia de la orden de domiciliación</p>
        <span>Mandate reference</span>
    </div>

    <table>
        <tbody>
            <tr>
                <td class="right-column">
                    <p>Identificador del acreedor</p>
                    <span>Creditor identifier</span>
                </td>
                <td class="left-column">
                    <p>: &nbsp; 050605</p>
                    <span>&nbsp;</span>
                </td>
            </tr>
            <tr>
                <td class="right-column">
                    <p>Nombre del Acreedor</p>
                    <span>Creditor's Name</span>
                </td>
                <td class="left-column">
                    <p>: &nbsp; {{$user->nombre_fiscal}}</p>
                    <span>&nbsp;</span>
                </td>
            </tr>
            <tr>
                <td class="right-column">
                    <p>Dirección</p>
                    <span>Address</span>
                </td>
                <td class="left-column">
                    <p>: &nbsp; {{$user->direccion}}</p>
                    <span>&nbsp;</span>
                </td>
            </tr>
            <tr>
                <td class="right-column">
                    <p>Código Postal- Población</p>
                    <span>Postal Code - City</span>
                </td>
                <td class="left-column">
                    <p>: &nbsp; 03160 - ALMORADI</p>
                    <span>&nbsp;</span>
                </td>
            </tr>
            <tr>
                <td class="right-column">
                    <p>Provincia - País</p>
                    <span>Town - Country</span>
                </td>
                <td class="left-column">
                    <p>: &nbsp; ALICANTE - ESPAÑA</p>
                    <span>&nbsp;</span>
                </td>
            </tr>
        </tbody>
    </table>
</div>
<style media="screen">
    table {
        width: 100%;
    }

    table .right-column {
        width: 35%;
    }

    table .left-column p {
        margin-left: 10px;
    }

    .referencia-container {
        border-left: solid 1px;
        border-right: solid 1px;
        border-bottom: solid 1px;
        padding: 5px;
    }

    .referencia {
        margin-bottom: 60px;
    }
</style>