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
                            type="button">Matricular Nuevo Estudiante</button>
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


                    <div class="col-xs">
                        <button data-toggle="modal" data-target="#matricularEstudianteModal" class="btn btn-primary" type="button">
                            Matricular Antiguo Estudiante
                        </button>
                    </div>

                    <div class="modal fade" id="matricularEstudianteModal" tabindex="-1" aria-labelledby="matricularEstudianteLabel" aria-hidden="true">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="matricularEstudianteLabel">Matricular Antiguo Estudiante</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">

                                    @include('partials.estudiantes.form_matricularAntiguoEstudiante')
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
                            @foreach ($estudiantes as $estudiante)
                            <tr>
                                <td>{{ $estudiante->estudiante_id }}</td>
                                <td>{{ $estudiante->nombre }}</td>
                                <td>{{ $estudiante->apellidos }}</td>
                                <td>{{ $estudiante->ci }}</td>
                                <td>
                                    @if ($estudiante->carreras->isNotEmpty())
                                    @foreach ($estudiante->carreras as $carrera)
                                    {{ $carrera->nombre }} - {{ optional($carrera->nivel)->nombre }}<br>
                                    @endforeach
                                    @else
                                    Sin carrera asignada
                                    @endif
                                </td>
                                <td>
                                    <button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#showModal{{ $estudiante->estudiante_id }}">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <div class="modal fade" id="showModal{{ $estudiante->estudiante_id }}" tabindex="-1">
                                        <div class="modal-dialog modal-lg">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Información del Estudiante</h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    @include('partials.estudiantes.form_show', ['estudiante' => $estudiante])
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <button type="button" class="btn btn-warning btn-sm" data-toggle="modal" data-target="#editModal{{ $estudiante->estudiante_id }}">Editar</button>
                                    <div class="modal fade" id="editModal{{ $estudiante->estudiante_id }}" tabindex="-1">
                                        <div class="modal-dialog modal-lg">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Editar Estudiante</h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    @include('partials.estudiantes.form_edit', ['estudiante' => $estudiante])
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Formulario de eliminación directamente en la tabla -->
                                    <button type="button" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#deleteModal{{ $estudiante->estudiante_id }}">
                                        Eliminar
                                    </button>

                                    <!-- Eliminar -->
                                    <div class="modal fade" id="deleteModal{{ $estudiante->estudiante_id }}" tabindex="-1">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Confirmar Eliminación</h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <p>¿Está seguro que desea eliminar al estudiante {{ $estudiante->nombre }} {{ $estudiante->apellidos }}?</p>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                                                    <form action="{{ route('estudiantes.destroy', $estudiante->estudiante_id) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger">Eliminar</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            @endforeach

                        </tbody>
                    </table>

                    <div class="d-flex justify-content-sm-end mt-5">
                        <nav aria-label="Page navigation example">
                            <ul class="pagination">
                                <!-- Previous Page Link -->
                                @if ($estudiantes->onFirstPage())
                                <li class="page-item disabled"><span class="page-link">Previous</span></li>
                                @else
                                <li class="page-item"><a class="page-link" href="{{ $estudiantes->previousPageUrl() }}">Previous</a></li>
                                @endif

                                <!-- Pagination Elements -->
                                @foreach ($estudiantes->links()->elements as $element)
                                <!-- "Three Dots" Separator -->
                                @if (is_string($element))
                                <li class="page-item disabled"><span class="page-link">{{ $element }}</span></li>
                                @endif

                                <!-- Array Of Links -->
                                @if (is_array($element))
                                @foreach ($element as $page => $url)
                                @if ($page == $estudiantes->currentPage())
                                <li class="page-item active"><span class="page-link">{{ $page }}</span></li>
                                @else
                                <li class="page-item"><a class="page-link" href="{{ $url }}">{{ $page }}</a></li>
                                @endif
                                @endforeach
                                @endif
                                @endforeach

                                <!-- Next Page Link -->
                                @if ($estudiantes->hasMorePages())
                                <li class="page-item"><a class="page-link" href="{{ $estudiantes->nextPageUrl() }}">Next</a></li>
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
    const inputEstudiante = document.getElementById('buscar_estudiante');
    const sugerencias = document.getElementById('sugerencias');
    const inputNombreApellido = document.getElementById('nombre_apellido');
    const inputCI = document.getElementById('carnetCI');
    const selectCarrera = document.getElementById('matriculaCarrera');
    const estudianteID = document.getElementById('estudiante_id')

    const buscarEstudiante = async (texto) => {
        try {
            const resultado = await fetch(`http://127.0.0.1:8000/api/estudiantes/create/buscar?q=${texto}`);
            if (!resultado.ok) {
                throw new Error(`Error en la solicitud: ${resultado.status}`);
            }
            return await resultado.json();
        } catch (error) {
            console.error('Hubo un problema con la solicitud fetch:', error);
            return [];
        }
    };

    const mostrarSugerencias = (estudiantes) => {
        sugerencias.innerHTML = '';
        if (estudiantes.length === 0) {
            sugerencias.style.display = 'none';
            return;
        }

        estudiantes.forEach((estudiante) => {
            const div = document.createElement('div');
            div.classList.add('dropdown-item');
            div.textContent = `${estudiante.nombre_completo} (CI: ${estudiante.ci})`;
            div.onclick = () => seleccionarEstudiante(estudiante);
            sugerencias.appendChild(div);
        });

        sugerencias.style.display = 'block';
    };

    const seleccionarEstudiante = (estudiante) => {
        // Rellenar campos con los datos del estudiante
        inputNombreApellido.value = estudiante.nombre_completo;
        inputCI.value = estudiante.ci;
        estudianteID.value = estudiante.id_estudiante;

        // Eliminar carreras en las que ya esté matriculado
        estudiante.carreras.forEach((carrera) => {
            const option = Array.from(selectCarrera.options).find(
                (opt) => opt.textContent.includes(carrera.nombre_carrera)
            );
            if (option) {
                option.remove();
            }
        });

        // Ocultar las sugerencias
        sugerencias.style.display = 'none';
    };

    // Función debounce
    const debounce = (func, delay) => {
        let timer;
        return function(...args) {
            clearTimeout(timer); // Cancela el temporizador anterior
            timer = setTimeout(() => func.apply(this, args), delay); // Configura un nuevo temporizador
        };
    };

    const manejarCambio = async (e) => {
        const texto = e.target.value;
        if (texto.length >= 2) {
            const estudiantes = await buscarEstudiante(texto);
            mostrarSugerencias(estudiantes);
        } else {
            sugerencias.style.display = 'none';
        }
    };

    // Aplica debounce a manejarCambio con un retraso de 300ms
    inputEstudiante.addEventListener('keyup', debounce(manejarCambio, 300));
</script>
<script>
    function confirmarEliminacion(estudianteId) {
        if (confirm('¿Estás seguro de que deseas eliminar esta carrera?')) {
            // Si el usuario hace clic en "Aceptar", redirige al controlador para eliminar el nivel
            window.location.href = '{{ url('
            estudiantes ') }}/' + carreraId;
        }
    }
</script>
@stop