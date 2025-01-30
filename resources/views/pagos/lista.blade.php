@extends('adminlte::page')

@section('title', 'pagos')

@section('content')
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

                <table class="table table-hover table-head-fixed">
                    <thead class="table-light ">
                        <tr>
                            <th>ID</th>
                            <th>NOMBRES</th>
                            <th>APELLIDOS</th>
                            <th>MODULOS PAGADOS</th>
                            <th>CARRERA-NIVEL</th>
                            <th>OPCIONES</th>

                        </tr>
                    </thead>


                    <tbody>
                        @if(count($pagoConDetalles) > 0)
                            @foreach($pagoConDetalles as $pago)
                                <tr>
                                    <td>{{ $pago['id'] }}</td>
                                    <td>{{ $pago['estudiante']->nombre }}</td>
                                    <td>{{ $pago['estudiante']->apellidos }}</td>
                                    <td>
                                        @foreach($pago['meses_pagados'] as $mes)
                                            {{ $mes }}
                                            @if (!$loop->last), @endif
                                        @endforeach
                                    </td>
                                    <td>
                                        @if($pago['carrera'] && $pago['nivel'])
                                            {{ $pago['carrera']->nombre . ' - ' . $pago['nivel']->nombre }}
                                        @else
                                            Sin carrera
                                        @endif
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-warning btn-sm">Editar</button>
                                        <a href="{{ route('pagos.show', ['pago' => $pago['id']]) }}" class="btn btn-success btn-sm">Ver</a>
                                        <!-- Formulario de eliminación directamente en la tabla -->
                                        <form action="{{ route('pagos.destroy', ['pago' => $pago['id']]) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm"
                                                onclick="return confirm('¿Estás seguro de que deseas eliminar este pago?')">Eliminar</button>
                                        </form>
                                    </td>
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
                window.location.href = '{{ url("pagos") }}/' + carreraId;
            }
        }
</script>
@stop
