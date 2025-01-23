@extends('adminlte::page')

<head>
    <style>
        /* Estilo para la lista de sugerencias */
        #sugerencias {
            display: none;
            position: absolute;
            background-color: white;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            z-index: 1000;
            top: 100%;
            /* Aparece justo debajo del input */
            left: 0;
            width: 100%;
            /* Ajusta al ancho del input */
            max-height: 200px;
            overflow-y: auto;
        }

        #sugerencias a {
            display: block;
            padding: 8px 10px;
            text-decoration: none;
            color: #333;
        }

        #sugerencias a:hover {
            background-color: #f1f1f1;
            color: #000;
        }

        /* Ajustes para el selector de meses */
        .mes-lista {
            display: none;
            position: absolute;
            background-color: #fff;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            z-index: 1000;
            top: 100%;
            /* Aparece debajo del input */
            left: 0;
            padding: 10px;
            grid-template-columns: repeat(3, 1fr);
            /* Más columnas para distribuir mejor */
            gap: 10px;
        }

        .mes-lista label {
            display: block;
            padding: 5px;
            font-size: 14px;
            cursor: pointer;
            text-align: center;
            border: 1px solid #ddd;
            border-radius: 5px;
            background-color: #f9f9f9;
            transition: all 0.3s ease;
        }

        .mes-lista label:hover {
            background-color: #e0e0e0;
        }
    </style>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css">
</head>

@section('content')
    <section class="content">
        <div class="container mt-5">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header text-center bg-primary text-white">Recibo de Pagos</div>
                        <div class="card-body">
                            <form action="{{ route('pagos.store') }}" method="post" autocomplete="off">
                                @csrf
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="search">Nombre:</label>
                                        <input type="text" id="buscar_estudiante" name="search" placeholder="Buscar..."
                                            class="form-control" onfocus="this.value=''">

                                        <div id="sugerencias" class="dropdown-menu"></div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="carreraNivel">Carrera:</label>
                                        <select name="matricula_id" id="matriculaCarrera" class="form-control select2">
                                            <!-- Opciones generadas dinámicamente -->
                                        </select>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <label for="concepto">Concepto:</label>
                                        <input type="text" class="form-control" id="concepto" name="concepto" required>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label for="fecha">Fecha:</label>
                                        <input type="datetime-local" name="fecha" class="form-control"
                                            value="{{ old('fecha', \Carbon\Carbon::now('America/La_Paz')->format('Y-m-d\TH:i')) }}">
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label for="monto">Monto:</label>
                                        <input type="text" class="form-control" id="monto" name="monto" required>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12 mb-3">
                                        <label for="mes_pago">Meses a Pagar:</label>
                                        <div class="mes-selector" onclick="toggleMeses()">
                                            <input type="text" class="form-control" id="mes_pago" name="mes_pago[]"
                                                readonly>
                                            <div class="mes-lista">
                                                @foreach (['Mod 1', 'Mod 2', 'Mod 3', 'Mod 4', 'Mod 5', 'Mod 6', 'Mod 7', 'Mod 8', 'Mod 9', 'Mod 10', 'Mod 11', 'Mod 12'] as $mes)
                                                    <label>
                                                        <input type="checkbox" name="meses[]" value="{{ $mes }}">
                                                        {{ $mes }}
                                                    </label>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="text-center">
                                    <button type="submit" class="btn btn-primary">Generar Recibo</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@stop


@section('js')
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>

    <script>
        $(document).ready(function() {
            $(".mes-lista").hide();
        });

        function toggleMeses() {
            $(".mes-lista").toggle();
        }

        $("input[name='meses[]']").on("change", function() {
            var selectedMonths = [];
            $("input[name='meses[]']:checked").each(function() {
                selectedMonths.push($(this).val());
            });
            $("#mes_pago").val(selectedMonths.join(', '));
        });

        $(document).on("click", function(event) {
            if (!$(event.target).closest('.mes-selector').length) {
                $(".mes-lista").hide();
            }
        });
    </script>

    <script>
        $('#search').on('keyup', function() {
            var query = $(this).val();
            if (query.trim() == '') {
                $('#search_list').html('');
                return;
            }
            $.ajax({
                url: "search",
                type: "GET",
                data: {
                    'search': query
                },
                dataType: 'json',
                success: function(data) {
                    var output = '';
                    if (data.length > 0) {
                        $.each(data, function(index, row) {
                            output += '<tr data-estudiante-id="' + row.id + '">';
                            output += '<td>' + row.nombre + '</td>';
                            output += '<td>' + row.apellidos + '</td>';
                            output += '</tr>';
                        });
                    } else {
                        output = 'No results';
                    }
                    $('#search_list').html(output);
                }
            });
        });
    </script>

    <script>
        $(document).on('click', '#search_list tr', function() {
            var nombre = $(this).find('td:first').text();
            var apellidos = $(this).find('td:eq(1)').text();
            var estudianteId = $(this).data('estudiante-id');
            $('#estudiante_id').val(estudianteId);

            $('#search').val(nombre + ' ' + apellidos);

            $('#search_list').html('');
            $.ajax({
                url: "{{ url('getEstudianteInfo') }}/" + estudianteId,
                type: "GET",
                dataType: 'json',
                success: function(data) {
                    if (data.success) {
                        $('#carreraNivel').val(data.carreraNivel);
                        var selectCarrera = $('#matriculaCarrera');
                        selectCarrera.empty(); // Limpiar opciones actuales
                        data.carreras.forEach(function(carrera) {
                            selectCarrera.append('<option value="' + carrera.matricula_id +
                                '">' + carrera.nombre_carrera + '</option>');
                        });
                    } else {
                        console.error('Error al obtener la información del estudiante.');
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error en la solicitud AJAX:', error);
                }
            });
        });
    </script>

    <script>
        const inputEstudiante = document.getElementById('buscar_estudiante');
        const sugerencias = document.getElementById('sugerencias');
        const inputNombreApellido = document.getElementById('buscar_estudiante');
        const selectCarrera = document.getElementById('matriculaCarrera');
        const estudianteID = document.getElementById('matricula_id');

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
            inputNombreApellido.value = estudiante.nombre_completo;
            selectCarrera.innerHTML = '';

            estudiante.carreras.forEach((carrera) => {
              
                // Verifica si la carrera ya está en el select
                const optionExists = Array.from(selectCarrera.options).some(
                    (option) => option.value === carrera.id
                );

                if (!optionExists) {
                    const option = document.createElement('option');
                    option.value = carrera.matriculas[0].id_matricula; // Usamos el id de la carrera como value
                    option.textContent = carrera.nombre_carrera + '-' + carrera
                    .nivel; // El nombre de la carrera como texto visible
                    selectCarrera.appendChild(option);
                }
            });

            sugerencias.style.display = 'none';
        };

        const debounce = (func, delay) => {
            let timer;
            return function(...args) {
                clearTimeout(timer);
                timer = setTimeout(() => func.apply(this, args), delay);
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

        inputEstudiante.addEventListener('keyup', debounce(manejarCambio, 300));
    </script>

@stop
