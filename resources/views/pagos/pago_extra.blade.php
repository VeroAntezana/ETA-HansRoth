@extends('adminlte::page')

<head>
    <style>
        /* Estilos generales del formulario */
        .card-header {
            text-align: center;
            font-weight: bold;
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
                        <div class="card-header text-center bg-success text-white">Registro de Ingreso Extra</div>
                        <div class="card-body">
                            <form action="{{ route('pagos.storeExtra') }}" method="post" autocomplete="off">
                                @csrf
                                <div class="row">
                                    <div class="col-md-12 mb-3">
                                        <label for="concepto">Concepto:</label>
                                        <input type="text" id="concepto" name="concepto" class="form-control"
                                            placeholder="Ejemplo: Pagos Varios o Extras" required>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="monto">Monto:</label>
                                        <input type="number" class="form-control" id="monto" name="monto" step="0.01" min="0" required>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="fecha">Fecha:</label>
                                        <input type="datetime-local" name="fecha" class="form-control"
                                            value="{{ old('fecha', \Carbon\Carbon::now('America/La_Paz')->format('Y-m-d\TH:i')) }}">
                                    </div>
                                </div>

                                <div class="text-center">
                                    <button type="submit" class="btn btn-success">Registrar Ingreso Extra</button>
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
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const montoInput = document.getElementById('monto');
            montoInput.addEventListener('input', function() {
                if (montoInput.value < 0) {
                    montoInput.value = 0;
                }
            });
        });
    </script>
@stop
