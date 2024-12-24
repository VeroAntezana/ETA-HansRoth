<form action="{{ route('carreras.store') }}" method="post">
    @csrf
    <div class="row">
        <div class="col-md-9">
            <div class="form-group">
                <label for="nombre">NOMBRE</label>
                <input type="text" name="nombre" class="form-control my-colorpicker1" value="{{ old('nombre') }}"
                    required>

                @error('nombre')
                <small class="text-danger">*{{ $message }}</small>
                @enderror
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label for="nivel">Nivelwd</label>
                    <select name="nivel_id" class="form-control select2" style="width: 100%;">
                        @foreach ($niveles as $nivel)
                            <option value="{{ $nivel->id }}">
                                {{ $nivel->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>


            <div class="form-group">
                <label for="duracion">DURACION</label>
                <input type="text" name="duracion" class="form-control my-colorpicker1" value="{{ old('duracion') }}"
                    required>

                @error('duracion')
                <small class="text-danger">*{{ $message }}</small>
                @enderror
            </div>

        </div>
    </div>

    <hr>
    <div class="d-flex justify-content-end">
        <a type="button" class="btn btn-danger mr-2" href="{{ route('carreras.index') }}">Hey</a>
        <button type="submit" class="btn btn-info">GuardarSI</a>
    </div>

</form>