<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1" name="viewport">
    <title>HSE | Iniciar Sesión</title>
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback" rel="stylesheet">
    <link href="{{ asset('plugins/font-awesome-pro/css/all.css') }}" rel="stylesheet">
    <link href="{{ asset('core/css/adminlte.css') }}" rel="stylesheet">
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet">
    <link href="{{ asset('favicon.ico') }}" rel="shortcut icon" type="image/x-icon">
    <link href="{{ asset('favicon.ico') }}" rel="icon" type="image/x-icon">
</head>

<body class="hold-transition login-page" style="background-color: #004A98 !important">
    <div class="login-box">
        <div class="login-logo">
            <a href="">
                <img alt="Imagotipo" class="w-50" src="{{ asset('core/img/logo_uaslp.png') }}">
            </a>
        </div>
        <div class="card">
            <div class="overlay" id="overlay">
                <i class="fal fa-2x fa-sync fa-spin"></i>
            </div>
            <div class="card-body login-card-body">
                <p class="login-box-msg">Ingresa para continuar</p>
                @if ($errors->any())
                <div class="alert alert-danger alert-dismissible text-justify" id="error-alert">
                    <button aria-hidden="true" class="close" data-dismiss="alert" type="button">&times;</button>
                    <h6><i class="icon fal fa-times-circle"></i>¡Error!</h6>
                    @foreach ($errors->all() as $error)
                    {{ $error }}<br>
                    @endforeach
                </div>
                @endif

                <form method="POST" action="{{ route('login') }}" autocomplete="off">
                    @csrf
                    <div class="input-group mb-3">
                        <input id="email" class="form-control @error('email') is-invalid @enderror" name="email" placeholder="Correo electrónico" required autofocus type="email" value="{{ old('email') }}" autocomplete="email">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fal fa-user"></span>
                            </div>
                        </div>
                        @error('email')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                    <div class="input-group mb-3">
                        <input id="password" class="form-control @error('password') is-invalid @enderror" name="password" placeholder="Contraseña" required type="password" autocomplete="current-password">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fal fa-lock-alt"></span>
                            </div>
                        </div>
                        @error('password')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                                <label class="form-check-label" for="remember">
                                    {{ __('Recuérdame') }}
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 text-center text-sm-right">
                            <button type="submit" class="btn btn-sm btn-primary">
                                <i class="fal fa-sign-in mr-2"></i>{{ __('Iniciar sesión') }}
                            </button>
                            @if (Route::has('password.request'))
                            <a class="btn btn-link" href="{{ route('password.request') }}">
                                {{ __('¿Olvidaste tu contraseña?') }}
                            </a>
                            @endif
                            @guest
                            @if (Route::has('register'))
                            <a class="nav-link" href="{{ route('register') }}">{{ __('Regístrate') }}</a>
                            @endif
                            @else
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }}
                                </a>

                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {{ __('Cerrar sesión') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                            @endguest
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script src="{{ asset('plugins/jquery/jquery.js') }}"></script>
    <script src="{{ asset('plugins/bootstrap/js/bootstrap.bundle.js') }}"></script>
    <script src="{{ asset('plugins/jquery-validation/jquery.validate.js') }}"></script>
    <script src="{{ asset('plugins/jquery-validation/localization/messages_es.js') }}"></script>
    <script src="{{ asset('core/js/adminlte.js') }}"></script>
    <script type="text/javascript">
        $(function() {
            $('#login-form').validate({
                errorPlacement: function(error, element) {
                    error.addClass('form-check-label invalid-feedback');
                    element.closest('.input-group').append(error);
                },
                highlight: function(element, errorClass, validClass) {
                    $(element).addClass('is-invalid');
                },
                unhighlight: function(element, errorClass, validClass) {
                    $(element).removeClass('is-invalid');
                },
                submitHandler: function(form) {
                    $('#overlay').show();

                    return true;
                }
            });

            $('#error-alert').fadeTo(5000, 1, function() {
                $(this).slideUp(1000);
            });

            $('#overlay').hide();
        });
    </script>
</body>

</html>