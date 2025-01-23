<form action="{{ route('niveles.update', $nivel->nivel_id) }}" method="POST" autocomplete="off">
    @csrf
    @method('PUT')
    <div class="row">
        <div class="col-md-5">
            <div class="form-group">
                <label>NOMBRE</label>
                <input type="text" name="nombre" class="form-control" value="{{ $nivel->nombre }}" required>
            </div>
        </div>
    </div>
    <div class="d-flex justify-content-end">
        <button type="button" class="btn btn-danger mr-2" data-dismiss="modal">Cancelar</button>
        <button type="submit" class="btn btn-info">Actualizar</button>
    </div>
</form>