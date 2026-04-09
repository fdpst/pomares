<p style="white-space: pre-line;">{!! $des ?? 'Adjunto la documentación solicitada.' !!}</p><br>

@if (isset($tipo))
    @if ($tipo == 'Facturas Enviadas' || $tipo == 'Todas')
        <a href="{{ url('client-files/' . $user['filetoken'] . '/facturas_enviadas.zip') }}"> 
            <button style="background-color: #4CAF50; color: white; padding: 10px 20px; border: none; border-radius: 4px; cursor: pointer; margin: 5px;">
                Descargar Facturas Enviadas
            </button>
        </a>
    @endif <br>

    @if ($tipo == 'Facturas Recibidas' || $tipo == 'Todas')
        <a href="{{ url('client-files/' . $user['filetoken'] . '/facturas_recibidas.zip') }}">
            <button style="background-color: #2196F3; color: white; padding: 10px 20px; border: none; border-radius: 4px; cursor: pointer; margin: 5px;">
                Descargar Facturas Recibidas
            </button>
        </a>
    @endif
@endif
