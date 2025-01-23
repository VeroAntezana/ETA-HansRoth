<div class="row">
    <div class="col-md-5">
        <div class="form-group">
            <label>NOMBRE</label>
            <input type="text" class="form-control" value="{{ $estudiante->nombre }}" readonly>
        </div>
    </div>
    <div class="col-md-5">
        <div class="form-group">
            <label>APELLIDOS</label>
            <input type="text" class="form-control" value="{{ $estudiante->apellidos }}" readonly>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-5">
        <div class="form-group">
            <label>FECHA NACIMIENTO</label>
            <input type="date" class="form-control" value="{{ $estudiante->fecha_nacimiento }}" readonly>
        </div>
    </div>
    <div class="col-md-5">
        <div class="form-group">
            <label>CARNET DE IDENTIDAD</label>
            <input class="form-control" type="number" value="{{ $estudiante->ci }}" readonly>
        </div>
    </div>
    <div class="col-md-5">
        <div class="form-group">
            <label>SEXO</label>
            <input type="text" class="form-control" value="{{ $estudiante->sexo == 'M' ? 'Masculino' : 'Femenino' }}" readonly>
        </div>
    </div>
</div>
<div class="d-flex justify-content-end">
    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
</div>