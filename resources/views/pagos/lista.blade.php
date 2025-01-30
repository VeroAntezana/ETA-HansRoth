@extends('adminlte::page')

@section('title', 'pagos')

@section('content')
    <style>
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
                                <a class="btn" href="{{ route('pagos.lista') }}">
                                    <i class="fas fa-sync fa-md fa-fw"></i>
                                </a>
                            </h3>
                        </div>

                        <div class="col-xs">
                            <a href="{{ route('pagos.index') }}" class="btn btn-primary">Nuevo</a>
                        </div>


                    </div>

                </div>

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
                                    <tr>
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
@stop
