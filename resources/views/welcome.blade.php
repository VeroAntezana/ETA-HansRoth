@extends('adminlte::page')

@section('title', 'ETA')

@section('content_header')
    <h1>ETA HANS ROTH</h1>
@stop

@section('content')
    <p>BIENVENIDOS A ETA HANS ROTH FE Y ALEGRIA</p>
    <div class="d-flex justify-content-center">
        <img src="vendor/adminlte/dist/img/eta.jpg" alt="Iglesia" class="img-fluid small-image">
    </div>
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
    <style>
        .small-image {
            width: 40%; /* Ajusta el valor según tus necesidades */
        }
    </style>
    
@stop

@section('js')
    <script> console.log('Hi!'); </script>
@stop