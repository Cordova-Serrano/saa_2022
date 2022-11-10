@extends ('layout')

@section ('title', 'Seguimiento de Alumnos del Área | Importación CSV')

@section ('styles')
<!-- Dropify -->
<link href="{{ asset('plugins/dropify/core/dropify.min.css') }}" rel="stylesheet">
@endsection

@section ('content')
<div class="row">
    <div class="col-12">
        <div class="card card-outline card-reflex-blue">
            <div class="overlay" id="overlay">
                <i class="fal fa-2x fa-sync fa-spin"></i>
            </div>
            <div class="card-header">
                <div class="d-flex flex-wrap justify-content-between">
                    <h3 class="card-title my-auto">Importación archivo csv</h3>
                </div>
            </div>
            <form action="{{ route('csv.store') }}" autocomplete="off" class="form-horizontal" id="import-form" method="post" enctype="multipart/form-data">
                @csrf
                <div class="card-body">
                    <div class="row">
                        <input id="logo" type="file" class="dropify" name="file" data-max-file-size="15M" data-allowed-file-extensions="csv" data-default-file="">
                        <input type="text" class="form-control" id="is_update" name="is_update" hidden>
                    </div>
                    <div class="row mt-4">
                        <div class="alert alert-info alert-dismissible w-100 saa-bg-color">
                            <h5><i class="icon fas fa-info"></i> Importante!</h5>
                            -Formato por columna: cve_uaslp,cve_larga,generacion,nombre,carrera,situacion,cred_por_cursar,cred_por_semestre,semestres_cursados,porcentaje_avance,promedio_general,rendimiento_general,promedio_aprobatorio,materias_aprobadas,materias_reprobadas

                            <br>
                            -Subir archivo con encabezados
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="d-flex justify-content-between">
                        <a class="btn btn-secondary btn-icon-text" href="{{ route('csv.index') }}">
                            <i class="fas fa-times-circle mr-2"></i>Cancelar
                        </a>
                        <button id="import" class="btn btn-sm btn-warning btn-icon-text ml-2" type="submit">
                            <i class="fas fa-upload mr-2"></i>Importar
                        </button>
                    </div>
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
                Archivo ya existente <br>
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
<!-- Jquery Validations -->
<script src="{{ asset('plugins/jquery-validation/jquery.validate.js') }}"></script>
<script src="{{ asset('plugins/jquery-validation/localization/messages_es.js') }}"></script>
<script src="{{ asset('plugins/moment/moment.min.js') }}"></script>
<script src="{{ asset('plugins/moment/locale/es-mx.js') }}"></script>
<script src="{{ asset('plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.js') }}"></script>
<!-- Dropify -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/Dropify/0.2.2/js/dropify.min.js" integrity="sha512-8QFTrG0oeOiyWo/VM9Y8kgxdlCryqhIxVeRpWSezdRRAvarxVtwLnGroJgnVW9/XBRduxO/z1GblzPrMQoeuew==" crossorigin="anonymous"></script>
<script src="{{ asset('plugins/dropify/core/dropify.min.js') }}"></script>
@endsection

@section ('scripts')
<script type="text/javascript">
    $(document).ready(function() {
        //Validation forms
        $('#import-form').validate({
            ignore: '',
            rules: {},
            errorPlacement: function(error, element) {
                error.addClass('form-check-label invalid-feedback')
                element.closest('div').append(error)
            },
            highlight: function(element, errorClass, validClass) {
                $(element).addClass('is-invalid')
            },
            unhighlight: function(element, errorClass, validClass) {
                $(element).removeClass('is-invalid')
                $(element).addClass('is-valid')
            }
        })

        $('.dropify').dropify({
            messages: {
                'remove': 'Eliminar archivo',
                'error': 'Vaya, sucedió algo mal.',
                'default': 'Arrastre y suelte un archivo aquí o haga clic.',
                'replace': 'Arrastre y suelte un archivo aquí o haga clic para reemplazar.'
            },
            error: {
                'fileSize': 'El tamaño máximo del archivo es de 15M.',
                'imageFormat': 'Sólo puede subir archivos en formato PNG o JPG.'
            }
        });
        $('#overlay').hide();
    });

    $('#logo').change(function() {
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
        $('#import-form').submit();
    })

</script>
@endsection