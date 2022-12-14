@extends ('layout')

@section ('title', 'Seguimiento de Alumnos del Área | Usuarios')

@section ('styles')
<link href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.css') }}" rel="stylesheet">
<link href="{{ asset('plugins/datatables-responsive/css/responsive.bootstrap4.css') }}" rel="stylesheet">
@endsection

@section ('content')
<div class="row">
    <div class="col-12">
        @if ($message = session('success'))
        <div class="alert alert-success alert-dismissible text-justify" id="success-alert">
            <button aria-hidden="true" class="close" data-dismiss="alert" type="button">&times;</button>
            <h6><i class="icon fal fa-check-circle"></i>¡Realizado!</h6>
            {{ $message }}
        </div>
        @endif
        @if ($message = session('change_room'))
        <div class="alert alert-success alert-dismissible text-justify" id="success-alert">
            <button aria-hidden="true" class="close" data-dismiss="alert" type="button">&times;</button>
            <h6><i class="icon fal fa-check-circle"></i>¡Realizado!</h6>
            {{ $message }}
        </div>
        @endif
        @if ($message = session('warning'))
        <div class="alert alert-warning alert-dismissible text-justify" id="warning-alert">
            <button aria-hidden="true" class="close" data-dismiss="alert" type="button">&times;</button>
            <h6><i class="icon fal fa-check-circle"></i>¡Advertencia!</h6>
            {{ $message }}
        </div>
        @endif
        @if ($errors->any())
        <div class="alert alert-danger alert-dismissible text-justify" id="error-alert">
            <button aria-hidden="true" class="close" data-dismiss="alert" type="button">&times;</button>
            <h6><i class="icon fal fa-times-circle"></i>¡Error!</h6>
            @foreach ($errors->all() as $error)
            {{ $error }}<br>
            @endforeach
        </div>
        @endif
        <div class="card card-outline card-reflex-blue">
            <div class="overlay" id="overlay">
                <i class="fal fa-2x fa-sync fa-spin"></i>
            </div>
            <div class="card-header">
                <div class="d-flex flex-wrap justify-content-between">
                    <h3 class="card-title my-auto">Usuarios</h3>
                    <button type="button" class="btn  btn-sm btn-success" data-toggle="modal" data-target="#modal-csv">
                        <i class="fal fa-plus-circle mr-2"></i>Nuevo
                    </button>
                </div>
            </div>
            <div class="card-body">
                <table class="table table-valign-middle table-hover" id="data-table">
                    <thead class="saa-table-header">
                        <tr>
                            <th scope="col">Nombre</th>
                            <th scope="col">Email</th>
                            <th scope="col">Username</th>
                            <th scope="col">Eliminar</th>
                            <th scope="col">Editar</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                    <thead class="saa-table-header">
                        <tr>
                            <th scope="col">Nombre</th>
                            <th scope="col">Email</th>
                            <th scope="col">Username</th>
                            <th scope="col">Eliminar</th>
                            <th scope="col">Editar</th>
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
        </div>
    </div>
</div>

<!-- Modal Añadir nuevo usuario-->
<div class="modal fade" id="modal-csv" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title-saa">Nuevo usuario</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
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
@endsection

@section ('scripts')
<script type="text/javascript">
    let dt = null;

    $(function() {
        dt = $('#data-table').DataTable({
            pageLength: 10,
            autoWidth: false,
            processing: true,
            responsive: true,
            searching: true,
            lengthChange: false,
            language: {
                url: "{{ asset('plugins/datatables/jquery.dataTables.spanish.json') }}"
            },
            ajax: "{{ route('users.index') }}",
            columnDefs: [{
                className: 'text-center',
                targets: [0, 1, 2]
            }, ],
            columns: [{
                    data: 'name'
                },
                {
                    data: 'email'
                },
                {
                    data: 'username',
                }
            ]
        });

        $('#csv-form').validate({
            rules: {
                email: {
                    email: true
                }
            },
            errorPlacement: function(error, element) {
                error.addClass('form-check-label invalid-feedback');
                element.closest('div').append(error);
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
        bsCustomFileInput.init();
        $('#overlay').hide();
    });


    $('#document').change(function() {
        var filename = $(this).val().replace(/.*(\/|\\)/, '')
        //Ajax Patients
        /*$.get('/update', function(data) {
            console.log(data)
        });*/
        //Ajax semester
        $.ajax({
            url: "/update",
            type: "get",
            data: {
                filename: filename,
            },
            success: function(response) {
                //Do Something
                console.log(response)
                if (response == 1) {
                    //Ya existe, mostrar modal sobreescribir
                    $modalUpdate = $('#modal-csv-update')
                    $modalUpdate.modal('show')

                } else {
                    //No existe subir a base de datos
                    $('#is_update').val(0)
                }
            },
            error: function(xhr) {
                //Do Something to handle error
                console.log(xhr)
            }
        });
    })

    $('#accept-update').click(function() {
        $('#is_update').val(1)
        $('#csv-form').submit();
    })

    function getDateFormatted(e) {
        let date = new Date(e)
        let year = date.getFullYear()
        let month = ('0' + (date.getMonth() + 1)).slice(-2)
        let day = date.getDate()
        let hour = date.getHours()
        let min = String(date.getMinutes()).padStart(2, '0')

        //Date Formatted
        date = day + '/' + month + '/' + year + " " + hour + ":" + min

        return date
    }

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