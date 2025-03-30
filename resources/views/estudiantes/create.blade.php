@extends('adminlte::page')

@section('title', 'estudiantes')

@section('content')
    <section class="content">
        <div class="container-fluid p-4">
            <div class="card">
                <div class="card-header">
                    <div class="row justify-content-between">
                        <button class="btn btn-primary" onclick="toggleForm('form-estudiante')">Matricular Nuevo Estudiante</button>
                        <button class="btn btn-secondary" onclick="toggleForm('form-matricula')">Matricular Antiguo Estudiante</button>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Formulario de Registro de Estudiante -->
                    <form id="form-estudiante" action="{{ route('estudiantes.store') }}" method="post" autocomplete="off">
                        @csrf
                        <div class="row">
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label for="nombre">NOMBRE </label>
                                    <input type="text" name="nombre" class="form-control my-colorpicker1"
                                        value="{{ old('nombre') }}" required>

                                    @error('nombre')
                                        <small class="text-danger">*{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label for="apellidos">APELLIDOS</label>
                                    <input type="text" name="apellidos" class="form-control my-colorpicker1"
                                        value="{{ old('apellidos') }}" required>

                                    @error('apellidos')
                                        <small class="text-danger">*{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>

                        </div>
                        <div class="row">


                            <div class="col-md-5">
                                <div class="form-group">
                                    <label for="fecha_nacimiento">FECHA NACIMIENTO</label>
                                    <input type="date" name="fecha_nacimiento" class="form-control my-colorpicker1"
                                        value="{{ old('fecha_nacimiento') }}" required>
                                    @error('fecha_nacimiento')
                                        <small class="text-danger">*{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>


                            <div class="col-md-5">
                                <div class="form-group">
                                    <label for="carnet">CARNET DE IDENTIDAD</label>
                                    <input name="ci" class="form-control my-colorpicker1" type="number"
                                        value="{{ old('carnet') }}" step="1" min="0" value="0" required>

                                    @error('carnet')
                                        <small class="text-danger">*{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>


                            <div class="col-md-5">
                                <div class="form-group">
                                    <label for="carnet">SEXO</label>
                                    <select id="sexo" name="sexo" class="form-control">
                                        <option value="">Seleccione</option>
                                        <option value="M">Masculino</option>
                                        <option value="F">Femenino</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label for="carrera">SELECCIONAR CARRERA Y NIVEL</label>
                                    <select name="carrera_id" class="form-control select2" style="width: 100%;">
                                        @foreach ($carreras as $carrera)
                                            <option value="{{ $carrera->carrera_id }}">
                                                {{ $carrera->nombre }} - {{ $carrera->nivel->nombre }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label for="carrera">GESTION</label>
                                    <select name="gestion_id" class="form-control select2" style="width: 100%;">
                                        @foreach ($gestiones as $gestion)
                                            <option value="{{ $gestion->gestion_id }}">{{ $gestion->descripcion }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <hr>
                        <div class="d-flex justify-content-end">
                            <a type="button" class="btn btn-danger mr-2"
                                href="{{ route('estudiantes.index') }}">Cancelar</a>
                            <button type="submit" class="btn btn-info">Guardar</a>
                        </div>

                    </form>

                    <!-- Formulario de Matriculación de Estudiante -->
                    <form id="form-matricula" action="{{route('estudiantes.matricular')}}" method="post" autocomplete="off" style="display: none;">
                        @csrf
                        <div class="row">
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label for="buscar_estudiante">Buscar Estudiante (Nombre o CI)</label>
                                    <input type="text" id="buscar_estudiante" class="form-control"
                                        placeholder="Escribe el nombre o CI">
                                    <div id="sugerencias" class="dropdown-menu" style="display: none; position: absolute;">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label for="nombre_apellido">NOMBRE Y APELLIDO</label>
                                    <input type="text" name="nombre_apellido" id="nombre_apellido"
                                        class="form-control" placeholder="Nombre y Apellido" required disabled>
                                </div>
                            </div>
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label for="ci">CARNET DE IDENTIDAD</label>
                                    <input name="ci" id="carnetCI" class="form-control" type="number"
                                        placeholder="Carnet de Identidad" required disabled>
                                </div>
                            </div>
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label for="carrera">SELECCIONAR CARRERA Y NIVEL</label>
                                    <select name="carrera_id" id="matriculaCarrera" class="form-control select2"
                                        style="width: 100%;">
                                        @foreach ($carreras as $carrera)
                                            <option value="{{ $carrera->carrera_id }}">
                                                {{ $carrera->nombre }} - {{ $carrera->nivel->nombre }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label for="gestion">GESTIÓN</label>
                                    <select name="gestion_id" class="form-control select2" style="width: 100%;">
                                        @foreach ($gestiones as $gestion)
                                            <option value="{{ $gestion->gestion_id }}">{{ $gestion->descripcion }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <input type="number"  name="estudiante_id" hidden value="" id="estudiante_id">
                        <hr>
                        <div class="d-flex justify-content-end">
                            <a type="button" class="btn btn-danger mr-2" href="{{ route('estudiantes.index') }}">Cancelar</a>
                            <button type="submit" class="btn btn-info">Matricular</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <script>
        function toggleForm(formId) {
            const forms = ["form-estudiante", "form-matricula"];
            forms.forEach(id => {
                const form = document.getElementById(id);
                if (form) {
                    form.style.display = id === formId ? "block" : "none";
                }
            });
        }
    </script>
    <script>
        const inputEstudiante = document.getElementById('buscar_estudiante');
        const sugerencias = document.getElementById('sugerencias');
        const inputNombreApellido = document.getElementById('nombre_apellido');
        const inputCI = document.getElementById('carnetCI');
        const selectCarrera = document.getElementById('matriculaCarrera');
        const estudianteID = document.getElementById('estudiante_id')

        const buscarEstudiante = async (texto) => {
            try {
                const url = `{{ config('app.url') }}/api/estudiantes/create/buscar?q=${texto}`;
                const resultado = await fetch(url);
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


@stop
