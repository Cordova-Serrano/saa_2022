@extends ('layout')

@section ('title', 'Seguimiento de Alumnos del Área | Inicio')

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
                <h3 class="card-title">Bienvenido</h3>
                <div class="card-tools">
                    <button class="btn btn-tool" data-card-widget="collapse" title="Minimizar" type="button">
                        <i class="fal fa-minus"></i>
                    </button>
                    <button class="btn btn-tool" data-card-widget="remove" title="Cerrar" type="button">
                        <i class="fal fa-times"></i>
                    </button>
                </div>
            </div>
            <div class="card-body">
                Bienvenido.
            </div>
        </div>
    </div>
</div>
@endsection