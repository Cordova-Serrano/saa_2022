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
                <h3 class="card-title">Ver gráficas</h3>
                <div class="card-tools">
                    <div class="col-sm-12">
                        <div class="select2-blue">
                            <select name="civil_status" class="select2" data-placeholder="Seleccione la carrera" data-dropdown-css-class="select2-blue" style="width: 100%;" required>
                                <option selected disabled hidden value=""></option>
                                <option value="SOLTERO">Ingeniería en Sistemas Inteligentes</option>
                                <option value="CASADO">Ingeniería en Computación</option>
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
@endsection

@section ('plugins')
<!-- Select2-->
<script src="{{ asset('plugins/select2/js/select2.full.js') }}"></script>
<script src="{{ asset('plugins/select2/js/i18n/es.js') }}"></script>
@endsection

@section ('scripts')
<script type="text/javascript">
    $(function() {
        $('.select2').select2({
            width: '100%'
        });

        $('.select2').on('change', function(e) {
            $(this).valid();
        });
    })
</script>
<script src="https://cdn.plot.ly/plotly-2.14.0.min.js"></script>
<script type="text/javascript" src="{{ asset('js/graphs.js') }}"></script>
@endsection