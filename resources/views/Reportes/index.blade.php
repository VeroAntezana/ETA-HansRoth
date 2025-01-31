@extends('adminlte::page')

@section('title', 'pagos')

@section('content')
    <section class="content">
        <div class="container-fluid p-4">
            <div class="card shadow-sm">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title mb-0 font-weight-bold">
                        <strong>Total en Caja</strong>
                    </h3>
                    <a class="btn btn-link" href="{{ route('pagos.lista') }}">
                        <i class="fas fa-sync fa-lg"></i>
                    </a>
                </div>
                <div class="card-body d-flex justify-content-center align-items-center flex-column">
                    <h4 class="text-success mb-3 font-weight-bold">
                        Bs {{ number_format($totalPagos, 2) -number_format($totalegresos, 2)  }} <!-- Formateo de moneda con 2 decimales -->
                    </h4>
                    <p class="text-muted">Total acumulado de pagos en caja</p>
                </div>
            </div>

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
                                <th>#</th>
                                <th>Fecha</th>
                                <th>Recibo</th>
                                <th>Detalles</th>
                                <th>Ingreso</th>
                                <th>OPCIONES</th>

                            </tr>
                        </thead>


                        <tbody>
                            @if (count($pagoConDetalles) > 0)
                                @foreach ($pagoConDetalles as $pago)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $pago['fecha']}}</td>
                                        <td>{{$pago['id']}}</td>
                                        <td>{{$pago['detalle']}}</td>
                                        <td>{{$pago['ingreso']}}</td>
                                        <td>

                                            <a href="{{ route('pagos.show', ['pago' => $pago['id']]) }}"
                                            class="btn btn-success btn-sm">Ver</a>
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
                window.location.href = '{{ url('pagos') }}/' + carreraId;
            }
        }
    </script>
@stop
