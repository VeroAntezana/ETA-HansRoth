<?php

namespace App\Http\Controllers;

use App\Models\Egreso;
use Illuminate\Http\Request;
use App\Models\Gestion;
use Carbon\Carbon;

class EgresoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)    
    {
        // Obtener fechas del formulario
        $fechaInicio = $request->input('fecha_inicio');
        $fechaFin = $request->input('fecha_fin');

        // Consulta de egresos con filtro de fechas
        $query = Egreso::query();

        if ($fechaInicio && $fechaFin) {
            $query->whereBetween('fecha', [
                Carbon::parse($fechaInicio)->startOfDay(),
                Carbon::parse($fechaFin)->endOfDay()
            ]);
        }
        $egresos = $query->orderBy('fecha', 'desc')->get();
        $totalEgresos = $query->sum('monto');
        $gestiones = Gestion::all();
        return view('egresos.index', compact('egresos', 'gestiones','totalEgresos'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $gestiones = Gestion::all();
        return view('egresos.create', compact('gestiones',));
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required',
            'fecha' => 'required|date',
            'monto' => 'required|numeric',
            'gestion_id' => 'required',
            'concepto' => 'required'
        ]);

        $data = $request->all();
        $data['fecha'] = Carbon::parse($request->fecha)->format('Y-m-d H:i:s');
        Egreso::create($data);
        return redirect()->route('egresos.index')->with('success', 'Egreso registrado exitosamente');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Egreso  $egreso
     * @return \Illuminate\Http\Response
     */
    public function show(Egreso $egreso)
    {
        //
    }

    public function print($egreso_id)
    {
        $egreso = Egreso::findOrFail($egreso_id);
        return view('egresos.print', compact('egreso'));
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Egreso  $egreso
     * @return \Illuminate\Http\Response
     */
    public function edit(Egreso $egreso)
    {
        $gestiones = Gestion::all();
        return view('egresos.edit', compact('egreso', 'gestiones'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Egreso  $egreso
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $egreso_id)
    {
        $egreso = Egreso::findOrFail($egreso_id);
        $egreso->update($request->validate([
            'nombre' => 'required',
            'fecha' => 'required|date',
            'monto' => 'required|numeric',
            'gestion_id' => 'required',
            'concepto' => 'required'
        ]));
        return redirect()->route('egresos.index')->with('success', ' actualizado exitosamente');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Egreso  $egreso
     * @return \Illuminate\Http\Response
     */
    public function destroy(Egreso $egreso)
    {
        $egreso->delete();
        return redirect()->route('egresos.index')->with('success', 'Egreso eliminado exitosamente');
    }
}
