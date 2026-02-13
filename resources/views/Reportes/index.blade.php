@extends('adminlte::page')

@section('title', 'Pagos')

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
                    @if (number_format($totalPagos - $totalegresos, 2) > 0)
                        <h4 class="text-success mb-3 font-weight-bold">
                            Bs {{ number_format($totalPagos - $totalegresos, 2) }}
                            <!-- Formateo de moneda con 2 decimales -->
                        </h4>
                        <p class="text-muted">Total acumulado de pagos en caja</p>
                    @else
                        <h4 class="text-danger mb-3 font-weight-bold">
                            Bs {{ number_format($totalPagos - $totalegresos, 2) }}
                            <!-- Formateo de moneda con 2 decimales -->
                        </h4>
                        <p class="text-muted">Total acumulado de pagos en caja</p>
                    @endif

                </div>
            </div>

            <div class="card">
                <div class="card-header justify-content-between">
                    <div class="row justify-content-between">
                        <div class="col-md-4 my-auto">
                            <h3 class="card-title my-auto">
                                <strong>LISTA DE PAGOS</strong>
                                <a class="btn" href="{{ route('pagos.lista') }}">
                                    <i class="fas fa-sync fa-md fa-fw"></i>
                                </a>
                            </h3>
                            <form action="{{ route('reportes.export') }}" method="POST" id="exportForm">
                                @csrf
                                <div class="d-flex mb-3">
                                    <!-- <div class="form-group mr-2">
                                        <label for="gestion_id">Gestion:</label>
                                        <select name="gestion_id" id="gestion_id" class="form-control">
                                            @foreach ($gestiones as $gestion)
                                                <option value="{{ $gestion->gestion_id }}"
                                                    {{ optional($gestionActiva)->gestion_id == $gestion->gestion_id ? 'selected' : '' }}>
                                                    {{ $gestion->descripcion }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div> -->
                                    <div class="form-group mr-2">
                                        <label for="fecha_inicio">Fecha de Inicio:</label>
                                        <input type="date" name="fecha_inicio" id="fecha_inicio" class="form-control"
                                            value="{{ optional($gestionActiva)->fecha_inicio }}">
                                    </div>
                                    <div class="form-group">
                                        <label for="fecha_fin">Fecha de Fin:</label>
                                        <input type="date" name="fecha_fin" id="fecha_fin" class="form-control"
                                            value="{{ optional($gestionActiva)->fecha_fin }}">
                                    </div>
                                    
                                </div>
                            </form>
                        </div>
                        <br>
                        <div class="col-md-auto">
                            <label for="fecha_inicio">Fecha de Inicio:</label>
                            <form action="{{ route('reportes.index') }}" method="GET">
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
                        <button type="submit" class="btn btn-primary ml-4 align-self-end">Exportar a
                                        Excel</button>
                        <div class="col-md-auto">
                            <a href="{{ route('pagos.index', ['gestion_id' => optional($gestionActiva)->gestion_id]) }}" class="btn btn-primary">Nuevo</a>
                        </div>
                    </div>

                    @if (!empty($gestionAlert))
                        <div class="alert alert-warning mt-2 mb-0">
                            {{ $gestionAlert }}
                        </div>
                    @endif
                </div>

                <div class="card-body p-0">
                    <table class="table table-hover table-head-fixed" id="pagosTable">
                        <thead class="table-light">
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
                                        <td>{{ $pago['fecha'] }}</td>
                                        <td>{{ $pago['id'] }}</td>
                                        <td>{{ $pago['detalle'] }}</td>
                                        <td>{{ number_format($pago['ingreso'], 2) }}</td>
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
        document.getElementById('fecha_inicio').addEventListener('change', filterTable);
        document.getElementById('fecha_fin').addEventListener('change', filterTable);

        function filterTable() {
            const startDate = document.getElementById('fecha_inicio').value;
            const endDate = document.getElementById('fecha_fin').value;
            const table = document.getElementById('pagosTable');
            const rows = table.getElementsByTagName('tr');

            for (let i = 1; i < rows.length; i++) { // Skip header row
                const cells = rows[i].getElementsByTagName('td');
                const rowDate = cells[1].innerText;

                if (startDate && endDate) {
                    const isInRange = new Date(rowDate) >= new Date(startDate) && new Date(rowDate) <= new Date(endDate);
                    rows[i].style.display = isInRange ? '' : 'none';
                } else {
                    rows[i].style.display = '';
                }
            }
        }
    </script>
@stop
