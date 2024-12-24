@extends('adminlte::page')
@section('title', 'Recibo de Pago')

@section('content')
<section class="content">
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <br>
                <br>
                <div class="card">
                    <div class="card-header">GENERAR RECIBO DE PAGO</div>
                    <div class="card-body">
                        <form action="{{ route('pagos.store') }}" method="post" autocomplete="off">
                            @csrf
                            <div class="form-group">
                                <label for="nombre">Nombre:</label>
                                <input type="text" class="form-control" id="nombre" name="nombre" value="{{ $pago->estudiante->nombre }} {{ $pago->estudiante->apellidos }}" readonly>
                            </div>
                            <div class="form-group">
                                <label for="carrera">Carrera:</label>
                                <input type="text" class="form-control" id="carrera" name="carrera" value="@if($pago->estudiante->carreras->isNotEmpty()) {{ $pago->estudiante->carreras->first()->carrera_nombre . ' - ' . $pago->estudiante->carreras->first()->nivel_nombre }} @else Sin carrera @endif" readonly>
                            </div>
                            
                            <div class="form-group">
                                <label for="concepto">Concepto:</label>
                                <input type="text" class="form-control" id="concepto" name="concepto" value="{{ $pago->concepto }}" readonly>
                            </div>
                            <div class="form-group">
                                <label for="fecha">Fecha:</label>
                                <input type="text" class="form-control" id="fecha" name="fecha" value="{{ \Carbon\Carbon::parse($pago->fecha)->format('d/m/Y H:i') }}" readonly>
                            </div>
                            <div class="form-group">
                                <label for="monto">Monto:</label>
                                <input type="text" class="form-control" id="monto" name="monto" value="{{ $pago->monto }}" readonly>
                            </div>
                            <div class="form-group">
                                <label for="meses">Meses Pagados:</label>
                                <input type="text" class="form-control" id="meses" name="meses" value="{{ $pago->mes_pago }}" readonly>
                            </div>
                            <button type="button" onclick="imprimirRecibo()" class="btn btn-primary" id="btnImprimir">Imprimir Recibo</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@stop
@section('css')
<style>
    /* Ocultar el botón de impresión cuando se imprime la página */
    @media print {
        #btnImprimir {
            display: none;
        }
    }
</style>
@stop
@section('js')
<script>
  
   // Función para imprimir el recibo
   function imprimirRecibo() {
        window.print();
    }

</script>
@stop
