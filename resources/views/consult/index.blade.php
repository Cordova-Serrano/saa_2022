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
            <div class="card-header">
                <div class="d-flex flex-wrap justify-content-between">
                    <div class="col d-flex">
                        <h3 class="card-title my-auto">Consulta de alumnos</h3>
                    </div>
                    <div class="col d-flex justify-content-end">
                        <div class="mr-2">
                            <select class="form-control select2 select2-reflex-blue" style="width: 100%" data-dropdown-css-class="select2-reflex-blue" data-placeholder="Seleccione el semestre" id="semesters" name="" required>
                                <option></option>
                            </select>
                        </div>
                        <div class="" id="career-select2" style="display: none;">
                            <select class="form-control select2 select2-reflex-blue" style="width: 100%" data-dropdown-css-class="select2-reflex-blue" data-placeholder="Seleccione la carrera" id="careers" name="" required>
                                <option></option>
                                <option value=""></option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <table class="table table-valign-middle table-hover" id="data-table">
                    <thead class="saa-table-header">
                        <tr>
                            <th>Clave UASLP</th>
                            <th>Clave larga</th>
                            <th>Generación</th>
                            <th>Nombre</th>
                            <th>Carrera</th>
                            <th>Situación</th>
                            <th>Créditos por cursar</th>
                            <th>Créditos por semestre</th>
                            <th>Semestres cursados</th>
                            <th>Porcentaje de avance</th>
                            <th>Promedio general</th>
                            <th>Rendimiento general</th>
                            <th>Promedio aprobatorio</th>
                            <th>Materias aprobadas</th>
                            <th>Materias reprobadas</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
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
<!---Select2-->
<script src="{{ asset('plugins/select2/js/select2.full.js') }}"></script>
<script src="{{ asset('plugins/select2/js/i18n/es.js') }}"></script>
@endsection

@section ('scripts')
<script type="text/javascript">
    //Initialize Select2 Elements
    $('.select2').select2()

    //Initialize Select2 Elements
    $('.select2bs4').select2({
        theme: 'bootstrap4'
    })

    let dt = null;

    $(function() {
        //Semesters
        var semesters = @json($semesters);
        let select_semesters = $('#semesters')
        $.each(semesters, function(i, semester) {
            var newOption = new Option(semester.semester, semester.id, false, false);
            select_semesters.append(newOption).trigger('change');
        });
        //Careers
        var careers = @json($careers);
        let select_careers = $('#careers')
        $.each(careers, function(i, career) {
            var newOption = new Option(career.name, career.id, false, false);
            select_careers.append(newOption).trigger('change');
        });
        //DataTable Draw
        dt = $('#data-table').DataTable({
            pageLength: 10,
            autoWidth: false,
            processing: true,
            responsive: true,
            searching: false,
            lengthChange: false,
            scrollX: true,
            language: {
                url: "{{ asset('plugins/datatables/jquery.dataTables.spanish.json') }}"
            },
            columnDefs: [{
                className: 'text-center',
                targets: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14]
            }, ],
            columns: [{
                    data: 'uaslp_key'
                },
                {
                    data: 'large_key'
                },
                {
                    data: 'generation'
                },
                {
                    data: 'name'
                },
                {
                    data: 'career'
                },
                {
                    data: 'status',
                    render: function(data, row, type, meta) {
                        let status = '<span class="badge bg-warning">N/A</span>';
                        switch (data) {
                            case 'INSCRITO':
                                status = '<span class="badge bg-success">INSCRITO</span>';
                                break;
                            case 'NO INSCRITO':
                                status = '<span class="badge badge-warning">NO INSCRITO</span>';
                                break;
                            case 'TITULADO':
                                status = '<span class="badge badge-primary">TITULADO</span>';
                                break;
                            case 'PASANTE':
                                status = '<span class="badge" style="background-color: #3d01a4; color: #fff">PASANTE</span>';
                                break;
                            case 'BAJA ACADEMICA':
                                status = '<span class="badge" style="background-color: #b800d7; color: #fff">BAJA ACADEMICA</span>';
                                break;
                            case 'BAJA TEMPORAL':
                                status = '<span class="badge" style="background-color: #a7194c; color: #fff">BAJA TEMPORAL</span>';
                                break;
                            case 'BAJA DEFINITIVA':
                                status = '<span class="badge badge-danger">BAJA DEFINITIVA</span>';
                                break;
                        }
                        return status;
                    }
                },
                {
                    data: 'creds_remaining'
                },
                {
                    data: 'creds_per_semester'
                },
                {
                    data: 'semesters_completed'
                },
                {
                    data: 'percentage_progress'
                },
                {
                    data: 'general_average'
                },
                {
                    data: 'general_performance'
                },
                {
                    data: 'app_average'
                },
                {
                    data: 'subjects_approved'
                },
                {
                    data: 'subjects_failed'
                },
            ],
        })

        $('#overlay').hide();
    });

    $('#semesters').change(function() {
        if ($(this).val() != '') {
            $('#career-select2').show()
            //Ajax semester
            $.ajax({
                url: "/load_semester",
                type: "get",
                data: {
                    semester_id: $(this).val(),
                },
                success: function(response) {
                    const table = $("#data-table").DataTable();
                    table.clear()
                    $.each(response, function(i, student) {
                        table.row.add(student).draw();
                    });

                },
                error: function(xhr) {
                    console.log(xhr)
                }
            });
        }
    })

    
    $('#careers').change(function() {
        if ($(this).val() != '') {
            //$('#career-select2').show()
            //Ajax semester
            $.ajax({
                url: "/load_career",
                type: "get",
                data: {
                    semester_id: $('#semesters').val(),
                    career : $('#careers option:selected').text(),
                },
                success: function(response) {
                    const table = $("#data-table").DataTable();
                    table.clear()
                    $.each(response, function(i, student) {
                        table.row.add(student).draw();
                    });

                },
                error: function(xhr) {
                    console.log(xhr)
                }
            });
        }
        console.log($('#careers option:selected').text());
    })
</script>
@endsection