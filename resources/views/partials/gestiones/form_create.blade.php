<form action="{{ route('gestiones.store') }}" method="post">
    @csrf
    <div class="row">
        <div class="col-md-9">
            <div class="form-group">
                <label for="descripcion">DESCRIPCION</label>
                <input type="text" name="descripcion" class="form-control my-colorpicker1" value="{{ old('descripcion') }}"required>

                @error('descripcion')
                    <small class="text-danger">*{{ $message }}</small>
                @enderror
            </div>

        </div>
       
        <div class="col-md-5">
            <div class="form-group">
                <label for="fecha_inicio">FECHA INICIO</label>
                <input type="datetime-local" name="fecha_inicio" class="form-control my-colorpicker1" value="{{ old('fecha_inicio') }}"required>

                @error('fecha_inicio')
                    <small class="text-danger">*{{ $message }}</small>
                @enderror
            </div>
            
        </div>
        <div class="col-md-5">
            <div class="form-group">
                <label for="fecha_fin">FECHA FIN</label>
                <input type="datetime-local" name="fecha_fin" class="form-control my-colorpicker1" value="{{ old('fecha_fin') }}"required>

                @error('fecha_fin')
                    <small class="text-danger">*{{ $message }}</small>
                @enderror
            </div>
            
        </div>
        
    </div>
    <hr>
    <div class="d-flex justify-content-end">
        <a type="button" class="btn btn-danger mr-2" href="{{ route('gestiones.index') }}">Cancelar</a>
        <button type="submit" class="btn btn-info">Guardar</a>
    </div>

</form>
