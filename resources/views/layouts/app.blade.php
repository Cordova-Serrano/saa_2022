<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <!-- <meta name="viewport" content="width=device-width, initial-scale=1"> -->

    <meta content="width=device-width, initial-scale=1" name="viewport">
    <title>{{'Seguimiento de Alumnos del Área | Inicio de sesión' }}</title>
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback" rel="stylesheet">
    <link href="{{ asset('plugins/font-awesome-pro/css/all.css') }}" rel="stylesheet">
    <link href="{{ asset('core/css/adminlte.css') }}" rel="stylesheet">
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet">
    <link href="{{ asset('favicon.ico') }}" rel="shortcut icon" type="image/x-icon">
    <link href="{{ asset('favicon.ico') }}" rel="icon" type="image/x-icon">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>
</head>

<body class="hold-transition login-page" style="background-color: #004A98 !important">
    <div id="app" class="login-box">
        <div class="login-logo">
            <a href="">
                <img alt="Imagotipo" class="w-50" src="{{ asset('core/img/logo_uaslp.png') }}">
            </a>
        </div>

        <main class="py-4">
            @yield('content')
        </main>
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