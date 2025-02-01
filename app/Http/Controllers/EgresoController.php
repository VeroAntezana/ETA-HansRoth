<?php

namespace App\Http\Controllers;

use App\Models\Egreso;
use Illuminate\Http\Request;
use App\Models\Gestion;

class EgresoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $egresos = Egreso::orderBy('fecha', 'desc')->get();
        $gestiones = Gestion::all();
        return view('egresos.index', compact('egresos', 'gestiones'));
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

        Egreso::create($request->all());
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
            'gestion_id'=> 'required',
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
