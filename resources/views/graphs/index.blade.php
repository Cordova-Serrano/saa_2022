@extends ('layout')

@section ('title', 'Seguimiento de Alumnos del Área | Ver gráficas')

@section ('styles')
<!-- Select2 -->
<link href="{{ asset('plugins/select2/css/select2.css') }}" rel="stylesheet">
@endsection

@section ('content')
<div class="row">
    <div class="col-12">
        @if ($message = session('warning'))
        <div class="alert alert-warning alert-dismissible text-justify" id="warning-alert">
            <button aria-hidden="true" class="close" data-dismiss="alert" type="button">&times;</button>
            <h6><i class="icon fal fa-exclamation-triangle"></i>¡Advertencia!</h6>
            {{ $message }}
        </div>
        @endif
        <div class="card card-outline card-reflex-blue">
            <div class="card-header">
                <div class="d-flex flex-wrap justify-content-between">
                    <div class="col d-flex">
                        <h3 class="card-title my-auto">Ver gráficas</h3>
                    </div>
                    <div class="col d-flex justify-content-end">
                        <div class="btn-group">
                            <select class="form-control select2" id="select-graph" style="width: 100%;">
                                <option value="scatter" selected>Créditos Acumulados</option>
                                <option value="bar">Rezago Académico</option>
                            </select>
                        </div>
                        <div class="mr-2">
                            <select class="form-control select2 select2-reflex-blue" style="width: 100%" data-dropdown-css-class="select2-reflex-blue" data-placeholder="Seleccione el semestre" id="semesters" name="" required>
                            {{-- <option selected value=''>Seleccionar semestre</option> --}}
                            </select>
                        </div>
                        <div class="" id="career-select2">
                            <select class="form-control select2 select2-reflex-blue" style="width: 100%" data-dropdown-css-class="select2-reflex-blue" data-placeholder="Seleccione la carrera" id="careers" name="" required>
                                {{-- <option selected value=''>Seleccionar carrera</option> --}}
                                {{-- <option value=""></option> --}}
                            </select>
                        </div>
                        <div class="" id="generation-select">
                            <select class="form-control select2 select2-reflex-blue" style="width: 100%" data-dropdown-css-class="select2-reflex-blue" data-placeholder="Seleccione generacion" id="generations" name="" required>
                                {{-- <option selected value=''>Seleccionar generación</option> --}}
                                {{-- <option value=""></option> --}}
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div id="graph" style="text-align: center; min-height: 500;">
            </div>
        </div>
    </div>
</div>

<!-- Modal Variable -->
<div class="modal fade" id="modal-var" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title-saa">Cálculo de rezago</h5>
                <button type="button" class="close" id="close-modal">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="d-flex">
                    <div class="text-center d-flex justify-content-center align-items-center mr-2">
                        <label for="">Variable:</label>
                    </div>
                    <input type="number" class="form-control" id="var">
                </div>
            </div>
            <div class="modal-footer justify-content-between">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-success" id="accept-var">Aceptar</button>
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
<script src="https://cdn.plot.ly/plotly-2.14.0.min.js"></script>
<script type="text/javascript">
    function RenderGraphs(records) {
        const graphType = document.getElementById('select-graph').value;
        const URL = "http://127.0.0.1:8000/graph/" + graphType;
        const request = {
            method: "POST",
            headers: {"content-type": "application/json", "accept": "application/json"},
            body: JSON.stringify({
                records: records
            }),
        }
        console.log(request);
        fetch(URL, request)
            .then(response => response.json())
            .then(data => {
                json_graph = JSON.parse(data);

                Plotly.newPlot('graph', json_graph);
            }
        );
    }
