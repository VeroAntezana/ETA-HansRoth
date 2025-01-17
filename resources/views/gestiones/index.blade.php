@extends('adminlte::page')

@section('title', 'gestiones')

@section('content')
    <section class="content">
        <div class="container-fluid p-4">
            <div class="card">
                <div class="card-header justify-content-between">
                    <div class="row justify-content-between">
                        <div class="col-xs-4 my-auto">
                            <h3 class="card-title my-auto">
                                <strong>LISTA DE GESTIONES</strong>
                                <a class="btn" href="{{ route('gestiones.index') }}">
                                    <i class="fas fa-sync fa-md fa-fw"></i>
                                </a>
                            </h3>
                        </div>

                        <div class="col-xs">
                            <button data-toggle="modal" data-target="#formCreateModal" class="btn btn-primary"
                                type="button">Nuevo</button>
                        </div>
                        <div class="modal fade" id="formCreateModal" tabindex="-1" aria-labelledby="formCreateLabel"
                            aria-hidden="true">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="formCreateLabel">Registrar nuevo </h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        @include('partials.gestiones.form_create')
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="card-body p-0">
                    <table class="table table-hover table-head-fixed">
                        <thead class="table-light ">
                            <tr>
                                <th>ID</th>
                                <th>DESCRIPCION</th>
                                <th>FECHA INICIO</th>
                                <th>FECHA FIN</th>
                                <th>ACCIONES</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($gestiones as $gestion)
                                <tr>
                                    <td>{{ $gestion->gestion_id }}</td>
                                    <td>{{ $gestion->descripcion }}</td>
                                    <td>{{ $gestion->fecha_inicio }}</td>
                                    <td>{{ $gestion->fecha_fin }}</td>
                                    <td>
                                        <button type="button" class="btn btn-warning btn-sm" onclick="editGestion({{ $gestion->gestion_id }})">Editar</button>
                                        <!-- Formulario de eliminación directamente en la tabla -->
                                        <form action="{{ route('gestiones.destroy', $gestion->gestion_id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('¿Estás seguro de que deseas eliminar esta gestion?')">Eliminar</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Modal de edición -->
        <div class="modal fade" id="formEditModal" tabindex="-1" aria-labelledby="formEditLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="formEditLabel">Editar Gestión</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body" id="editModalBody">
                        <!-- Aquí se cargará el formulario de edición -->
                    </div>
                </div>
            </div>
        </div>
    </section>
@stop

@section('js')
    <script>
        function editGestion(id) {
            $.get('/gestiones/' + id + '/edit', function(data) {
                $('#editModalBody').html(data);
                $('#formEditModal').modal('show');
            });
        }

        function confirmarEliminacion(gestionId) {
            if (confirm('¿Estás seguro de que deseas eliminar este nivel?')) {
                // Si el usuario hace clic en "Aceptar", redirige al controlador para eliminar el nivel
                window.location.href = '{{ url("/gestiones") }}/' + gestionId;
            }
        }
    </script>
@stop
