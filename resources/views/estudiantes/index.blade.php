@extends('adminlte::page')

@section('title', 'estudiantes')

@section('content')
<section class="content">
    <div class="container-fluid p-4">
        <div class="card">
            <div class="card-header justify-content-between">
                <div class="row justify-content-between">
                    <div class="col-xs-4 my-auto">
                        <h3 class="card-title my-auto">
                            <strong>LISTA DE ESTUDIANTES GENERAL</strong>
                            <a class="btn" href="{{ route('estudiantes.index') }}">
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
                                    <h5 class="modal-title" id="fomrCreateLabel">Registrar nuevo </h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    @include('partials.estudiantes.form_create')
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
                            <th>NOMBRES</th>
                            <th>APELLIDOS</th>
                            <th>CARNET <br>IDENTIDAD</th>
                            <th>CARRERA-NIVEL</th>
                            <th>OPCIONES</th>

                        </tr>
                    </thead>


                    <tbody>
                        @foreach($estudiantes as $estudiante)
                        <tr>
                            <td>{{ $estudiante->id }}</td>
                            <td>{{ $estudiante->nombre }}</td>
                            <td>{{ $estudiante->apellidos }}</td>
                            <td>{{ $estudiante->carnet }}</td>
                            <td>
                                  {{ optional($estudiante->carreraNivel->carrera)->nombre }} - {{ optional($estudiante->carreraNivel->nivel)->nombre }}
                            </td>
                            
                            <td>
                                <button type="button" class="btn btn-warning btn-sm">Editar</button>
                                <!-- Formulario de eliminación directamente en la tabla -->
                                <form action="{{ route('estudiantes.destroy', $estudiante->id) }}" method="POST"
                                    class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm"
                                        onclick="return confirm('¿Estás seguro de que deseas eliminar este estudiante?')">Eliminar</button>
                                </form>
                                
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

        </div>
    </div>

</section>
@stop

@section('js')
<script>
    function confirmarEliminacion(estudianteId) {
            if (confirm('¿Estás seguro de que deseas eliminar esta carrera?')) {
                // Si el usuario hace clic en "Aceptar", redirige al controlador para eliminar el nivel
                window.location.href = '{{ url("estudiantes") }}/' + carreraId;
            }
        }
</script>
@stop