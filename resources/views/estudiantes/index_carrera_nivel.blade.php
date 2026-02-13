@extends('adminlte::page')

@section('title')
@if($carrera)
Estudiantes - {{ strtoupper($carrera->nombre) }} {{ strtoupper($nivel) }}
@else
Estudiantes - Carrera no encontrada
@endif
@endsection

@section('content')
<section class="content">
    <div class="container-fluid p-4">
        <div class="card">
            <div class="card-header justify-content-between">
                <div class="d-flex justify-content-between align-items-center">
                    <h3 class="card-title my-auto">
                        @if($carrera)
                        <strong>LISTA DE ESTUDIANTES - {{ strtoupper($carrera->nombre) }} {{ strtoupper($nivel->nombre) }}</strong>
                        @else
                        <strong>Carrera no encontrada</strong>
                        @endif
                    </h3>
                    <form method="GET" class="d-flex align-items-center">
                        <select name="gestion_id" class="form-control select2" style="width: 220px;" onchange="this.form.submit()">
                            @foreach ($gestiones as $gestion)
                                <option value="{{ $gestion->gestion_id }}"
                                    {{ optional($gestionActiva)->gestion_id == $gestion->gestion_id ? 'selected' : '' }}>
                                    {{ $gestion->descripcion }}
                                </option>
                            @endforeach
                        </select>
                    </form>
                </div>
            </div>

            <div class="card-body p-0">
                @if(!empty($gestionAlert))
                <div class="alert alert-warning m-3">
                    {{ $gestionAlert }}
                </div>
                @endif
                @if(session('error'))
                <div class="alert alert-warning">
                    {{ session('error') }}
                </div>
                @endif

                @if($carrera)
                @if(empty( $estudiantes))
                <div class="alert alert-info">
                    No hay estudiantes registrados para esta carrera y nivel.
                </div>
                @else
                <table class="table table-hover table-head-fixed">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>NOMBRES</th>
                            <th>APELLIDOS</th>
                            <th>CARNET IDENTIDAD</th>
                            <th>CARRERA-NIVEL</th>
                            <th>OPCIONES</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($estudiantes as $estudiante)
                        <tr>
                            <td>{{ $estudiante->ID_Estudiante }}</td>
                            <td>{{ $estudiante->NombreEstudiante }}</td>
                            <td>{{ $estudiante->ApellidosEstudiante }}</td>
                            <td>{{ $estudiante->CI }}</td>
                            <td>
                                {{ $estudiante->NombreCarrera . ' - ' . $estudiante->Nivel }}

                            </td>
                            <td class="text-center">
                                <button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#showModal{{ $estudiante->ID_Estudiante }}">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <div class="modal fade" id="showModal{{ $estudiante->ID_Estudiante }}" tabindex="-1">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Información del Estudiante</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                @include('partials.estudiantes.form_show_nivel', ['estudiante' => $estudiante])
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @endif
                @else
                <div class="alert alert-danger">
                    No se pudo encontrar la carrera especificada.
                </div>
                @endif
            </div>
        </div>
    </div>
</section>
@stop
