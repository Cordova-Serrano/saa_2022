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
                        <div class="" id="career-select2">
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
    let dataTable = null;

    $(function() {
        //Initialize Select2 Elements
        $('.select2').select2()

        //Initialize Select2 Elements
        $('.select2bs4').select2({
            theme: 'bootstrap4'
        })
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

        $('#overlay').hide();
    });

    $('#semesters').change(function() {
        if ($(this).val() != '') {
            $('#career-select2').show()
            const table = $("#data-table").DataTable({
                pageLength: 10,
                autoWidth: false,
                processing: true,
                responsive: true,
                searching: false,
                serverSide: true,
                lengthChange: false,
                scrollX: true,
                language: {
                    url: "{{ asset('plugins/datatables/jquery.dataTables.spanish.json') }}"
                },
                ajax: "/load_semester/" + $(this).val(),
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
            });

            //Data Table : Expedients
            dataTable = $('#data-table').DataTable({
                pageLength: 10,
                autoWidth: false,
                processing: true,
                responsive: true,
                searching: true,
                lengthChange: false,
                serverSide: true,
                dom: '<"#toolbar.toolbar">frtip',
                language: {
                    url: "{{ asset('plugins/datatables/jquery.dataTables.spanish.json') }}"
                },
                ajax: "{{ route('consult.test') }}",
                columnDefs: [{
                        className: 'text-center',
                        targets: [0, 1, 2, 3, 4, 5, 6, 7, 8]
                    },
                    {
                        orderable: false,
                        searchable: false,
                        targets: 8
                    }
                ],
                columns: [{
                        data: 'expedient_code',
                    },
                    {
                        data: 'patient_code',
                    },
                    {
                        data: 'patient_name',
                    },
                    {
                        data: 'doctor_name',
                    },
                    {
                        data: 'entry_date',
                        render: function(data, row, type, meta) {
                            var dateDay = getDateFormatted(data);
                            return dateDay;
                        }
                    },
                    {
                        data: 'status',
                        render: function(data, row, type, meta) {
                            let status = '<span class="badge bg-warning">N/A</span>';
                            switch (data) {
                                case 'ABIERTO':
                                    status = '<span class="badge bg-success">ABIERTO</span>';
                                    break;
                                case 'CERRADO':
                                    status = '<span class="badge badge-blue">CERRADO</span>';
                                    break;
                                case 'PRECIERRE':
                                    status = '<span class="badge" style="color: white; background-color: purple;">PRECIERRE</span>';
                                    break;
                                case 'LIQUIDADO':
                                    status = '<span class="badge badge-warning">LIQUIDADO</span>';
                                    break;
                                case 'MOROSO':
                                    status = '<span style="background-color:#fd7a0e;color:white;" class="badge">MOROSO</span>';
                                    break;
                            }
                            return status;
                        }
                    },
                    {
                        data: 'has_discount',
                        visible: false,
                    },
                    {
                        data: 'exit_date',
                        render: function(data, row, type, meta) {
                            console.log(data)
                            if (data) {
                                var dateDay = getDateFormatted(data);
                                return dateDay;
                            }
                            return null;
                        }
                    },
                    {
                        data: null,
                        defaultContent: '',
                        createdCell: function(td, cellData, rowData, row, col) {
                            if (rowData.status == "CERRADO") {
                                $(td).prepend(
                                    `<div class="btn-group">
                                <button type="button" class="btn btn-sm btn-reflex-blue show-record mr-1" title="Mostrar">
                                    <i class="fal fa-eye"></i>
                                </button>
                                <button type="button" class="btn btn-sm responsable-record mr-1" title="Responsable"
                                    style="background-color: purple;">
                                    <i class="fas fa-user" style="color: white;"></i>
                                </button>
                                <button type="button" class="btn btn-sm btn-primary  pdf-record mr-1" title="PDF">
                                    <i class="fas fa-download" style="color: white;"></i>
                                </button>
                            </div>`
                                )
                            } else if (rowData.status == "MOROSO") {
                                $(td).prepend(
                                    `<div class="btn-group">
                                <button type="button" class="btn btn-sm btn-reflex-blue show-record mr-1" title="Mostrar">
                                    <i class="fal fa-eye"></i>
                                </button>
                                <button type="button" class="btn btn-sm btn-warning edit-record mr-1" title="Editar">
                                    <i class="fal fa-pencil-alt"></i>
                                </button>
                                <button type="button" class="btn btn-sm responsable-record mr-1" title="Responsable"
                                    style="background-color: purple;">
                                    <i class="fas fa-user" style="color: white;"></i>
                                </button>
                                <button type="button" class="btn btn-sm btn-primary  pdf-record mr-1" title="PDF">
                                    <i class="fas fa-download" style="color: white;"></i>
                                </button>
                            </div>`
                                )
                            } else {
                                $(td).prepend(
                                    `<div class="btn-group">
                                <button type="button" class="btn btn-sm btn-reflex-blue show-record mr-1" title="Mostrar">
                                    <i class="fal fa-eye"></i>
                                </button>
                                <button type="button" class="btn btn-sm btn-warning edit-record mr-1" title="Editar">
                                    <i class="fal fa-pencil-alt"></i>
                                </button>
                                <button type="button" class="btn btn-sm responsable-record mr-1" title="Responsable"
                                    style="background-color: purple;">
                                    <i class="fas fa-user" style="color: white;"></i>
                                </button>
                                <button type="button" class="btn btn-sm bg-orange room-record mr-1" title="Habitación">
                                    <i class="far fa-exchange-alt"></i>
                                </button>
                                <button type="button" class="btn btn-sm btn-primary  pdf-record mr-1" title="PDF">
                                    <i class="fas fa-download" style="color: white;"></i>
                                </button>
                            </div>`
                                )
                            }

                        }
                    }
                ],
                drawCallback: () => {
                    $("#data-table_filter").remove();
                    $('.show-record').on('click', (e) => {
                        let expedient = dataTable.row($(e.currentTarget).closest('tr')).data();

                        showRecord(expedient);
                    });

                    $('.pdf-record').on('click', (e) => {
                        let expedient = dataTable.row($(e.currentTarget).closest('tr')).data();

                        downloadRecord(expedient);
                    });

                    $('.responsable-record').on('click', (e) => {
                        let expedient = dataTable.row($(e.currentTarget).closest('tr')).data();

                        responsableRecord(expedient);
                    });

                    $('.room-record').on('click', (e) => {
                        let expedient = dataTable.row($(e.currentTarget).closest('tr')).data();

                        roomRecord(expedient);
                    });

                    $('.edit-record').on('click', (e) => {
                        let expedient = dataTable.row($(e.currentTarget).closest('tr')).data();

                        editRecord(expedient);
                    });

                    $('.delete-record').on('click', (e) => {
                        let expedient = dataTable.row($(e.currentTarget).closest('tr')).data();

                        deleteRecord(expedient);
                    });
                },
                fnInitComplete: function() {
                    let toolbar = $('div#toolbar');
                    toolbar.html(
                        `<div class="d-flex flex-wrap justify-content-between mb-3">
                                    <div class="col-log-8 col-md-12 pl-0 pr-0">
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input id="paid-checkbox" name="stat" class="form-check-input" type="checkbox" value="ABIERTO"
                                            onclick="checkBox(this)">
                                            <label class="form-check-label">
                                                <span class="badge badge-success">ABIERTO</span>
                                            </label>
                                        </div>
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input id="canceled-checkbox" name="stat" class="form-check-input" type="checkbox" value="PRECIERRE"
                                            onclick="checkBox(this)">
                                            <label class="form-check-label">
                                                <span style="background-color:#800080; color:white;" class="badge">PRECIERRE</span>
                                            </label>
                                        </div>
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input id="pending-checkbox" name="stat" class="form-check-input" type="checkbox" value="LIQUIDADO"
                                            onclick="checkBox(this)">
                                            <label class="form-check-label">
                                                <span class="badge badge-warning">LIQUIDADO</span>
                                            </label>
                                        </div>
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input id="pending-checkbox" name="stat" class="form-check-input" type="checkbox" value="MOROSO"
                                            onclick="checkBox(this)">
                                            <label class="form-check-label">
                                                <span style="background-color:#fd7a0e;color:white;" class="badge">MOROSO</span>
                                            </label>
                                        </div>
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input id="pending-checkbox" name="stat" class="form-check-input" type="checkbox" value="CERRADO"
                                            onclick="checkBox(this)">
                                            <label class="form-check-label">
                                                <span class="badge badge-blue">CERRADO</span>
                                            </label>
                                        </div>
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input id="pending-checkbox" name="stat2" class="form-check-input" type="checkbox" value="1"
                                            onclick="checkBoxDiscount(this)">
                                            <label class="form-check-label">
                                                <span class="badge badge-primary" style="background-color: #087cfc !important;">APLICA DESCUENTO</span>
                                            </label>
                                        </div>
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input id="pending-checkbox" name="stat2" class="form-check-input" type="checkbox" value="0"
                                            onclick="checkBoxDiscount(this)">
                                            <label class="form-check-label">
                                                <span class="badge badge-info" style="background-color: #28cc94 !important;">SIN DESCUENTO</span>
                                            </label>
                                        </div>
                                    </div>
                                </div>`);
                },
                order: [
                    [0, 'desc']
                ]
            });
        }
    })

    $('#careers').change(function() {
        if ($(this).val() != '') {
            var data = $(this).select2('data');
            let career = data[0].text
            console.log(career)
        }
    })
</script>
@endsection