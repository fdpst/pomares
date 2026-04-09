<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
    <meta charset="utf-8">
    <title>FIDIAS GOLD | REGISTRO</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
    <link rel="shortcut icon" href="/favicon.png" />
</head>

<body>
    <div class="background">

        <nav class="blue-background">
            <div class="nav-wrapper">
                <div class="col s12">
                </div>
            </div>
        </nav>
        <div class="container">
            <div class="row">

                @if(session('mensaje'))
                <script>
                    M.toast({
                        html: "{{session('mensaje')}}",
                        classes: 'green accent-4'
                    })
                </script>
                @endif

                <div class="card custom-card col s12 l6 offset-l3">

                    <div class="logo">
                        <img height="80" src="{{URL::asset('logo.jpg')}}" alt="">
                    </div>

                    <h4 class="center-align">Registro</h4>


                    <form class="" action="{{route('save.registro')}}" method="post">
                        @csrf

                        <div class="row mb-0">
                            <div class="input-field col s12">
                                <label for="nombre" class="active">*Nombre</label>
                                <input id="nombre" type="text" name="name" value="">
                                @error('name')
                                <span class="red-text text-darken-2">{{$message}}</span>
                                @enderror
                            </div>
                        </div>

                        <!--<div class="row mb-0">
                            <div class="input-field col s12">
                                <label for="nombre_fiscal" class="active">Nombre Fiscal</label>
                                <input id="nombre_fiscal" type="text" name="nombre_fiscal" value="">
                            </div>
                        </div>-->

                        <div class="row mb-0">
                            <div class="input-field col s12">
                                <label for="email" class="active">*Email</label>
                                <input id="email" type="email" name="email" value="">
                                @error('email')
                                <span class="red-text text-darken-2">{{$message}}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-0">
                            <div class="input-field col s12">
                                <label for="telefono" class="active">*Teléfono</label>
                                <input id="telefono" type="text" name="telefono" value="">
                                @error('telefono')
                                <span class="red-text text-darken-2">{{$message}}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-0">
                            <div class="input-field col s12">
                                <select class="default" name="provincia_id">
                                    <option value=""></option>
                                    @foreach($provincias as $provincia)
                                    <option value="{{$provincia->id}}">{{$provincia->nombre}}</option>
                                    @endforeach
                                </select>
                                <label>Provincia</label>
                            </div>
                        </div>

                        <div class="row mb-0">
                            <div class="input-field col s12 center-align">
                                <button class="btn btn-small black" type="submit">Enviar</button>
                            </div>
                        </div>

                    </form>

                </div>
            </div>
        </div>
    </div>
    <script type="text/javascript">
        document.addEventListener('DOMContentLoaded', function() {
            const elems = document.querySelectorAll('select');
            const instances = M.FormSelect.init(elems, {});
        })
    </script>

    <style media="screen">
        body {
            min-height: 100vh;
        }

        .custom-card {
            margin-top: 2rem;
            padding: 1.5rem !important;
            border-radius: 7px;

        }

        .background {
            background-image: url("{{URL::asset('fondo.png')}}");
            background-position: center;
            background-size: cover;
            background-repeat: no-repeat;
            height: 100vh;
        }

        .mb-0 {
            margin-bottom: 0px;
        }

        .logo {
            text-align: center;
            padding: 0.8rem;
        }

        .blue-background {
            background-color: #1d2735 !important;
        }

        form span {
            font-size: 0.8rem;
        }
    </style>

</body>

</html>