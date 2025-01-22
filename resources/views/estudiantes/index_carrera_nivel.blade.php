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
                <h3 class="card-title my-auto">
                    @if($carrera)
                    <strong>LISTA DE ESTUDIANTES - {{ strtoupper($carrera->nombre) }} {{ strtoupper($nivel->nombre) }}</strong>

                    @else
                        <strong>Carrera no encontrada</strong>
                    @endif
                </h3>
            </div>

            <div class="card-body p-0">
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
                                    <td>
                                        <button type="button" class="btn btn-warning btn-sm">Editar</button>
                                        <form action="{{ route('estudiantes.destroy', $estudiante->ID_Estudiante) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('¿Estás seguro de que deseas eliminar este estudiante?')">Eliminar</button>
                                        </form>
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
