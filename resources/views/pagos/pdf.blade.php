
 <div class="container-fluid p-4">
        <div class="card">
            <div class="card-header justify-content-between">
                <div class="row justify-content-between">
                    <div class="col-xs-4 my-auto">
                        <h3 class="card-title my-auto">
                            <strong>LISTA DE PAGOS</strong>
                            
                        </h3>
                    </div>
                </div>

            </div>

            <div class="card-body p-0">

                <table class="table table-hover table-head-fixed">
                    <thead class="table-light ">
                        <tr>
                            <th>ID</th>
                            <th>NOMBRES</th>
                            <th>APELLIDOS</th>
                            <th>MESES PAGADOS</th>
                            <th>CARRERA-NIVEL</th>
                           

                        </tr>
                    </thead>


                    <tbody>
                        @foreach($pagos as $pago)
                        <tr>
                            <td>{{ $pago->id }}</td>
                            <td>{{ $pago->estudiante->nombre }}</td>
                            <td>{{ $pago->estudiante->apellidos }}</td>
                            <td>{{ $pago->mes_pago }}</td>
                            <td>
                                @if($pago->estudiante->carreras->isNotEmpty())
                                {{ $pago->estudiante->carreras->first()->carrera_nombre . ' - ' .
                                $pago->estudiante->carreras->first()->nivel_nombre }}
                            @else
                                Sin carrera
                            @endif
                            </td>
                            
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

        </div>
    </div>




