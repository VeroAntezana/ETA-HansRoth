@extends('adminlte::page')

@section('title', 'Carreras')

@section('content')
<section class="content">
    <div class="container-fluid p-4">
        <div class="card">
            <div class="card-header justify-content-between">
                <div class="row justify-content-between">
                    <div class="col-xs-4 my-auto">
                        <h3 class="card-title my-auto">
                            <strong>LISTA DE CARRERAS</strong>
                            <a class="btn" href="{{ route('carreras.index') }}">
                                <i class="fas fa-sync fa-md fa-fw"></i>
                            </a>
                        </h3>
                    </div>

                    <div class="col-xs">
                        <button data-toggle="modal" data-target="#formCreateModal" class="btn btn-primary" type="button">Nuevo</button>
                    </div>
                    <div class="modal fade" id="formCreateModal" tabindex="-1" aria-labelledby="formCreateLabel" aria-hidden="true">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="formCreateLabel">Registrar nueva carrera</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    @include('partials.carreras.form_create')
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-body p-0">
                <table class="table table-hover table-head-fixed">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>NOMBRE</th>
                            <th>NIVEL</th>
                            <th>DURACION</th>
                            <th>OPCIONES</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($carreras as $carrera)
                        <tr>
                            <td>{{ $carrera->carrera_id }}</td>
                            <td>{{ $carrera->nombre }}</td>
                            <td>
                                @if($carrera->nivel)
                                {{ $carrera->nivel->nombre }}
                                @else
                                Sin nivel asignado
                                @endif
                            </td>
                            <td>{{ $carrera->duracion_meses }} meses</td>
                            <td>
                                <button type="button" class="btn btn-warning btn-sm" data-toggle="modal" data-target="#editModal{{ $carrera->carrera_id }}">Editar</button>
                                <div class="modal fade" id="editModal{{ $carrera->carrera_id }}" tabindex="-1">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Editar Carrera</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                @include('partials.carreras.form_edit', ['carrera' => $carrera])
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <form action="{{ route('carreras.destroy', $carrera->carrera_id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('¿Estás seguro de que deseas eliminar esta carrera?')">
                                        Eliminar
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach

                    </tbody>
                </table>
                <!-- Aquí se añaden los enlaces de paginación con estilo -->
                <div class="d-flex justify-content-sm-end mt-5">
                    <nav aria-label="Page navigation example">
                        <ul class="pagination">
                            <!-- Previous Page Link -->
                            @if ($carreras->onFirstPage())
                            <li class="page-item disabled"><span class="page-link">Previous</span></li>
                            @else
                            <li class="page-item"><a class="page-link" href="{{ $carreras->previousPageUrl() }}">Previous</a></li>
                            @endif

                            <!-- Pagination Elements -->
                            @foreach ($carreras->links()->elements as $element)
                            <!-- "Three Dots" Separator -->
                            @if (is_string($element))
                            <li class="page-item disabled"><span class="page-link">{{ $element }}</span></li>
                            @endif

                            <!-- Array Of Links -->
                            @if (is_array($element))
                            @foreach ($element as $page => $url)
                            @if ($page == $carreras->currentPage())
                            <li class="page-item active"><span class="page-link">{{ $page }}</span></li>
                            @else
                            <li class="page-item"><a class="page-link" href="{{ $url }}">{{ $page }}</a></li>
                            @endif
                            @endforeach
                            @endif
                            @endforeach

                            <!-- Next Page Link -->
                            @if ($carreras->hasMorePages())
                            <li class="page-item"><a class="page-link" href="{{ $carreras->nextPageUrl() }}">Next</a></li>
                            @else
                            <li class="page-item disabled"><span class="page-link">Next</span></li>
                            @endif
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </div>

</section>

@stop

@section('js')
<script>
    function confirmarEliminacion(carreraId) {
        if (confirm('¿Estás seguro de que deseas eliminar esta carrera?')) {
            window.location.href = '{{ url("carreras") }}/' + carreraId;
        }
    }
</script>
@stop