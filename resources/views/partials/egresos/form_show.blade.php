<div class="container p-4">
    <div class="card">
        <div class="card-header text-white d-flex justify-content-between align-items-center" style="background-color: #c38a31;">
            <h4 class="mb-0">RECIBO DE EGRESO</h4>
            <img src="{{ asset('vendor/adminlte/dist/img/eta.jpg') }}" alt="Logo" class="img-fluid" style="max-height: 120px;">
        </div>

        <div class="card-body">
            <div class="row mb-4">
                <div class="col-md-6">
                    <label class="font-weight-bold">Nombre:</label>
                    <div class="form-control bg-light">{{ $egreso->nombre }}</div>
                </div>
                <div class="col-md-6">
                    <label class="font-weight-bold">Fecha:</label>
                    <div class="form-control bg-light">{{ \Carbon\Carbon::parse($egreso->fecha)->format('d/m/Y H:i') }}</div>
                </div>
            </div>

            <div class="row mb-4">
                <div class="col-md-6">
                    <label class="font-weight-bold">Concepto:</label>
                    <div class="form-control bg-light">{{ $egreso->concepto }}</div>
                </div>
                <div class="col-md-6">
                    <label class="font-weight-bold">Monto:</label>
                    <div class="form-control bg-light">Bs {{ number_format($egreso->monto, 2) }}</div>
                </div>
            </div>

            <div class="row mb-4">
                <div class="col-md-12">
                    <label class="font-weight-bold">Gestión:</label>
                    <div class="form-control bg-light">{{ $egreso->gestion->descripcion }}</div>
                </div>
            </div>

            <div class="text-center mt-4">
                <button onclick="imprimirRecibo({{ $egreso->egreso_id }})" class="btn text-white" style="background-color: #c38a31;">
                    <i class="fas fa-print"></i> Imprimir Recibo
                </button>
            </div>

            <div class="text-center text-muted mt-4">
                <small>Este documento es un comprobante de egreso ETA HANS ROTH</small>
            </div>
        </div>
    </div>
</div>

<script>
function imprimirRecibo(egresoId) {
    var printWindow = window.open("{{ url('egresos/print') }}/" + egresoId, "Print", "width=800,height=800");
    
    printWindow.onload = function() {
        setTimeout(function() {
            printWindow.print();
        }, 500);
    };
}
</script>
<style>
    @media print {

        .modal-header,
        .btn,
        .modal-footer {
            display: none !important;
        }

        .card {
            border: none !important;
        }

        .card-header {
            background-color: #c38a31 !important;
            -webkit-print-color-adjust: exact;
        }

        .bg-light {
            background-color: #f8f9fa !important;
            -webkit-print-color-adjust: exact;
        }
    }
</style>