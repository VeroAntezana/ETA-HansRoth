<form action="{{ route('gestiones.update', $gestion->id) }}" method="POST">
    @csrf
    @method('PUT')
    <div class="row">
        <div class="col-md-9">
            <div class="form-group">
                <label for="descripcion">DESCRIPCION</label>
                <input type="text" name="descripcion" class="form-control my-colorpicker1" value="{{ old('descripcion', $gestion->descripcion) }}" required>

                @error('descripcion')
                    <small class="text-danger">*{{ $message }}</small>
                @enderror
            </div>
        </div>
       
        <div class="col-md-5">
            <div class="form-group">
                <label for="fecha_inicio">FECHA INICIO</label>
                <input type="datetime-local" name="fecha_inicio" class="form-control my-colorpicker1" value="{{ old('fecha_inicio', $gestion->fecha_inicio) }}" required>

                @error('fecha_inicio')
                    <small class="text-danger">*{{ $message }}</small>
                @enderror
            </div>
        </div>
        <div class="col-md-5">
            <div class="form-group">
                <label for="fecha_fin">FECHA FIN</label>
                <input type="datetime-local" name="fecha_fin" class="form-control my-colorpicker1" value="{{ old('fecha_fin', $gestion->fecha_fin) }}" required>

                @error('fecha_fin')
                    <small class="text-danger">*{{ $message }}</small>
                @enderror
            </div>
        </div>
    </div>
    <hr>
    <div class="d-flex justify-content-end">
        <button type="button" class="btn btn-danger mr-2" data-dismiss="modal">Cancelar</button>
        <button type="submit" class="btn btn-info">Actualizar</button>
    </div>
</form>
