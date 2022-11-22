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
        @if ($message = Session::get('error'))
                <div class="alert alert-danger alert-dismissible text-justify" id="successMessage">
                    <button aria-hidden="true" class="close" data-dismiss="alert" type="button">&times;</button>
                    <h6><i class="icon fal fa-times-circle"></i>¡Error!</h6>
                    {{ $message }}<br>
                </div>
        @endif
        <div class="card card-outline card-reflex-blue">
            <div class="overlay" id="overlay">
                <i class="fal fa-2x fa-sync fa-spin"></i>
            </div>
            <div class="card-header">
                <div class="d-flex flex-wrap justify-content-between">
                    <h3 class="card-title my-auto">Usuarios</h3>
                    <a class="btn btn-sm btn-success" href="{{ route('users.create') }}">
                        <i class="fal fa-plus-circle mr-2"></i>Nuevo
                    </a>
                </div>
            </div>
            <div class="card-body">
                <table class="table table-valign-middle table-hover" id="data-table">
                    <thead class="saa-table-header">
                        <tr>
                            <th>#</th>
                            <th>Nombre</th>
                            <th>Email</th>
                            <th>Username</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div aria-hidden="true" aria-labelledby="delete-record" class="modal fade" id="delete-modal" data-backdrop="static" data-keyboard="false" role="dialog" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header bg-danger">
                <h4 class="modal-title">Eliminar Usuario</h4>
                <button aria-label="Close" class="close" data-dismiss="modal" title="Cerrar" type="button">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p class="text-justify">¿Está seguro que desea eliminar al usuario?</p>
            </div>
            <div class="modal-footer justify-content-between">
                <button class="btn btn-sm btn-secondary" data-dismiss="modal" type="button">
                    <i class="fal fa-times-circle mr-2"></i>Cancelar
                </button>
                <form autocomplete="off" id="delete-form" method="post">
                    @csrf
                    @method ('DELETE')
                    <button class="btn btn-sm btn-danger" type="submit">
                        <i class="fal fa-trash-alt mr-2"></i>Eliminar
                    </button>
                </form>
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
                    targets: [0, 1, 2, 3,4]
                },
                {
                    orderable: false,
                    searchable: false,
                    targets: 4
                }
            ],
            columns: [{
                    data: 'id'
                },
                {
                    data: 'name'
                },
                {
                    data: 'email'
                },
                {
                    data: 'username'
                },
                {
                    className: 'text-center',
                    defaultContent: '',
                    createdCell: function(td, cellData, rowData, row, col, data, id, meta) {
                        $(td).prepend(
                            `<div class="btn-group">
                                <button type="button" class="update btn btn-sm btn-warning edit-user mr-1 update" title="Editar">
                                    <i class="fal fa-pencil-alt"></i>
                                </button>
                                <button type="button" class="btn btn-sm btn-danger delete-user" title="Eliminar">
                                    <i class="fal fa-trash-alt"></i>
                                </button>
                            </div>`
                        );
                        $(td).addClass(data);
                    }
                }
            ],
            drawCallback: () => {
                $('.edit-user').on('click', (e) => {
                    let user = dt.row($(e.currentTarget).closest('tr')).data();
                    console.log(user);
                    editRecord(user);
                });


                
                $('.delete-user').on('click', (e) => {
                    let user = dt.row($(e.currentTarget).closest('tr')).data();
                    deleteRecord(user);
                });
            }
        });

        $('#overlay').hide();
    });

    function editRecord(user) {
        let route = "{{ route('users.edit', ['user' => 'id']) }}";
        route = route.replace('id', user.id);

        window.location.href = route;
    }

    function deleteRecord(user){
        let route = "{{ route('users.destroy', ['user' => 'id']) }}";
        route = route.replace('id', user.id);

        let modal = $('#delete-modal');

        let form = modal.find('form:first');
        form.attr('action', route);

        modal.modal('show');
    }

    setTimeout(function() {$('#successMessage').fadeOut('fast');}, 3000);

</script>
@endsection