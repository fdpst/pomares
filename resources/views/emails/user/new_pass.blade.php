@component('mail::message')

<img src="{{URL::asset('logo_menu.png')}}" alt="Fidias Gold" height="200">

<br>
<br>
<br>

Se ha registrado una nueva contraseña, asociada a
su USUARIO de fidifactu con el correo electrónico {{ $user->email }}

Credenciales:

Usuario: <strong>{{ $user->email }}</strong><br>
Contraseña Nueva: <strong>{{ $password }}</strong>

Importante!
Recomendamos una vez acceda modifique esta contraseña por razones de seguridad.

@component('mail::button', ['url' => Request::root()])
    Ingresar
@endcomponent

Gracias!!!<br>
 
@endcomponent
