@component('mail::message')
# Actualización de usuario

Su usuario ha sido actualizado

@component('mail::button', ['url' => Request::root()])
    Ingresar
    
@endcomponent

Gracias!!!
<br>

{{ config('app.name') }}

@endcomponent
