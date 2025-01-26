<div class="row">
    <div class="col-md-5">
        <div class="form-group">
            <label>NOMBRE</label>
            <input type="text" class="form-control" value="{{ $estudiante->NombreEstudiante }}" readonly>
        </div>
    </div>
    <div class="col-md-5">
        <div class="form-group">
            <label>APELLIDOS</label>
            <input type="text" class="form-control" value="{{ $estudiante->ApellidosEstudiante }}" readonly>
        </div>
    </div>
    <div class="col-md-5">
        <div class="form-group">
            <label>CARNET DE IDENTIDAD</label>
            <input type="text" class="form-control" value="{{ $estudiante->CI }}" readonly>
        </div>
    </div>
    <div class="col-md-5">
        <div class="form-group">
            <label>CARRERA-NIVEL</label>
            <input type="text" class="form-control" value="{{ $estudiante->NombreCarrera . ' - ' . $estudiante->Nivel }}" readonly>
        </div>
    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
</div>