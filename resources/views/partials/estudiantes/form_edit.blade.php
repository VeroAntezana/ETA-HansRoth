<form action="{{ route('estudiantes.update', $estudiante->estudiante_id) }}" method="POST" autocomplete="off">
    @csrf
    @method('PUT')
    <div class="row">
        <div class="col-md-5">
            <div class="form-group">
                <label for="nombre">NOMBRE</label>
                <input type="text" name="nombre" class="form-control" value="{{ $estudiante->nombre }}" required>
            </div>
        </div>
        <div class="col-md-5">
            <div class="form-group">
                <label for="apellidos">APELLIDOS</label>
                <input type="text" name="apellidos" class="form-control" value="{{ $estudiante->apellidos }}" required>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-5">
            <div class="form-group">
                <label for="fecha_nacimiento">FECHA NACIMIENTO</label>
                <input type="date" name="fecha_nacimiento" class="form-control" value="{{ $estudiante->fecha_nacimiento }}" required>
            </div>
        </div>
        <div class="col-md-5">
            <div class="form-group">
                <label for="ci">CARNET DE IDENTIDAD</label>
                <input name="ci" class="form-control" type="number" value="{{ $estudiante->ci }}" required>
            </div>
        </div>
        <div class="col-md-5">
            <div class="form-group">
                <label>SEXO</label>
                <select name="sexo" class="form-control">
                    <option value="M" {{ $estudiante->sexo == 'M' ? 'selected' : '' }}>Masculino</option>
                    <option value="F" {{ $estudiante->sexo == 'F' ? 'selected' : '' }}>Femenino</option>
                </select>
            </div>
        </div>
    </div>
    <div class="d-flex justify-content-end">
        <button type="button" class="btn btn-danger mr-2" data-dismiss="modal">Cancelar</button>
        <button type="submit" class="btn btn-info">Actualizar</button>
    </div>
</form>