<form action="{{ route('estudiantes.store') }}" method="post" autocomplete="off">
    @csrf
    <div class="row">
        <div class="col-md-5">
            <div class="form-group">
                <label for="nombre">NOMBRE</label>
                <input type="text" name="nombre" class="form-control my-colorpicker1" value="{{ old('nombre') }}"
                    required>

                @error('nombre')
                <small class="text-danger">*{{ $message }}</small>
                @enderror
            </div>
        </div>
        <div class="col-md-5">
            <div class="form-group">
                <label for="apellidos">APELLIDOS</label>
                <input type="text" name="apellidos" class="form-control my-colorpicker1" value="{{ old('apellidos') }}"
                    required>

                @error('apellidos')
                <small class="text-danger">*{{ $message }}</small>
                @enderror
            </div>
        </div>

    </div>
    <div class="row">


        <div class="col-md-5">
            <div class="form-group">
                <label for="fecha_nacimiento">FECHA NACIMIENTO</label>
                <input type="date" name="fecha_nacimiento" class="form-control my-colorpicker1" value="{{ old('fecha_nacimiento') }}"
                required>
                @error('fecha_nacimiento')
                <small class="text-danger">*{{ $message }}</small>
                @enderror
            </div>
        </div>


        <div class="col-md-5">
            <div class="form-group">
                <label for="carnet">CARNET DE IDENTIDAD</label>
                <input name="ci" class="form-control my-colorpicker1" type="number" value="{{ old('carnet') }}"
                    step="1" min="0" value="0" required>

                @error('carnet')
                <small class="text-danger">*{{ $message }}</small>
                @enderror
            </div>
        </div>


        <div class="col-md-5">
            <div class="form-group">
                <label for="carnet">SEXO</label>
                <select id="sexo" name="sexo" class="form-control">
                    <option value="">Seleccione</option>
                    <option value="M">Masculino</option>
                    <option value="F">Femenino</option>
                </select>
            </div>
        </div>
        <div class="col-md-5">
            <div class="form-group">
                <label for="carrera">SELECCIONAR CARRERA Y NIVEL</label>
                     <select name="carrera_id" class="form-control select2" style="width: 100%;">
                        @foreach ($carreras as $carrera)
                            <option value="{{ $carrera->carrera_id }}">
                         {{ $carrera->nombre }} - {{ $carrera->nivel->nombre }}
                             </option>
                         @endforeach
                    </select>

            </div>
        </div>
        <div class="col-md-5">
            <div class="form-group">
                <label for="carrera">GESTION</label>
                <select name="gestion_id" class="form-control select2" style="width: 100%;">
                    @foreach ($gestiones as $gestion)
                    <option value="{{ $gestion->gestion_id }}">{{ $gestion->descripcion }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    <hr>
    <div class="d-flex justify-content-end">
        <a type="button" class="btn btn-danger mr-2" href="{{ route('estudiantes.index') }}">Cancelar</a>
        <button type="submit" class="btn btn-info">Guardar</a>
    </div>

</form>

