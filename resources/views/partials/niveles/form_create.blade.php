<form action="{{ route('niveles.store') }}" method="post">
    @csrf
    <div class="row">
        <div class="col-md-9">
            <div class="form-group">
                <label for="nombre">NOMBRE</label>
                <input type="text" name="nombre" class="form-control my-colorpicker1" value="{{ old('nombre') }}"required>

                @error('nombre')
                    <small class="text-danger">*{{ $message }}</small>
                @enderror
            </div>
        </div>
        
    </div>
    <hr>
    <div class="d-flex justify-content-end">
        <a type="button" class="btn btn-danger mr-2" href="{{ route('niveles.index') }}">Cancelar</a>
        <button type="submit" class="btn btn-info">Guardar</a>
    </div>

</form>
