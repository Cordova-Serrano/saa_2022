@php
$route = Route::currentRouteName();
@endphp
<!DOCTYPE html>
<html lang="es">

<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1" name="viewport">
    <title>@yield ('title')</title>
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback" rel="stylesheet">
    <link href="{{ asset('plugins/font-awesome-pro/css/all.css') }}" rel="stylesheet">
    <script src="https://cdn.plot.ly/plotly-latest.min.js"></script>
    <!-- etc. -->
    @yield ('styles')
    <link href="{{ asset('core/css/adminlte.css') }}" rel="stylesheet">
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet">
    <link href="{{ asset('favicon.ico') }}" rel="icon" type="image/x-icon">
    <link href="{{ asset('favicon.ico') }}" rel="shortcut icon" type="image/x-icon">
</head>

<body class="hold-transition sidebar-mini">
    <div class="wrapper">
        <nav class="main-header navbar navbar-expand navbar-light navbar-1235c">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="javascript:void(0)" role="button">
                        <i class="fal fa-bars"></i>
                    </a>
                </li>
            </ul>
            <ul class="navbar-nav ml-auto">
                <li class="nav-item dropdown">
                    <a class="nav-link mr-1 p-0" data-toggle="dropdown" href="javascript:void(0)">
                        <img class="h-100 img-circle elevation-1" src="{{ asset('core/img/logo_minimal_test.jpg') }}">
                    </a>
                    <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">

                        <!-- ----------------------------------------------------------------------------------------------------------------------- -->
                        <a id="navbarDropdown" class="dropdown-item dropdown-header" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                            {{ Auth::user()->name }}
                        </a>
                        <!-- ----------------------------------------------------------------------------------------------------------------------- -->
                        <div class="dropdown-divider"></div>
                        <form action="{{ route('logout') }}" autocomplete="off" method="post">
                            @csrf
                            <a class="dropdown-item" href="javascript:void(0)" onclick="event.preventDefault(); this.closest('form').submit();">
                                <i class="fal fa-sign-out mr-2"></i>Cerrar Sesión
                            </a>
                        </form>
                        <div class="dropdown-divider"></div>
                        <!-- ----------------------------------------------------------------------------------------------------------------- -->
                        </form>
                    </div>
                </li>
            </ul>
        </nav>
        <aside class="main-sidebar sidebar-light-reflex-blue elevation-4">
            <a class="brand-link" href="" style="background-color: #004A98 !important;">
                <img alt="Isotipo" class="brand-image" src="{{ asset('core/img/logo_uaslp.png') }}">
                <span class="brand-text">
                    <img alt="Logotipo" height="30" src="{{ asset('core/img/logo_ing_uaslp.png') }}">
                </span>
            </a>
            <div class="sidebar">
                <nav class="mt-2">
                    <ul class="nav nav-pills nav-sidebar flex-column" data-accordion="false" data-widget="treeview" role="menu">
                        <li class="nav-item">
                            <a class="nav-link {{ str_starts_with($route, 'home') ? 'active' : '' }}" href="/home">
                                <i class="nav-icon fas fa-clinic-medical"></i>
                                <p>Inicio</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ str_starts_with($route, 'csv') ? 'active' : '' }}" href="{{ route('csv.index') }}">
                                <i class="nav-icon fas fa-file-csv"></i>
                                <p>Archivo CSV</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ str_starts_with($route, 'graphs') ? 'active' : '' }}" href="{{ route('graphs.index') }}">
                                <i class="nav-icon fas fa-chart-bar"></i>
                                <p>Ver gráficas</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ str_starts_with($route, 'consult') ? 'active' : '' }}" href="{{ route('consult.index') }}">
                                <i class="nav-icon fas fa-users"></i>
                                <p>Consulta de alumnos</p>
                            </a>
                        </li>
                        @if (Auth::user()->hasRole('super'))
                        <li class="nav-item {{ (str_starts_with($route, 'classifications') || str_starts_with($route, 'settings') || str_starts_with($route, 'suppliers') || str_starts_with($route, 'users'))||  str_starts_with($route, 'fees') ||str_starts_with($route, 'charge-vouchers') ||  str_starts_with($route, 'type') ? 'menu-open' : '' }}">
                            <a class="nav-link {{ (str_starts_with($route, 'classifications') || str_starts_with($route, 'settings') || str_starts_with($route, 'suppliers') || str_starts_with($route, 'users')) || str_starts_with($route, 'charge-vouchers') ||  str_starts_with($route, 'fees') ? 'active' : '' }}" href="javascript:void(0)">
                                <i class="nav-icon fas fa-tools"></i>
                                <p>
                                    Configuración
                                    <i class="right fal fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a class="nav-link {{ str_starts_with($route, 'users') ? 'active' : '' }}" href="{{ route('users.index') }}">
                                        <i class="nav-icon fal fa-users"></i>
                                        <p>Usuarios</p>
                                    </a>
                                    <a class="nav-link" href="{{ route('register') }}">{{ __('Regístrate') }}</a>
                                </li>
                            </ul>
                        </li>
                        @endif
                    </ul>
                </nav>
            </div>
        </aside>
        <div class="content-wrapper">
            <section class="content-header"></section>
            <section class="content">
                <div class="container-fluid">
                    @yield ('content')
                </div>
            </section>
        </div>
        <footer class="main-footer text-center text-sm-left">
            <div class="float-right d-none d-sm-block">
                v1.0
            </div>
            &copy; {{ date('Y') }} <a href="javascript:void(0)">Facultad de Ingeniería, Área de Ciencias de la Computación</a>.
            Todos los derechos reservados.
        </footer>
    </div>
    <script src="{!! mix('js/app.js') !!}"></script>
    <script src="{{ asset('plugins/jquery/jquery.js') }}"></script>
    <script src="{{ asset('plugins/bootstrap/js/bootstrap.bundle.js') }}"></script>
    <script src="{{ asset('plugins/inputmask/jquery.inputmask.js') }}"></script>
    @yield ('plugins')
    <script src="{{ asset('core/js/adminlte.js') }}"></script>
    <script src="{{ asset('core/js/demo.js') }}"></script>
    <script type="text/javascript">
        String.prototype.capitalize = function() {
            return this.toLowerCase().split(' ').map(word => word.charAt(0).toUpperCase() + word.slice(1)).join(' ');
        }

        Inputmask.extendAliases({
            currency: {
                digits: 2,
                prefix: '$',
                allowMinus: !1,
                alias: 'numeric',
                digitsOptional: !1,
            },
            integer: {
                digits: 0,
                allowMinus: !1,
                alias: 'numeric'
            },
            percentage: {
                max: 100,
                digits: 2,
                suffix: '%',
                allowMinus: !1,
                alias: 'numeric',
                digitsOptional: !1
            },
            decimal: {
                max: 100,
                digits: 2,
                allowMinus: !1,
                alias: 'numeric',
                digitsOptional: !1
            },


        });

        $(function() {
            $('.inputmask').inputmask({
                rightAlign: false,
                showMaskOnFocus: false,
                showMaskOnHover: false,
                removeMaskOnSubmit: true
            });

            $('#error-alert, #success-alert, #warning-alert').fadeTo(5000, 1, function() {
                $(this).slideUp(1000);
            });
        });

        function drawAlert(parent, type, message) {
            let icon = '';
            let title = '';
            let element = new Date().toLocaleTimeString().replaceAll(':', '');

            switch (type) {
                case 'danger':
                    title = 'Error';
                    icon = 'times-circle';
                    break;
                case 'success':
                    title = 'Realizado';
                    icon = 'check-circle';
                    break;
                case 'warning':
                    title = 'Advertencia';
                    icon = 'exclamation-triangle';
                    break;
            }

            $(`#${ parent }`).prepend(
                `<div class="alert alert-${ type } alert-dismissible text-justify" id="${ element }">
                    <button aria-hidden="true" class="close" data-dismiss="alert" type="button">&times;</button>
                    <h6><i class="icon fal fa-${ icon }"></i>¡${ title }!</h6>
                    ${ message }
                </div>`
            );

            $(`#${ element }`).fadeTo(5000, 1, function() {
                $(this).slideUp(1000);
            });

            window.scrollTo(0, 0);
        }
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        })
    </script>
    @yield ('scripts')
</body>

</html>