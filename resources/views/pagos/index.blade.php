@extends('adminlte::page')
@section('title', 'Recibo pagos')

<head>
 
    <style>
        /* Estilo para el contenedor del selector de meses */
        .mes-selector {
            position: relative;
            display: inline-block;
        }

        /* Estilo para la lista de meses */
        .mes-lista {
            display: none;
            position: absolute;
            background-color: #fff;
            border: 1px solid #ccc;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            z-index: 1000;
            top: -150px;
            /* Ajusta la posición vertical según sea necesario */
            left: 0;
            /* Coloca la lista a un lado del campo de entrada */
            /* Cambios para mostrar en cuatro columnas y tres meses por columna */
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            max-width: 350px;
            /* Ancho máximo para mantener la apariencia cuadrada */
            gap: 5px;
            /* Espaciado entre los meses */
            padding: 10px;
            /* Relleno para mejorar la apariencia */
        }

        /* Estilo para las opciones de la lista de meses */
        .mes-lista label {
            display: block;
            padding: 5px;
            cursor: pointer;
            text-align: center;
            /* Centrar el texto en cada cuadro */
            font-size: 12px;
            /* Tamaño de fuente más pequeño */
        }
    </style>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css">
</head>

@section('content')
<section class="content">
    <div class="container mt-5">
        <div class="row justify-content-center">

            <div class="col-md-8">
                <br>
                <br>
                <div class="card">
                    <div class="card-header">RECIBO</div>
                    <div class="card-body">

                        <form action="{{ route('pagos.store') }}" method="post" autocomplete="off">
                            @csrf
                            <div class="row">
                                <div class=" col-md-6">
                                    <div class="form-group">
                                        <label for="search">Nombre:</label>
                                        <input type="text" name="search" id="search" placeholder="Buscar..."
                                            class="form-control" onfocus="this.value=''">
                                        <div id="search_list"></div>
                                    </div>
                                </div>

                                <!-- En tu vista de pagos -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="carreraNivel">Carrera</label>
                                        <input type="text" class="form-control" id="carreraNivel" name="carreraNivel"
                                            readonly>
                                    </div>
                                </div>
                            </div>
                            <br>
                            <div class="form-group">
                                <label for="concepto">Concepto</label>
                                <input type="text" class="form-control" id="concepto" name="concepto" required>
                            </div>

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="fecha">Fecha</label>
                                        <input name="fecha" type="datetime-local" class="form-control my-colorpicker1"
                                            value="{{ old('fecha', \Carbon\Carbon::now('America/La_Paz')->format('Y-m-d\TH:i')) }}">
                                    </div>

                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="monto">Monto</label>
                                        <input type="text" class="form-control" id="monto" name="monto" required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="mes_pago">Meses a Pagar</label>
                                        <!-- Utilizamos un div personalizado para simular un select -->
                                        <div class="mes-selector" onclick="toggleMeses()">
                                            <input type="text" class="form-control" id="mes_pago" name="mes_pago[]"
                                                readonly>
                                            <!-- Lista de meses -->
                                            <div class="mes-lista">
                                                <?php
                                                $meses = array('Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre');

                                                foreach ($meses as $mes) {
                                                    echo '<label><input type="checkbox" name="meses[]" value="' . $mes . '"> ' . $mes . '</label>';
                                                }
                                                ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <input type="hidden" id="estudiante_id" name="estudiante_id" value="">

                                <button class="btn btn-sm btn-primary botonguardar" type="submit">Generar
                                    Recibo</button>

                            </div>


                        </form>

                    </div>

                </div>


            </div>


        </div>

    </div>

</section>
@stop


@section ('js')
<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>

<script>
    $(document).ready(function () {
        $(".mes-lista").hide();
    });

    function toggleMeses() {
        $(".mes-lista").toggle();
    }

    $("input[name='meses[]']").on("change", function () {
        var selectedMonths = [];
        $("input[name='meses[]']:checked").each(function () {
            selectedMonths.push($(this).val());
        });
        $("#mes_pago").val(selectedMonths.join(', '));
    });

    $(document).on("click", function (event) {
        if (!$(event.target).closest('.mes-selector').length) {
            $(".mes-lista").hide();
        }
    });
</script>
<script>
    
   $('#search').on('keyup', function () {
       var query = $(this).val();
       if(query.trim()==''){
           $('#search_list').html('');
           return;
       }
       $.ajax({
           url: "search",
           type: "GET",
           data: { 'search': query },
           dataType: 'json', // Especifica el tipo de datos que esperas recibir
           success: function (data) {
               var output = '';
               if (data.length > 0) {
                   $.each(data, function (index, row) {
                       
                       output += '<tr data-estudiante-id="' + row.id + '">';
                       output += '<td>' + row.nombre + '</td>';
                       output += '<td>' + row.apellidos + '</td>';
                       output += '</tr>';
                      
                   });
               } else {
                   output = 'No results';
               }
               $('#search_list').html(output);
               
           }
       });
   });

</script>

<script>
        $(document).on('click', '#search_list tr', function () {
        var nombre = $(this).find('td:first').text();
        var apellidos = $(this).find('td:eq(1)').text();
        var estudianteId = $(this).data('estudiante-id');
        $('#estudiante_id').val(estudianteId);

        // Rellena el campo de nombre con los valores seleccionados
        $('#search').val(nombre + ' ' + apellidos);

        // Rellena el campo de carrera (puedes ajustar esto según la lógica que necesites)
       

        // Oculta la lista de sugerencias
        $('#search_list').html('');
        $.ajax({
        url: "{{ url('getEstudianteInfo') }}/" + estudianteId,
        type: "GET",
        dataType: 'json',
        success: function (data) {
            if (data.success) {
                // Autocompletar el campo de carrera y nivel
                $('#carreraNivel').val(data.carreraNivel);
               
                
            } else {
                // Maneja el caso en el que no se pueda obtener la información
                console.error('Error al obtener la información del estudiante.');
        }
    },
    error: function (xhr, status, error) {
            console.error('Error en la solicitud AJAX:', error);
        }
    });
    
});
</script>

@stop