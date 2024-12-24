<?php

namespace App\Http\Controllers;

use App\Models\niveles;

use Illuminate\Http\Request;

class NivelesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $niveles= Niveles::orderby('id', 'asc')->paginate(9);
        return view('niveles.index',compact('niveles'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $niveles = niveles::get();
        return view('niveles.create',compact('niveles'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $nivel = Niveles::create($request->all());
        return redirect()->route('niveles.index', $nivel);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\niveles  $niveles
     * @return \Illuminate\Http\Response
     */
    public function show(niveles $niveles)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\niveles  $niveles
     * @return \Illuminate\Http\Response
     */
    public function edit(niveles $niveles)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\niveles  $niveles
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, niveles $niveles)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\niveles  $niveles
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        
        // Buscar el nivel por su ID
        $nivel = Niveles::find($id);

        // Verificar si el nivel existe
        if (!$nivel) {
            return redirect()->route('niveles.index')->with('error', 'Nivel no encontrado');
        }

        // Eliminar el nivel
        $nivel->delete();

        // Redireccionar a la vista de index con un mensaje de éxito
        return redirect()->route('niveles.index')->with('success', 'Nivel eliminado correctamente');
    }
}
