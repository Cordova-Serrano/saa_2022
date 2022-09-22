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
                    <h3 class="card-title my-auto">Nuevo Usuario</h3>
                </div>
            </div>
            <form action="{{ route('users.store') }}" autocomplete="off" class="form-horizontal" id="user-form" method="post">
                @csrf
                <div class="card-body">
                    <div class="row">
                        <div class="col">
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label mt-2" for="name">Nombre</label>
                                <div class="col-sm-9 mt-2">
                                    <input class="form-control" maxlength="255" name="name" placeholder="Nombre" required type="text" value="{{ old('name') }}">
                                </div>
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label mt-2" for="name">Rol</label>
                                <div class="col-sm-9 mt-2">
                                    <input class="form-control" maxlength="255" name="name" placeholder="Nombre" required type="text" value="{{ old('name') }}">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="d-flex justify-content-between">
                        <a class="btn btn-sm btn-secondary" href="{{ route('users.index') }}">
                            <i class="fal fa-times-circle mr-2"></i>Cancelar
                        </a>
                        <button class="btn btn-sm btn-primary" type="submit" id="save-btn">
                            <i class="fal fa-save mr-2"></i>Guardar
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section ('plugins')
<!-- Form Validations -->
<script src="{{ asset('plugins/jquery-validation/jquery.validate.js') }}"></script>
<script src="{{ asset('plugins/jquery-validation/localization/messages_es.js') }}"></script>
@endsection

@section ('scripts')
<script type="text/javascript">
    $(function() {
        $('#overlay').hide();
    });
</script>
@endsection