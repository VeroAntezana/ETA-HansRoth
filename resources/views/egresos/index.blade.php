@extends('adminlte::page')

@section('content')
<section class="content">
    <div class="container-fluid p-4">
        <div class="card">
            <div class="card-header justify-content-between">
                <div class="row justify-content-between align-items-center">
                    <div class="col">
                        <h3 class="card-title m-0">
                            <strong>LISTA DE EGRESOS</strong>
                            <a class="btn" href="{{ route('egresos.index') }}">
                                <i class="fas fa-sync fa-md fa-fw"></i>
                            </a>
                        </h3>
                    </div>
                    <div class="col text-right">
                        <a href="{{ route('egresos.create') }}" class="btn btn-primary">Nuevo</a>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-body text-center">
                    <h4>Total de Egresos</h4>
                    <h2 class="text-danger"><strong>Bs {{ number_format($totalEgresos, 2) }}</strong></h2>
                    <p>Total acumulado de egresos</p>
                </div>
            </div>

            <!-- Filtro de fechas -->
            <div class="card-body">
                <form method="GET" action="{{ route('egresos.index') }}" class="form-inline">
                    <div class="form-group mx-2">
                        <label for="fecha_inicio">Fecha Inicio: </label>
                        <input type="date" class="form-control ml-2" name="fecha_inicio" value="{{ request('fecha_inicio') }}">
                    </div>
                    <div class="form-group mx-2">
                        <label for="fecha_fin">Fecha Fin: </label>
                        <input type="date" class="form-control ml-2" name="fecha_fin" value="{{ request('fecha_fin') }}">
                    </div>
                    <button type="submit" class="btn btn-success mx-2">Filtrar</button>
                </form>
            </div>

            <div class="card-body p-0">
                <table class="table table-hover table-head-fixed">
                    <thead class="table-light">
                        <tr>
                            <th>#-Recibo</th>
                            <th>Fecha</th>
                            <th>Nombre</th>
                            <th>Concepto</th>
                            <th>Monto</th>
                            <th>OPCIONES</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($egresos as $index => $egreso)
                        <tr>
                            <td>{{ $egreso->egreso_id}}</td>
                            <td>{{ \Carbon\Carbon::parse($egreso->fecha)->format('d/m/Y H:i') }}</td>
                            <td>{{ $egreso->nombre }}</td>
                            <td>{{ $egreso->concepto }}</td>
                            <td>Bs {{ number_format($egreso->monto, 2) }}</td>
                            <!-- En la sección de opciones de la tabla -->
                            <td>
                                <button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#showModal{{ $egreso->egreso_id }}">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <div class="modal fade" id="showModal{{ $egreso->egreso_id }}" tabindex="-1">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Detalles del Egreso</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                @include('partials.egresos.form_show', ['egreso' => $egreso])
                                            </div>
                                        </div>
                                    </div>
                                </div>


                                <button type="button" class="btn btn-warning btn-sm" data-toggle="modal" data-target="#editModal{{ $egreso->egreso_id }}">Editar </button>
                                <div class="modal fade" id="editModal{{ $egreso->egreso_id }}" tabindex="-1">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Editar Egreso</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                @include('partials.egresos.form_edit', ['egreso' => $egreso, 'gestiones' => $gestiones])
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <form action="{{ route('egresos.destroy', $egreso) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('¿Estás seguro de que deseas eliminar este resgistro?')">
                                        Eliminar
                                    </button>

                                </form>

                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center">No se encontraron egresos.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>
@stop