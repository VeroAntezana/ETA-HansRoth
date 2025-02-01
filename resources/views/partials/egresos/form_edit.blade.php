<!-- resources/views/partials/egresos/form_edit.blade.php -->
<form action="{{ route('egresos.update', $egreso->egreso_id) }}" method="POST" autocomplete="off">
    @csrf
    @method('PUT')
    <div class="row">
        <div class="col-md-5">
            <div class="form-group">
                <label>NOMBRE</label>
                <input type="text" name="nombre" class="form-control" value="{{ $egreso->nombre }}" required>
            </div>
        </div>
        <div class="col-md-5">
            <div class="form-group">
                <label>FECHA</label>
                <input type="datetime-local" name="fecha" class="form-control" 
                    value="{{ \Carbon\Carbon::parse($egreso->fecha)->format('Y-m-d\TH:i') }}" required>
            </div>
        </div>
        <div class="col-md-5">
            <div class="form-group">
                <label>MONTO</label>
                <input type="number" name="monto" class="form-control" value="{{ $egreso->monto }}" required>
            </div>
        </div>
        <div class="col-md-5">
            <div class="form-group">
                <label>GESTIÓN</label>
                <select name="gestion_id" class="form-control" required>
                    @foreach($gestiones as $gestion)
                        <option value="{{ $gestion->gestion_id }}" {{ $egreso->gestion_id == $gestion->gestion_id ? 'selected' : '' }}>
                            {{ $gestion->descripcion }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="col-md-10">
            <div class="form-group">
                <label>CONCEPTO</label>
                <input type="text" name="concepto" class="form-control" value="{{ $egreso->concepto }}" required>
            </div>
        </div>
    </div>
    <div class="d-flex justify-content-end">
        <button type="button" class="btn btn-danger mr-2" data-dismiss="modal">Cancelar</button>
        <button type="submit" class="btn btn-info">Actualizar</button>
    </div>
</form>