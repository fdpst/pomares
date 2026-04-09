<style>
    * body {
        font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;
    }

    .header {
        position: fixed;
        top: -130px;
        left: 0;
        right: 0;
        height: 120px;
        text-align: center;
    }

    .header table {
        font-weight: 700;
        font-size: 12px;
        width: 100%;
        border: none;
    }
</style>

@php
$userLogProvincia = isset($userLog['provincia']) ? $userLog['provincia']['nombre'] : '';
$clienteProvincia = isset($cliente['provincia']) ? $cliente['provincia']['nombre'] : '';
$clientePais = isset($cliente['pais']) ? $cliente['pais']['nombre'] : '';
$userLogPais = isset($userLog['pais']) ? $userLog['pais']['nombre'] : 'España';
$logoSrc = $logo_base64 ?? ($userLog['logo_base64'] ?? ($userLog['logo'] ?? ''));
@endphp
<header class="header">
    <table>
        <tr>
            <td colspan="3" class="text-left pa-5">
                <span class="text-uppercase font-size-18">{{ $title }}</span>
            </td>
        </tr>

        <tr>
            <td style="width: 33.33%;" class="text-left border pa-5">
                <table>
                    <tr>
                        <td width="100%">
                            @if (!empty($logoSrc))
                            <img style="max-height: 42px; width: auto; display: block; margin-bottom: 4px;" src="{{ $logoSrc }}" alt="Logo empresa">
                            @endif
                            <span class="text-uppercase">
                                {{ $userLog['nombre_fiscal'] ?? $userLog['name'] }}
                            </span><br>
                            C.I.F: {{ $userLog['cif'] }}<br>
                            {{ $userLog['direccion'] }}<br>
                            {{ $userLog['postal_code'] }} {{ $userLog['ciudad'] }}<br>
                            {{ $userLogProvincia }}, {{ $userLogPais }}
                        </td>
                    </tr>
                </table>
            </td>
            <td></td>
            <td style="width: 33.33%;" class="text-right border pa-5">
                <span class="text-uppercase">
                    {{ $cliente['nombre_comercial'] ?? $cliente['nombre'] }}
                </span><br>
                C.I.F: {{ $cliente['dni'] }} <br>
                {{ $cliente['direccion'] }} <br>
                {{ $cliente['codigo_postal'] }} {{ $cliente['localidad'] }} <br>
                {{ $clienteProvincia }}, {{ $clientePais ?: 'España' }}
            </td>
        </tr>
    </table>
</header>