</script>
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
        console.log(semesters)
        let select_semesters = $('#semesters')
        var newOption = new Option("Selecciona semestre", '0', false, false);
            select_semesters.append(newOption).trigger('change');
        $.each(semesters, function(i, semester) {
            var newOption = new Option(semester.semester, semester.id, false, false);
            select_semesters.append(newOption).trigger('change');
        });
        //Careers
        var careers = @json($careers);
        let select_careers = $('#careers')
        var newOption = new Option("Selecciona carrera", "0", false, false);
            select_careers.append(newOption).trigger('change');
        $.each(careers, function(i, career) {
            var newOption = new Option(career.name, career.name, false, false);
            select_careers.append(newOption).trigger('change');
        });
        var generations = @json($gen);
        let select_generations = $('#generations')
        var newOption = new Option("Selecciona generación", "0", false, false);
            select_generations.append(newOption).trigger('change');
        $.each(generations, function(i, generations) {
            var newOption = new Option(generations.generation, generations.generation, false, false);
            select_generations.append(newOption).trigger('change');
        });

        $('#overlay').hide();
    });


    function make_query(){
        var semester = null;
        if ($('#semesters').val() != "0")
         semester = $('#semesters').val();
        var career = null;
        if ($('#careers').val() != "0")
            career = $('#careers').val();
        var generation = null;
        if ($('#generations').val() != "0")
            generation = $('#generations').val();
        //Ajax semester
        if((semester != null)||(career != null)||(generation != null))
        $.ajax({
                url: "/load_query",
                type: "get",
                data: {
                    semester_id: semester,
                    career : career,
                    generation : generation,
                },
                success: function(response) {
                    console.log(response)

                    RenderGraphs(response);
                    
                    if(response.length <= 0)
                        console.log("No hay datos");
                        //funcion para mostrar alerta o algo para hacerle saber al usuario que no hay registros coincidentes
                },
                error: function(xhr) {
                    console.log(xhr)
                }
            });
    }

    $('#semesters').change(function(){//make query when semesters change
        make_query();
    })

    $('#careers').change(function(){//make query when careers change
        make_query();
    })
    
    $('#generations').change(function(){//make query when generations change
        make_query();
    })

    $('#select-graph').change(function(){//make query when graph type change
        if($(this).val() === 'bar'){
            console.log($(this).val());
            //Clear current graph 
            //Open Modal
            $('#modal-var').modal('show');
        }
        else{
            make_query();
        } 
    })

    $('#close-modal').click(function() {
        $('#modal-var').modal('hide');
    })

    $('#accept-var').click(function() {
        //AÑadir funcion para mandar variable a graph
    })
    // $('#semesters').change(function() {
    //     if ($(this).val() != '') {
    //         $('#career-select2').show()
    //         //Ajax semester
    //         $.ajax({
    //             url: "/load_semester",
    //             type: "get",
    //             data: {
    //                 semester_id: $(this).val(),
    //             },
    //             success: function(response) {
    //                 const table = $("#data-table").DataTable();
    //                 table.clear()
    //                 $.each(response, function(i, student) {
    //                     table.row.add(student).draw();
    //                 });

    //             },
    //             error: function(xhr) {
    //                 console.log(xhr)
    //             }
    //         });
    //     }
    // })

    
    // $('#careers').change(function() {
    //     if ($(this).val() != '') {
    //         $('#generation-select').show()
    //         //Ajax semester
    //         $.ajax({
    //             url: "/load_career",
    //             type: "get",
    //             data: {
    //                 semester_id: $('#semesters').val(),
    //                 career : $('#careers option:selected').text(),
    //             },
    //             success: function(response) {
    //                 const table = $("#data-table").DataTable();
    //                 table.clear()
    //                 $.each(response, function(i, student) {
    //                     table.row.add(student).draw();
    //                 });

    //             },
    //             error: function(xhr) {
    //                 console.log(xhr)
    //             }
    //         });
    //     }
    //     console.log($('#careers option:selected').text());
    // })
    // $('#generations').change(function() {
    //     if ($(this).val() != '') {
    //         //Ajax generation
    //         $.ajax({
    //             url: "/load_generation",
    //             type: "get",
    //             data: {
    //                 semester_id: $('#semesters').val(),
    //                 career : $('#careers option:selected').text(),
    //                 generation : $('#generations option:selected').text(),
    //             },
    //             success: function(response) {
    //                 const table = $("#data-table").DataTable();
    //                 table.clear()
    //                 $.each(response, function(i, student) {
    //                     table.row.add(student).draw();
    //                 });

    //             },
    //             error: function(xhr) {
    //                 console.log(xhr)
    //             }
    //         });
    //     }
    //     console.log($('#generations option:selected').text());
    // })
    
</script>
@endsection
