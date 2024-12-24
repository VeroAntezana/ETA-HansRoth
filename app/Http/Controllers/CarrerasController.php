<?php

namespace App\Http\Controllers;

use App\Models\Carreras;
use App\Models\niveles;
use Illuminate\Http\Request;

class CarrerasController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
       // $carreras= Carreras::orderby('id', 'asc')->paginate(9);
       $carreras = Carreras::with('niveles')->orderBy('id', 'asc')->paginate(9);
       $niveles=niveles::all();
        return view('carreras.index',compact('carreras','niveles'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    { //return view('niveles.create',compact('niveles'))
       $niveles = niveles::all();
      // dd($niveles);
       return view('partials.carreras.form_create', compact('niveles'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $carrera = Carreras::create($request->all());
        $carrera->niveles()->sync($request->input('nivel_id', []));
        return redirect()->route('carreras.index', $carrera);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\carreras  $carreras
     * @return \Illuminate\Http\Response
     */
    public function show(carreras $carreras)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\carreras  $carreras
     * @return \Illuminate\Http\Response
     */
    public function edit(carreras $carreras)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\carreras  $carreras
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, carreras $carreras)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\carreras  $carreras
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
         // Buscar el nivel por su ID
         $carrera = Carreras::find($id);

         // Verificar si el nivel existe
         if (!$carrera) {
             return redirect()->route('carreras.index')->with('error', 'Carrera no encontrada');
         }
 
         // Eliminar el nivel
         $carrera->delete();
 
         // Redireccionar a la vista de index con un mensaje de éxito
         return redirect()->route('carreras.index')->with('success', 'Carrera eliminada correctamente');
    }
}
