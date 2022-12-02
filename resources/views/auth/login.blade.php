@extends('layouts.app')

@section('content')
<div class="container">
    <div class="justify-content-center">
        <!-- Card con el fomrulario de login -->
        <div class="card">
            <div class="overlay" id="overlay">
                <i class="fal fa-2x fa-sync fa-spin"></i>
            </div>
            <!-- Contenido del card -->
            <div class="card-body login-card-body">
                <p class="login-box-msg">Ingresa para continuar</p>
                <!-- Verifica si existe algun error y muestra una alerta en caso de error -->
                @if ($errors->any())
                <div class="alert alert-danger alert-dismissible text-justify" id="error-alert">
                    <button aria-hidden="true" class="close" data-dismiss="alert" type="button">&times;</button>
                    <h6><i class="icon fal fa-times-circle"></i>¡Error!</h6>
                    <!-- Muestra el mensaje de error correspondiente -->
                    @foreach ($errors->all() as $error)
                    {{ $error }}<br>
                    @endforeach
                </div>
                @endif

                <!-- FORMULARIO DE LOGIN -->
                <form method="POST" action="{{ route('login') }}" autocomplete="off" id="login-form">
                    @csrf
                    <!-- USERNAME -->
                    <div class="input-group mb-3">
                        <!-- input con id necesario para identificar el nombre de usuario ingresado-->
                        <input id="username" class="form-control @error('username') is-invalid @enderror" name="username" placeholder="Nombre de usuario" required autofocus type="username" value="{{ old('username') }}" autocomplete="off">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fal fa-user"></span>
                            </div>
                        </div>
                        <!-- Si sale error de username va a mostrar una alerta -->
                        @error('username')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                    <!-- /USERNAME -->
                    <!-- PASSWORD -->
                    <div class="input-group mb-3">
                        <!-- input con id necesario para identificar la contraseña ingresada -->
                        <input id="password" class="form-control @error('password') is-invalid @enderror" name="password" placeholder="Contraseña" required type="password" autocomplete="current-password">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fal fa-lock-alt"></span>
                            </div>
                        </div>
                        <!-- Si sale error de password va a mostrar una alerta -->
                        @error('password')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                    <!-- /PASSWORD -->
                    <!-- BUTTON -->
                    <div class="row">
                        <div class="col-12 text-center text-sm-right">
                            <!-- BOTON QUE ENVIA LA INFORMACION INGRESADA AL SISTEMA -->
                            <button type="submit" class="btn btn-sm btn-primary">
                                <i class="fal fa-sign-in mr-2"></i>{{ __('Iniciar sesión') }}
                            </button>
                            <!-- Si quiere activar la funcion de olvido contraseña descomentar esta sección -->
                            <!-- FORGOT YOUR PASSWORD? -->
                            <!-- @if (Route::has('password.request'))
                            <a class="btn btn-link" href="{{ route('password.request') }}">
                                {{ __('¿Olvidaste tu contraseña?') }}
                            </a>
                            @endif -->
                            <!-- /FORGOT YOUR PASSWORD? -->
                            @guest
                            <!-- Si quiere activar la funcion de registro descomentar esta sección -->
                            <!-- @if (Route::has('register'))
                            <a class="nav-link" href="{{ route('register') }}">{{ __('Regístrate') }}</a>
                            @endif
                            @else -->
                            <!-- MANDA UN ITEM DE NAVBAR DONDE MOSTRARA UN DROPDOWN -->
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }} <!-- Obtiene el nombre del usuario que ingreso al sistema -->
                                </a>

                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault(); 
                                                     document.getElementById('logout-form').submit();"> <!-- Boton manda a ruta de cerrar sesion -->
                                        {{ __('Cerrar sesión') }}
                                    </a>
                                    <!-- Sale del sistema, mandando a la ruta de logouts -->
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                            @endguest
                        </div>
                    </div>
                    <!-- /BUTTON -->
                </form>
                <!-- /FORMULARIO DE LOGIN -->
            </div>
        </div>
    </div>
</div>
@endsection

@section ('plugins')
<!-- Form Validations -->
<script src="{{ asset('plugins/jquery-validation/jquery.validate.js') }}"></script>
<!--<script src="{{ asset('plugins/jquery-validation/localization/messages_es.js') }}"></script>-->
<script type="text/javascript" src="{{ asset('plugins/jquery-validation/localization/messages_es.js') }}"></script>
@endsection

@section ('scripts')
<script type="text/javascript">
    // Valida las credenciales ingresadas en el formulario
    $(function() {
        $('#login-form').validate({
            lang: 'es',
            errorPlacement: function(error, element) { //define una clase para el elemento de error
                error.addClass('form-check-label invalid-feedback');
                element.closest('.input-group').append(error);
            },
            highlight: function(element, errorClass, validClass) { //se invoca el metodo highlight sobre el elemento que se quiere destacar
                $(element).addClass('is-invalid');
            },
            unhighlight: function(element, errorClass, validClass) { //se invoca el metodo unhighlight sobre el elemento que no se va a resaltar
                $(element).removeClass('is-invalid');
            },
            submitHandler: function(form) { //se invoca el metodo submitHandler para el envio de datos
                $('#overlay').show();

                return true;
            }
        });

        $('#overlay').hide();
    })
</script>
@endsection