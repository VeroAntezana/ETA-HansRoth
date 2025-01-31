<?php

namespace App\Http\Controllers;

use App\Models\Egreso;
use App\Models\pagos;
use Illuminate\Http\Request;

class reportesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $pago = pagos::with([
            'matricula.estudianteCarrera.estudiante',
            'matricula.estudianteCarrera.carrera.nivel'
        ])->get();

        $totalPagos = $pago->sum('monto');
        $totalegresos  = Egreso::sum('monto');

        // Convertimos la colección a un formato más conveniente para la vista
        $pagoConDetalles = $pago->map(function ($pago) {
            $estudiante = $pago->matricula->estudianteCarrera->estudiante;
            $carrera = $pago->matricula->estudianteCarrera->carrera;
            $nivel = $carrera->nivel;

            return [
                'id' => $pago->pago_id,
                'fecha' => $pago->fecha,
                'detalle' => sprintf(
                    "%s %s, Meses Pagados: %s, Carrera y Nivel: %s - %s",
                    $estudiante->nombre,
                    $estudiante->apellidos,
                    $pago->mes_pago,
                    $carrera->nombre,
                    $nivel->nombre
                ),
                'ingreso' => $pago->monto
            ];
        });

        return view('Reportes.index', compact('pagoConDetalles', 'totalPagos','totalegresos'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    public function index_egreso() {
        return view('reportes.index-egreso');
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
