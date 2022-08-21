@extends ('layout')

@section ('title', 'HSE | Nuevo Médico')

@section ('styles')
<link href="{{ asset('plugins/select2/css/select2.css') }}" rel="stylesheet">
<link href="{{ asset('plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.css') }}" rel="stylesheet">
@endsection

@section ('content')
<div class="row">
    <div class="col-12">
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
                <h3 class="card-title">Nuevo Médico</h3>
            </div>
            <form action="" autocomplete="off" class="form-horizontal" id="doctor-form" method="post" enctype="multipart/form-data">
                @csrf
                <div class="card-body">
                    <div class="row">
                        <div class="col-12 col-md-6">
                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label" for="name">Nombre</label>
                                <div class="col-sm-8">
                                    <input class="form-control" id="name" maxlength="150" name="name" placeholder="Nombre" required type="text" value="{{ old('name') }}">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label" for="rfc">RFC</label>
                                <div class="col-sm-8">
                                    <input class="form-control" id="rfc" maxlength="11" name="rfc" placeholder="RFC" required type="text" value="{{ old('rfc') }}">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label mt-2" data-target="#start-date" data-toggle="datetimepicker">Fecha de ingreso</label>
                                <div class="col-sm-8 mt-2">
                                    <div class="input-group datetimepicker" data-target-input="nearest" id="start-date">
                                        <input class="form-control" id="start_date" data-target="#start-date" data-toggle="datetimepicker" name="start_date" placeholder="Fecha" required type="text" value="{{ old('entry_date') }}">
                                        <div class="input-group-append cursor-pointer" data-target="#start-date" data-toggle="datetimepicker">
                                            <div class="input-group-text">
                                                <i class="fal fa-calendar-alt"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label" for="professional_license">Cédula Profesional</label>
                                <div class="col-sm-8">
                                    <input class="form-control" id="professional_license" maxlength="50" name="professional_license" placeholder="Cédula Profesional" required type="text" value="{{ old('professional_license') }}">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label" for="address">Dirección</label>
                                <div class="col-sm-8">
                                    <input class="form-control" id="address" maxlength="50" name="address" placeholder="Dirección" required type="text" value="{{ old('address') }}">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label" for="zip_code">Código Postal</label>
                                <div class="col-sm-8">
                                    <input class="form-control" id="zip_code" minlength="5" maxlength="5" name="zip_code" placeholder="Código Postal" required type="text" value="{{ old('zip_code') }}">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label" for="state">Estado</label>
                                <div class="col-sm-8">
                                    <div class="select2-blue">
                                        <select id="state" name="state" class="select2" data-placeholder="Seleccione un estado" data-dropdown-css-class="select2-blue" required style="width: 100%;">
                                            <option></option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label" for="email">Correo electrónico</label>
                                <div class="col-sm-8">
                                    <input class="form-control" id="email" name="email" placeholder="Correo electrónico" required type="email" value="{{ old('email') }}">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label" for="specialty">Especialidad</label>
                                <div class="col-sm-8">
                                    <select class="form-control select2 select2-reflex-blue" data-dropdown-css-class="select2-reflex-blue" data-placeholder="Especialidad" id="" name="[]" multiple="multiple" required>
                                        <option></option>
                                        <option value=""></option>

                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label" for="">Código</label>
                                <div class="col-sm-8">
                                    <input class="form-control" id="name" name="" required value="{{ $ }}" readonly>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label" for="curp">CURP</label>
                                <div class="col-sm-8">
                                    <input class="form-control" id="curp" maxlength="18" name="curp" placeholder="CURP" required type="text" value="{{ old('curp') }}">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label mt-2" data-target="#birthday" data-toggle="datetimepicker">Fecha de nacimiento</label>
                                <div class="col-sm-8 mt-2">
                                    <div class="input-group datetimepicker" data-target-input="nearest" id="birthday-date">
                                        <input class="form-control" id="birthday" data-target="#birthday" data-toggle="datetimepicker" name="birthday" placeholder="Fecha de nacimiento" required type="text">
                                        <div class="input-group-append cursor-pointer" data-target="#birthday" data-toggle="datetimepicker">
                                            <div class="input-group-text">
                                                <i class="fal fa-calendar-alt"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label" for="professional_level">Nivel académico</label>
                                <div class="col-sm-8">
                                    <input class="form-control" id="professional_level" maxlength="50" name="professional_level" placeholder="Nivel académico" required type="text" value="{{ old('professional_level') }}">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label" for="colony">Colonia</label>
                                <div class="col-sm-8">
                                    <input class="form-control" id="colony" name="colony" placeholder="Colonia" maxlength="50" required type="text" value="{{ old('colony') }}">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label" for="city">Ciudad</label>
                                <div class="col-sm-8">
                                    <input class="form-control" id="city" name="city" placeholder="Ciudad" maxlength="50" required type="text" value="{{ old('city') }}">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label" for="phone">Telefono</label>
                                <div class="col-sm-8">
                                    <input class="form-control" type="tel" id="phone" name="phone" minlength="10" maxlength="10" pattern="[0-9]{10}" placeholder="Teléfono" required value="{{ old('phone') }}">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label" for="civil_status">Estado Civil</label>
                                <div class="col-sm-8">
                                    <div class="select2-blue">
                                        <select name="civil_status" class="select2" data-placeholder="Seleccione un estado civil" data-dropdown-css-class="select2-blue" style="width: 100%;" required>
                                            <option selected disabled hidden value="">Seleccione un estado civil</option>
                                            <option value="SOLTERO">Soltero/a</option>
                                            <option value="CASADO">Casado/a</option>
                                            <option value="DIVORCIADO">Divorciado/a</option>
                                            <option value="SEPARADO">Separado/a</option>
                                            <option value="VIUDO">Viudo/a</option>
                                            <option value="CONCUBINATO">Concubinato</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <h5>Documentos Oficiales</h5>
                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label" for="document_1">Documento 1</label>
                                <div class="input-group col-sm-8">
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" id="document_1" name="document-1" required>
                                        <label class="custom-file-label" for="exampleInputFile">Seleccione el archivo</label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label" for="document_2">Documento 2</label>
                                <div class="input-group col-sm-8">
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" id="document_2" name="document-2" required>
                                        <label class="custom-file-label" for="exampleInputFile">Seleccione el archivo</label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label" for="document_3">Documento 3</label>
                                <div class="input-group col-sm-8">
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" id="document_3" name="document-3">
                                        <label class="custom-file-label" for="exampleInputFile">Seleccione el archivo</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="d-flex justify-content-between">
                        <a class="btn btn-sm btn-secondary" href="{{ route('doctors.index') }}">
                            <i class="fal fa-times-circle mr-2"></i>Cancelar
                        </a>
                        <button class="btn btn-sm btn-reflex-blue" type="submit">
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
<!-- Select2 -->
<script src="{{ asset('plugins/select2/js/select2.full.js') }}"></script>
<script src="{{ asset('plugins/select2/js/i18n/es.js') }}"></script>
<!-- Datetimepicker -->
<script src="{{ asset('plugins/moment/moment.min.js') }}"></script>
<script src="{{ asset('plugins/moment/locale/es-mx.js') }}"></script>
<script src="{{ asset('plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.js') }}"></script>
<!-- Files -->
<script src="{{ asset('plugins/bs-custom-file-input/bs-custom-file-input.min.js') }}"></script>
@endsection

