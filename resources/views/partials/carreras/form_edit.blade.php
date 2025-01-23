<form action="{{ route('carreras.update', $carrera->carrera_id) }}" method="POST" autocomplete="off">
    @csrf
    @method('PUT')
    <div class="row">
        <div class="col-md-5">
            <div class="form-group">
                <label>NOMBRE</label>
                <input type="text" name="nombre" class="form-control" value="{{ $carrera->nombre }}" required>
            </div>
        </div>
        <div class="col-md-5">
            <div class="form-group">
                <label>DURACIÓN (MESES)</label>
                <input type="number" name="duracion_meses" class="form-control" value="{{ $carrera->duracion_meses }}" required>
            </div>
        </div>
        <div class="col-md-5">
            <div class="form-group">
                <label>NIVEL</label>
                <select name="nivel_id" class="form-control" required>
                    @foreach($niveles as $nivel)
                        <option value="{{ $nivel->nivel_id }}" {{ $carrera->nivel_id == $nivel->nivel_id ? 'selected' : '' }}>
                            {{ $nivel->nombre }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>
    <div class="d-flex justify-content-end">
        <button type="button" class="btn btn-danger mr-2" data-dismiss="modal">Cancelar</button>
        <button type="submit" class="btn btn-info">Actualizar</button>
    </div>
</form>