@extends ('layout')

@section ('title', 'Seguimiento de Alumnos del Área | Archivo CSV')

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
                    <h3 class="card-title my-auto">Subir / Actualizar archivo CSV</h3>
                    <button type="button" class="btn  btn-sm btn-success" data-toggle="modal" data-target="#modal-csv">
                        <i class="fal fa-plus-circle mr-2"></i>Nuevo
                    </button>
                </div>
            </div>
            <div class="card-body">
                <table class="table table-valign-middle table-hover" id="data-table">
                    <thead class="saa-table-header">
                        <tr>
                            <th>#</th>
                            <th>Nombre del archivo</th>
                            <th>Fecha de subibida</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal Subir Documento-->
<div class="modal fade" id="modal-csv" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title-saa">Semestre</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('csv.store') }}" autocomplete="off" class="form-horizontal" id="csv-form" method="post" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label" for="document_1">Archivo CSV:</label>
                        <div class="input-group col-sm-9">
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" id="document" name="document" required>
                                <label class="custom-file-label" for="exampleInputFile">Seleccione el archivo</label>
                            </div>
                        </div>
                        <input type="text" class="form-control" id="is_update" name="is_update"hidden>
                    </div>
                </div>
                <div class="modal-footer  justify-content-between">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-success">Aceptar</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- Modal Sobreescribir-->
<div class="modal fade" id="modal-csv-update" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header bg-warning">
                <h5 class="modal-title-saa">Sobreescribir archivo</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Ya existe un achivo existente <br>
                ¿Está seguro de querer actualizarlo?
            </div>
            <div class="modal-footer  justify-content-between">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-success" id="accept-update">Aceptar</button>
            </div>
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
            ajax: "{{ route('csv.index') }}",
            columnDefs: [{
                className: 'text-center',
                targets: [0, 1, 2]
            }, ],
            columns: [{
                    data: 'id'
                },
                {
                    data: 'name'
                },
                {
                    data: 'created_at',
                    render: function(data, row, type, meta) {
                        var dateDay = getDateFormatted(data);
                        return dateDay;
                    }
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
</script>
@endsection