@section ('scripts')
<script type="text/javascript">
    $(function() {
        $('#doctor-form').validate({
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

        $('.select2').select2({
            width: '100%'
        });

        $('.select2').on('change', function(e) {
            $(this).valid();
        });

        const states = [{
                "clave": "AGS",
                "nombre": "AGUASCALIENTES"
            },
            {
                "clave": "BC",
                "nombre": "BAJA CALIFORNIA"
            },
            {
                "clave": "BCS",
                "nombre": "BAJA CALIFORNIA SUR"
            },
            {
                "clave": "CHI",
                "nombre": "CHIHUAHUA"
            },
            {
                "clave": "CHS",
                "nombre": "CHIAPAS"
            },
            {
                "clave": "CMP",
                "nombre": "CAMPECHE"
            },
            {
                "clave": "CMX",
                "nombre": "CIUDAD DE MEXICO"
            },
            {
                "clave": "COA",
                "nombre": "COAHUILA"
            },
            {
                "clave": "COL",
                "nombre": "COLIMA"
            },
            {
                "clave": "DGO",
                "nombre": "DURANGO"
            },
            {
                "clave": "GRO",
                "nombre": "GUERRERO"
            },
            {
                "clave": "GTO",
                "nombre": "GUANAJUATO"
            },
            {
                "clave": "HGO",
                "nombre": "HIDALGO"
            },
            {
                "clave": "JAL",
                "nombre": "JALISCO"
            },
            {
                "clave": "MCH",
                "nombre": "MICHOACAN"
            },
            {
                "clave": "MEX",
                "nombre": "ESTADO DE MEXICO"
            },
            {
                "clave": "MOR",
                "nombre": "MORELOS"
            },
            {
                "clave": "NAY",
                "nombre": "NAYARIT"
            },
            {
                "clave": "NL",
                "nombre": "NUEVO LEON"
            },
            {
                "clave": "OAX",
                "nombre": "OAXACA"
            },
            {
                "clave": "PUE",
                "nombre": "PUEBLA"
            },
            {
                "clave": "QR",
                "nombre": "QUINTANA ROO"
            },
            {
                "clave": "QRO",
                "nombre": "QUERETARO"
            },
            {
                "clave": "SIN",
                "nombre": "SINALOA"
            },
            {
                "clave": "SLP",
                "nombre": "SAN LUIS POTOSI"
            },
            {
                "clave": "SON",
                "nombre": "SONORA"
            },
            {
                "clave": "TAB",
                "nombre": "TABASCO"
            },
            {
                "clave": "TLX",
                "nombre": "TLAXCALA"
            },
            {
                "clave": "TMS",
                "nombre": "TAMAULIPAS"
            },
            {
                "clave": "VER",
                "nombre": "VERACRUZ"
            },
            {
                "clave": "YUC",
                "nombre": "YUCATAN"
            },
            {
                "clave": "ZAC",
                "nombre": "ZACATECAS"
            }
        ]

        var $select = $('#state');

        $.each(states, function(clave, nombre) {
            $select.append('<option value=' + nombre.nombre + '>' + nombre.nombre + '</option>');
        });

        bsCustomFileInput.init();

        $('#start-date').datetimepicker({
            autoclose: true,
            locale: 'es-mx',
            format: 'DD.MM.YYYY',
            defaultDate: new Date()
        })

        $('#birthday').datetimepicker({
            autoclose: true,
            locale: 'es-mx',
            format: 'DD.MM.YYYY',
        })

        $('#overlay').hide();
    });
</script>
@endsection