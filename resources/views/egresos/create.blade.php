@extends('adminlte::page')
@section('content')
<section class="content">
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header text-center bg-primary text-white">Recibo Egreso</div>
                    <div class="card-body">
                        <form action="{{ route('egresos.store') }}" method="post" autocomplete="off">
                            @csrf
                            <div class="row">
                            <div class="col-md-12 mb-3">
                                    <label for="search">Nombre:</label>
                                    <input type="text" id="nombre" name="nombre" placeholder=""
                                        class="form-control" onfocus="this.value=''">
  
                                </div>
                              
                            </div>
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label for="fecha">Fecha:</label>
                                    <input type="datetime-local" name="fecha" class="form-control"
                                        value="{{ old('fecha', \Carbon\Carbon::now('America/La_Paz')->format('Y-m-d\TH:i')) }}">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="monto">Monto:</label>
                                    <input type="text" class="form-control" id="monto" name="monto" required>
                                </div>
                                <div class="col-md-4 mb-3">
                                <label for="carrera">GESTION</label>
                                    <select name="gestion_id" class="form-control select2" style="width: 100%;">
                                        @foreach ($gestiones as $gestion)
                                            <option value="{{ $gestion->gestion_id }}">{{ $gestion->descripcion }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 mb-3">
                                    <label for="concepto">Concepto:</label>
                                    <input type="text" class="form-control" id="concepto" name="concepto" required>
                                </div>
                            </div>
                            <div class="text-center">
                                <button type="submit" class="btn btn-primary">Generar Recibo</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@stop