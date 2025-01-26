<?php

namespace App\Http\Controllers;

use App\Models\carreras;
use App\Models\Estudiantes;
use App\Models\niveles;
use App\Models\pagos;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Log;

class PagosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $pago = pagos::all();
        //$pagos = Pagos::with(['estudiante.carreras'])->get();
        return view('pagos.index', compact('pago'));
    }
    public function pdf()
    {
        $pagos = Pagos::all();
        $pdf = Pdf::loadView('pagos.pdf', compact('pagos'));
        return $pdf->stream();
    }
    public function lista()
    {
        $pago = Pagos::with([
            'matricula.estudianteCarrera.estudiante',
            'matricula.estudianteCarrera.carrera.nivel'
        ])->get();

        $totalPagos = $pago->sum('monto');

        // Convertimos la colección a un formato más conveniente para la vista
        $pagoConDetalles = $pago->map(function ($pagoItem) {
            return [
                'id' => $pagoItem->pago_id,
                'matricula_id' => $pagoItem->matricula->id, // Agregar el ID de matrícula
                'estudiante' => $pagoItem->matricula->estudianteCarrera->estudiante,
                'carrera' => $pagoItem->matricula->estudianteCarrera->carrera,
                'nivel' => $pagoItem->matricula->estudianteCarrera->carrera->nivel,
                'meses_pagados' => explode(',', $pagoItem->mes_pago), // Convertir los meses en un array
                'concepto' => $pagoItem->concepto,
                'fecha' => $pagoItem->fecha,
                'monto' => $pagoItem->monto
            ];
        });

        // Agrupar los pagos por el id de matricula
        $pagosAgrupados = $pagoConDetalles->groupBy('matricula_id');


        return view('pagos.lista', compact('pagoConDetalles','totalPagos'));
    }

    public function search(Request $request)
    {

        if ($request->ajax()) {

            $data = Estudiantes::where('id', 'like', '%' . $request->search . '%')
                ->orwhere('nombre', 'like', '%' . $request->search . '%')
                ->orwhere('apellidos', 'like', '%' . $request->search . '%')->get();

            return response()->json($data);
        }
    }

    public function getEstudianteInfo($id)
    {
        $estudiante = Estudiantes::find($id);

        if (!$estudiante) {
            return response()->json(['success' => false, 'message' => 'Estudiante no encontrado'], 404);
        }

        // Obtener el ID de la carrera y el nivel
        $carreraNivelIds = explode('_', $estudiante->carrera_nivel);
        $carreraId = $carreraNivelIds[0];
        $nivelId = $carreraNivelIds[1];

        // Buscar el nombre de la carrera y el nivel
        $carrera = Carreras::find($carreraId);
        $nivel = niveles::find($nivelId);

        if (!$carrera || !$nivel) {
            return response()->json(['success' => false, 'message' => 'Carrera o nivel no encontrado'], 404);
        }

        // Combinar el nombre de la carrera y el nivel
        $carreraNivel = $carrera->nombre . ' - ' . $nivel->nombre;

        return response()->json([
            'success' => true,
            'carreraNivel' => $carreraNivel,
        ]);
    }





    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function create()
    {
        $pago = pagos::get();
        return view('pagos.index', compact('pago'));
    }

    //$pagos = Pagos::with(['estudiante.carreras'])->get();


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)

    {
        $request->validate([
            'concepto' => 'required',
            'fecha' => 'required',
            'monto' => 'required',
            'meses' => 'required|array', // Asumiendo que 'meses' es un array enviado desde el formulario
            'matricula_id' => 'required|int', // Asegúrate de ajustar el nombre de la tabla si es diferente
        ]);
        // Obtén los nombres de los meses seleccionados
        $selectedMonths = $request->input('meses');

        // Convierte los nombres de los meses en una cadena separada por comas
        $mesesPagados = implode(', ', $selectedMonths);

        // Crear un nuevo objeto Pago con los datos recibidos
        $pago = Pagos::create([
            'concepto' => $request->input('concepto'),
            'fecha' => $request->input('fecha'),
            'monto' => $request->input('monto'),
            'mes_pago' => $mesesPagados,
            'matricula_id' => $request->input('matricula_id'),
        ]);

        // Guardar el pago en la base de datos
        $pago->save();
        // Redireccionar o devolver una respuesta según sea necesario
        return redirect()->route('pagos.show', $pago->pago_id);
    }



    /**
     * Display the specified resource.
     *
     * @param  \App\Models\pagos  $pagos
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // Buscar el pago por su id
        $pago = Pagos::findOrFail($id);

        // Obtener los detalles del estudiante, su carrera, y los meses pagados
        $estudiante = $pago->matricula->estudianteCarrera->estudiante; // Relación entre Pago -> Matricula -> Estudiante
        $carrera = $pago->matricula->estudianteCarrera->carrera; // Relación entre Pago -> Matricula -> Carrera
        $nivel = $pago->matricula->estudianteCarrera->carrera->nivel;
        $mesesPagados = $pago->meses_pagados; // Los meses que el estudiante pagó, los puedes obtener si están almacenados en el pago
        return view('pagos.show', compact('pago', 'estudiante', 'carrera', 'mesesPagados','nivel'));
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\pagos  $pagos
     * @return \Illuminate\Http\Response
     */
    public function edit(pagos $pagos)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\pagos  $pagos
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, pagos $pagos)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\pagos  $pagos
     * @return \Illuminate\Http\Response
     */
    public function destroy(pagos $pagos)
    {
        //
    }
}
