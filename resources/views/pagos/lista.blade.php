@extends('adminlte::page')

@section('title', 'pagos')

@section('content')
    <style>
        tr {
            transition: all 0.3s ease;
        }

        .checkbox-wrapper {
            width: 35px;
            height: 35px;
            border-radius: 5px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 500;
            font-size: 0.9em;
            transition: all 0.3s ease;
        }

        .checkbox-wrapper.pagado {
            background-color: #28a745;
            color: white;
            box-shadow: 0 2px 5px rgba(40, 167, 69, 0.2);
        }

        .checkbox-wrapper.pendiente {
            background-color: #f8f9fa;
            border: 2px solid #dee2e6;
            color: #6c757d;
        }

        .badge {
            font-size: 0.85em;
            padding: 0.35em 0.65em;
        }
    </style>
    <section class="content">
        <div class="container-fluid p-4">
            <div class="card">
                <div class="card-header justify-content-between">
                    <div class="row justify-content-between">
                        <div class="col-xs-4 my-auto">
                            <h3 class="card-title my-auto">
                                <strong>LISTA DE PAGOS</strong>
                                <a class="btn" href="{{ route('pagos.lista', ['gestion_id' => optional($gestionActiva)->gestion_id]) }}">
                                    <i class="fas fa-sync fa-md fa-fw"></i>
                                </a>
                            </h3>

                        </div>
                        <div class="col-xs">
                            <form action="{{ route('pagos.lista', ['gestion_id' => optional($gestionActiva)->gestion_id]) }}" method="GET">
                                <select name="gestion_id" class="form-control select2" style="width: 220px;"
                                    onchange="this.form.submit()">
                                    @foreach ($gestiones as $gestion)
                                        <option value="{{ $gestion->gestion_id }}"
                                            {{ optional($gestionActiva)->gestion_id == $gestion->gestion_id ? 'selected' : '' }}>
                                            {{ $gestion->descripcion }}
                                        </option>
                                    @endforeach
                                </select>
                            </form>
                        </div>
                        <div class="col-xs">
                            <select name="carrera_id" id="filterCarrera" class="form-control select2" style="width: 100%;">
                                <option value="">Todas las carreras</option>
                                @foreach ($carreras as $carrera)
                                    <option value="{{ $carrera->carrera_id }}">
                                        {{ $carrera->nombre }} - {{ $carrera->nivel->nombre }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-xs">
                            <a href="#" class="btn btn-primary btn-descargar">Descargar Lista</a>
                        </div>
                        <div class="col-xs">
                            <a href="{{ route('pagos.index', ['gestion_id' => optional($gestionActiva)->gestion_id]) }}" class="btn btn-primary">Nuevo</a>
                        </div>


                    </div>

                </div>

                @if (!empty($gestionAlert))
                    <div class="alert alert-warning mt-2 mb-0 mx-3">
                        {{ $gestionAlert }}
                    </div>
                @endif

                <div class="card-body p-0">

                    <!-- Vista Blade -->
                    <table class="table table-hover table-head-fixed">
                        <thead class="table-light">
                            <tr>
                                <th>ID</th>
                                <th>NOMBRES</th>
                                <th>APELLIDOS</th>
                                <th>MODULOS PAGADOS</th>
                                <th>CARRERA-NIVEL</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (count($pagosAgrupados) > 0)
                                @foreach ($pagosAgrupados as $pago)
                                    @php
                                        $mesesPagados = $pago['meses_pagos'];
                                        $totalPagados = count($mesesPagados);
                                        $duracion = $pago['duracion_carrera'];
                                    @endphp
                                    <tr data-carrera-id="{{ $pago['carrera_id'] }}"
                                        data-pagado="{{ count($pago['meses_pagos']) < $pago['duracion_carrera'] ? 'incompleto' : 'completo' }}">
                                        <td>{{ $pago['matricula_id'] }}</td>
                                        <td>{{ $pago['nombre'] }}</td>
                                        <td>{{ $pago['apellidos'] }}</td>
                                        <td>
                                            <div class="d-flex flex-wrap gap-2 align-items-center">
                                                @for ($i = 1; $i <= $duracion; $i++)
                                                    <div class="position-relative">
                                                        <div
                                                            class="checkbox-wrapper {{ in_array($i, $mesesPagados) ? 'pagado' : 'pendiente' }}">
                                                            {{ $i }}
                                                        </div>
                                                    </div>
                                                @endfor
                                            </div>

                                        </td>
                                        <td>{{ $pago['carrera_nivel'] }}</td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="6" class="text-center">No se encontraron pagos.</td>
                                </tr>
                            @endif
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
                window.location.href = '{{ url('pagos') }}/' + carreraId;
            }
        }
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const filterCarrera = document.getElementById('filterCarrera');

            filterCarrera.addEventListener('change', function() {
                const carreraId = this.value;
                const rows = document.querySelectorAll('tbody tr');

                rows.forEach(row => {
                    const rowCarreraId = row.dataset.carreraId;
                    const isIncompleto = row.dataset.pagado === 'incompleto';

                    // Mostrar solo si coincide con la carrera seleccionada y tiene pagos incompletos
                    const shouldShow = (carreraId === '' || rowCarreraId === carreraId);

                    row.style.display = shouldShow ? '' : 'none';
                });
            });
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Seleccionar el botón para descargar el PDF
            const downloadButton = document.querySelector('.btn-descargar');

            downloadButton.addEventListener('click', function() {
                // Crear un iframe para la impresión
                const iframe = document.createElement('iframe');
                iframe.style.position = 'absolute';
                iframe.style.top = '-9999px';
                document.body.appendChild(iframe);

                // Obtener los estilos actuales
                const styles = Array.from(document.styleSheets)
                    .map(sheet => {
                        try {
                            return Array.from(sheet.cssRules)
                                .map(rule => rule.cssText)
                                .join('\n');
                        } catch (e) {
                            return '';
                        }
                    })
                    .join('\n');

              
                const table = document.querySelector('.table');
                const iframeDocument = iframe.contentWindow.document;
                iframeDocument.open();
                iframeDocument.write(`
                <html>
                <head>
                    <title>Lista de Pagos</title>
                    <style>
                        ${styles} /* Incluir estilos actuales */
                    </style>
                </head>
                <body>
                    ${table.outerHTML}
                </body>
                </html>
            `);
                iframeDocument.close();

                // Esperar a que el contenido se cargue en el iframe
                iframe.onload = function() {
                    iframe.contentWindow.focus();
                    iframe.contentWindow.print();
                    document.body.removeChild(iframe);
                };
            });
        });
    </script>

@stop
