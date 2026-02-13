<form id="form-matricula" action="{{route('estudiantes.matricular')}}" method="post" autocomplete="off" >
    @csrf
    <div class="row">
        <div class="col-md-5">
            <div class="form-group">
                <label for="buscar_estudiante">Buscar Estudiante (Nombre o CI)</label>
                <input type="text" id="buscar_estudiante" class="form-control"
                    placeholder="Escribe el nombre o CI">
                <div id="sugerencias" class="dropdown-menu" style="display: none; position: absolute;">
                </div>
            </div>
        </div>
        <div class="col-md-5">
            <div class="form-group">
                <label for="nombre_apellido">NOMBRE Y APELLIDO</label>
                <input type="text" name="nombre_apellido" id="nombre_apellido"
                    class="form-control" placeholder="Nombre y Apellido" required disabled>
            </div>
        </div>
        <div class="col-md-5">
            <div class="form-group">
                <label for="ci">CARNET DE IDENTIDAD</label>
                <input name="ci" id="carnetCI" class="form-control" type="number"
                    placeholder="Carnet de Identidad" required disabled>
            </div>
        </div>
        <div class="col-md-5">
            <div class="form-group">
                <label for="carrera">SELECCIONAR CARRERA Y NIVEL</label>
                <select name="carrera_id" id="matriculaCarrera" class="form-control select2"
                    style="width: 100%;">
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
                <label for="gestion">GESTION</label>
                <select name="gestion_id" class="form-control select2" style="width: 100%;">
                    @foreach ($gestiones as $gestion)
                        <option value="{{ $gestion->gestion_id }}"
                            {{ optional($gestionActiva)->gestion_id == $gestion->gestion_id ? 'selected' : '' }}>
                            {{ $gestion->descripcion }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>
    <input type="number"  name="estudiante_id" hidden value="" id="estudiante_id">
    <hr>
    <div class="d-flex justify-content-end">
        <a type="button" class="btn btn-danger mr-2" href="{{ route('estudiantes.index') }}">Cancelar</a>
        <button type="submit" class="btn btn-info">Matricular</button>
    </div>
</form>
