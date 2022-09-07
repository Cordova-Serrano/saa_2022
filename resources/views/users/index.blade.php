@extends ('layout')

@section ('title', 'Seguimiento de Alumnos del Área | Archivo CSV')

@section ('styles')
<!--Select2-->
<link href="{{ asset('plugins/select2/css/select2.css') }}" rel="stylesheet">
<!--Datatables-->
<link href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.css') }}" rel="stylesheet">
<link href="{{ asset('plugins/datatables-responsive/css/responsive.bootstrap4.css') }}" rel="stylesheet">
@endsection

@section ('content')
<div class="row">
    <!-- <h1>HOLA</h1> -->
    <div class="container">
        <div class="justify-content-center">
            <div class="card">
                <div class="overlay" id="overlay">
                    <i class="fal fa-2x fa-sync fa-spin"></i>
                </div>
                <div class="card-body login-card-body">
                    <p class="login-box-msg">Regístrate para continuar</p>
                    @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible text-justify" id="error-alert">
                        <button aria-hidden="true" class="close" data-dismiss="alert" type="button">&times;</button>
                        <h6><i class="icon fal fa-times-circle"></i>¡Error!</h6>
                        @foreach ($errors->all() as $error)
                        {{ $error }}<br>
                        @endforeach
                    </div>
                    @endif

                    <form method="POST" action="{{ route('users.index') }}" autocomplete="off" id="register-form">
                        @csrf
                        <!-- NAME -->
                        <div class="input-group mb-3">
                            <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus placeholder="Nombre">
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fal fa-user"></span>
                                </div>
                            </div>
                            @error('name')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                        <!-- /NAME -->
                        <!-- EMAIL -->
                        <div class="input-group mb-3">
                            <input id="email" class="form-control @error('email') is-invalid @enderror" name="email" placeholder="Correo electrónico" required type="email" value="{{ old('email') }}" autocomplete="email">
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <i class="fal fa-envelope"></i>
                                </div>
                            </div>
                            @error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                        <!-- /EMAIL -->
                        <!-- PASSWORD -->
                        <div class="input-group mb-3">
                            <input id="password" class="form-control @error('password') is-invalid @enderror" name="password" placeholder="Contraseña" required type="password" autocomplete="new-password">
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
                        <!-- /PASSWORD -->
                        <!-- CONFIRM PASSWORD -->
                        <div class="input-group mb-3">
                            <input id="password-confirm" class="form-control" name="password_confirmation" placeholder="Confirma contraseña" required type="password" autocomplete="new-password">
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fal fa-lock-alt"></span>
                                </div>
                            </div>
                        </div>
                        <!-- /CONFIRM PASSWORD -->
                        <!-- BUTTON -->
                        <div class="row mb-0">
                            <div class="col-12 text-center text-sm-right ">
                                <button type="submit" class="btn btn-sm btn-primary">
                                    <i class="fal fa-sign-in mr-2"></i>{{ __('Registra nuevo usuario') }}
                                </button>
                            </div>
                        </div>
                        <!-- /BUTTON -->
                    </form>
                </div>
            </div>
        </div>
    </div>
    <table class="table table-hover" style="width: 50%; margin-left: auto; margin-right: auto;">
        <thead>
            <tr>
                <th scope="col">Nombre</th>
                <th scope="col">Email</th>
                <th scope="col">Username</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $user)
            <tr>
                <th scope="row">{{$user->name}}</th>
                <td>{{$user->email}}</td>
                <td>{{$user->username}}</td>
                <!-- AGREGAR RUTA -->
                <td><a href="/eliminar/{{$user->id}}">Eliminar</a></td>
                <td>
                    <!-- Button trigger modal -->
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal{{$user->id}}">
                        Editar
                    </button>
                    <!-- <a data-bs-toggle="modal" data-bs-target="#exampleModal{{$user->id}}">Editar</a> -->
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection

@section ('plugins')
<!-- Form Validations -->
<script src="{{ asset('plugins/jquery-validation/jquery.validate.js') }}"></script>
<script src="{{ asset('plugins/jquery-validation/localization/messages_es.js') }}"></script>
<!-- Datatables -->
<script src="{{ asset('plugins/datatables/jquery.dataTables.js') }}"></script>
<script src="{{ asset('plugins/datatables-bs4/js/dataTables.bootstrap4.js') }}"></script>
<script src="{{ asset('plugins/datatables-responsive/js/dataTables.responsive.js') }}"></script>
<script src="{{ asset('plugins/datatables-responsive/js/responsive.bootstrap4.js') }}"></script>
<!-- Files -->
<script src="{{ asset('plugins/bs-custom-file-input/bs-custom-file-input.min.js') }}"></script>
<!---Select2-->
<script src="{{ asset('plugins/select2/js/select2.full.js') }}"></script>
<script src="{{ asset('plugins/select2/js/i18n/es.js') }}"></script>
@endsection

@section ('scripts')
<script type="text/javascript">
    $(function() {
        $('#register-form').validate({
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

        $('#overlay').hide();
    })
</script>
@endsection