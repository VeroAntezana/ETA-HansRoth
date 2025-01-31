@extends('adminlte::page')
@section('title', 'Recibo de Pago')

@section('content')
<section class="content">
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white">
                        <div class="row align-items-center">
                            <div class="col-md-8">
                                <h4 class="mb-0">RECIBO DE PAGO</h4>
                            </div>
                            <div class="col-md-4 text-right">
                            <img src="{{ asset('vendor/adminlte/dist/img/eta.jpg') }}" alt="Logo" class="img-fluid" style="max-height: 120px;">
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('pagos.store') }}" method="post" autocomplete="off">
                            @csrf
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="nombre" class="font-weight-bold">Nombre del Estudiante:</label>
                                        <input type="text" class="form-control bg-light" id="nombre" name="nombre" 
                                            value="{{ $estudiante->nombre }} {{ $estudiante->apellidos }}" readonly>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="carrera" class="font-weight-bold">Carrera y Nivel:</label>
                                        <input type="text" class="form-control bg-light" id="carrera" name="carrera" 
                                            value="{{ $carrera->nombre }} - {{ $nivel->nombre }}" readonly>
                                    </div>
                                </div>
                            </div>

                            <div class="row mt-3">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="concepto" class="font-weight-bold">Concepto:</label>
                                        <input type="text" class="form-control bg-light" id="concepto" name="concepto" 
                                            value="{{ $pago->concepto }}" readonly>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="fecha" class="font-weight-bold">Fecha:</label>
                                        <input type="text" class="form-control bg-light" id="fecha" name="fecha" 
                                            value="{{ \Carbon\Carbon::parse($pago->fecha)->format('d/m/Y H:i') }}" readonly>
                                    </div>
                                </div>
                            </div>

                            <div class="row mt-3">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="monto" class="font-weight-bold">Monto:</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">Bs</span>
                                            </div>
                                            <input type="text" class="form-control bg-light" id="monto" name="monto" 
                                                value="{{ $pago->monto }}" readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="meses" class="font-weight-bold">Módulos Pagados:</label>
                                        <input type="text" class="form-control bg-light" id="meses" name="meses" 
                                            value="{{$pago->mes_pago}}" readonly>
                                    </div>
                                </div>
                            </div>

                            <div class="row mt-4">
                                <div class="col-12 text-center">
                                    <button type="button" onclick="imprimirRecibo()" class="btn btn-primary btn-lg px-5" id="btnImprimir">
                                        <i class="fas fa-print mr-2"></i>Imprimir Recibo
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="card-footer text-muted text-center">
                        Este documento es un comprobante de pago ETA HANS ROTH
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@stop

@section('css')
<style>
    .card {
        border: none;
        border-radius: 15px;
    }
    
    .card-header {
        border-radius: 15px 15px 0 0 !important;
        padding: 1.5rem;
    }
    
    .form-control {
        border: 1px solid #ddd;
        border-radius: 8px;
        padding: 10px 15px;
    }
    
    .form-control:read-only {
        background-color: #f8f9fa !important;
    }
    
    label {
        color: #555;
        margin-bottom: 0.5rem;
    }
    
    .btn-primary {
        border-radius: 8px;
        padding: 12px 30px;
        font-weight: 600;
        transition: all 0.3s;
    }
    
    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }
    
    .card-footer {
        background-color: #f8f9fa;
        border-radius: 0 0 15px 15px;
        padding: 1rem;
    }
    
    @media print {
        #btnImprimir {
            display: none;
        }
        
        .card {
            box-shadow: none !important;
        }
        
        .card-header {
            background-color: #fff !important;
            color: #000 !important;
        }
        
        .form-control {
            border: none !important;
        }
        
        .card-footer {
            border-top: 1px solid #ddd;
        }
    }
</style>
@stop

@section('js')
<script>
function imprimirRecibo() {
    
    var printWindow = window.open("{{ route('pagos.print', $pago->pago_id) }}", "Print", "width=800,height=800");
    
    printWindow.onload = function() {
        
        setTimeout(function() {
            printWindow.print();
           
        }, 500);
    };
}
</script>
@stop