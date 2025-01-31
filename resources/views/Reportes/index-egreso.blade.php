@extends('adminlte::page')

@section('title', 'pagos')

@section('content')
<section class="content">
    <div class="container-fluid p-4">
        <div class="card shadow-sm">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h3 class="card-title mb-0 font-weight-bold">
                    <strong>Total Salida</strong>
                </h3>
                <a class="btn btn-link" href="{{ route('pagos.lista') }}">
                    <i class="fas fa-sync fa-lg"></i>
                </a>
            </div>
            <div class="card-body d-flex justify-content-center align-items-center flex-column">
                <h4 class="text-success mb-3 font-weight-bold">
                    Bs <!-- Formateo de moneda con 2 decimales -->
                </h4>
                <p class="text-muted">Total acumulado de egreso</p>
            </div>
        </div>

        <div class="card">

            <div class="card-header justify-content-between">
                <div class="row justify-content-between">
                    <div class="col-xs-4 my-auto">
                        <h3 class="card-title my-auto">
                            <strong>LISTA DE EGRESOS</strong>
                            <a class="btn" href="{{ route('pagos.lista') }}">
                                <i class="fas fa-sync fa-md fa-fw"></i>
                            </a>
                        </h3>
                    </div>
                    <div class="col-xs">
                        <a href="#" class="btn btn-primary" data-toggle="modal" data-target="#modalEgreso">Nuevo</a>
                    </div>


                </div>

            </div>

            <div class="card-body p-0">

                <table class="table table-hover table-head-fixed">
                    <thead class="table-light ">
                        <tr>
                            <th>#</th>
                            <th>Fecha</th>
                            <th>Recibo</th>
                            <th>Detalles</th>
                            <th>Egresos</th>
                            <th>OPCIONES</th>

                        </tr>
                    </thead>


                    <tbody>

                    </tbody>

                </table>
            </div>

        </div>
    </div>

</section>

@stop