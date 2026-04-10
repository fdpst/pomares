@component('mail::message')

<div style="text-align: center;">
    <img src="{{ URL::asset('logo_menu.png') }}" alt="Martí Pomares, S.L" height="200" style="display: block; margin: 0 auto;">
</div>

<br>
<br>
<br>

Se ha registrado un nuevo usuario, asociada a
su correo electrónico {{ $user->email }}

Credenciales:

Usuario: <strong>{{ $user->email }}</strong><br>
Contraseña: <strong>{{ $password }}</strong>

Importante!
Recomendamos una vez acceda la primera vez modifique su contraseña por razones de seguridad.

@component('mail::button', ['url' => Request::root()])
    Ingresar
@endcomponent

Gracias!!!<br>

@endcomponent
