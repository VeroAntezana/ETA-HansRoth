<!-- Modal para crear egreso -->
<div class="modal fade" id="modalEgreso" tabindex="-1" role="dialog" aria-labelledby="modalEgresoLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalEgresoLabel">Crear Egreso</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Contenido del modal -->
                <form action="{{ route('egresos.store') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="nombre">Nombre:</label>
                        <input type="text" class="form-control" id="nombre" name="nombre" placeholder="Buscar...">
                    </div>
                 
                    <div class="form-group">
                        <label for="fecha">Fecha:</label>
                        <input type="datetime-local" class="form-control" id="fecha" name="fecha" value="2025-01-29T21:22">
                    </div>
                    <div class="form-group">
                        <label for="fecha">Monto:</label>
                        <input type="number" class="form-control" id="monto" name="monto" value="2025-01-29T21:22">
                    </div>
                    <div class="form-group">
                        <label for="concepto">Concepto:</label>
                        <textarea class="form-control" id="concepto" name="concepto" rows="3"></textarea>
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary">Generar Recibo</button>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>