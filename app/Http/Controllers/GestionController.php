<?php

namespace App\Http\Controllers;

use App\Models\Gestion;
use Illuminate\Http\Request;

class GestionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $gestiones = Gestion::orderBy('gestion_id', 'asc')->paginate(9);
        return view('gestiones.index', compact('gestiones'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $gestiones = Gestion::get();
        return view('gestiones.create', compact('gestiones'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $gestion = Gestion::create($request->all());
        return redirect()->route('gestiones.index', $gestion);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Gestion  $gestion
     * @return \Illuminate\Http\Response
     */
    public function show(Gestion $gestion)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Gestion  $gestion
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $gestion = Gestion::findOrFail($id);
        return view('partials.gestiones.form_edit', compact('gestion'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Gestion  $gestion
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $gestion = Gestion::findOrFail($id);
        $gestion->update($request->all());
        return redirect()->route('gestiones.index')->with('success', 'Gestión actualizada exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Gestion  $gestion
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // Buscar la gestión por su ID
        $gestion = Gestion::find($id);

        // Verificar si la gestión existe
        if (!$gestion) {
            return redirect()->route('gestiones.index')->with('error', 'Gestión no encontrada');
        }

        // Eliminar la gestión
        $gestion->delete();

        // Redireccionar a la vista de índice con un mensaje de éxito
        return redirect()->route('gestiones.index')->with('success', 'Gestión eliminada correctamente');
    }
}
