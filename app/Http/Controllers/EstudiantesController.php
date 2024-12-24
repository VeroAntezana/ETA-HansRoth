<?php

namespace App\Http\Controllers;
use App\Models\Carreras;
use App\Models\Estudiantes;
use App\Models\Gestion;
use App\Models\Niveles;
use App\Models\carrera_niveles;
use Illuminate\Http\Request;

class EstudiantesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $estudiantes = Estudiantes::with('carreraNivel', 'gestion')->orderBy('id', 'asc')->paginate(9);
        $carreras_niveles = carrera_niveles::with('carrera', 'nivel')->get(); // Aquí obtenemos los datos de carrera_niveles
        $gestiones = Gestion::all();
        return view('estudiantes.index', compact('estudiantes', 'carreras_niveles', 'gestiones'));
    }
    
   
    public function indexByNivel($carrera_nombre, $nivel)
    {
        // Busca la carrera según el nombre
        $carrera = Carreras::where('nombre', str_replace('-', ' ', $carrera_nombre))->first();
        
        // Si no existe la carrera, retorna con un mensaje de error
          // Si la carrera no existe, crea un mensaje para la vista y muestra la lista vacía
        if (!$carrera) {
         $estudiantes = collect(); // Colección vacía
            $gestiones = Gestion::all();
         return view('estudiantes.index_carrera_nivel', compact('estudiantes', 'carrera', 'nivel', 'gestiones'))
            ->with('error', 'La carrera especificada no existe.');
    }
    
        // Busca el nivel según el nombre
        $nivel = Niveles::where('nombre', $nivel)->first();
    
        // Si no existe el nivel, retorna con un mensaje de error
        if (!$nivel) {
            return redirect()->route('estudiantes.index')->with('error', 'El nivel especificado no existe.');
        }
    
        // Filtra estudiantes por carrera y nivel
        $estudiantes = Estudiantes::whereHas('carreraNivel', function ($query) use ($carrera, $nivel) {
            $query->where('carrera_id', $carrera->id)
                  ->where('nivel_id', $nivel->id);
        })
        ->with('carreraNivel', 'gestion')
        ->get();
    
        // Si no hay estudiantes encontrados, mostrar un mensaje
        if ($estudiantes->isEmpty()) {
            return view('estudiantes.index_carrera_nivel', compact('estudiantes', 'carrera', 'nivel'))
                ->with('info', 'No hay estudiantes registrados para esta carrera y nivel.');
        }
    
        // Si hay estudiantes, retornamos la vista con los estudiantes filtrados
        return view('estudiantes.index_carrera_nivel', compact('estudiantes', 'carrera', 'nivel'));
    }
    


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $estudiantes = Estudiantes::get();
        $carreras_niveles = carrera_niveles::with('carrera', 'nivel')->get();
         $gestiones = Gestion::all();
        return view('estudiantes.create', compact('estudiantes','carreras_niveles','gestiones'));
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
        'apellidos' => 'required',
        'fecha_nacimiento' => 'required',
        'carnet' => 'required',
        'sexo' => 'required',
        'carrera_nivel' => 'required', 
        'gestion_id' => 'required',
    ]);
    
    // Crear el estudiante
    $estudiante = Estudiantes::create($request->all());

    // Redirigir al índice general con un mensaje de éxito
    return redirect()->route('estudiantes.index')->with('success', 'Estudiante creado exitosamente.');
}
    

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Estudiantes  $estudiantes
     * @return \Illuminate\Http\Response
     */
    public function show(Estudiantes $estudiantes)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Estudiantes  $estudiantes
     * @return \Illuminate\Http\Response
     */
    public function edit(Estudiantes $estudiantes)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Estudiantes  $estudiantes
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Estudiantes $estudiantes)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Estudiantes  $estudiantes
     * @return \Illuminate\Http\Response
     */
    public function destroy(Estudiantes $estudiantes)
    {
        //
    }
    
}
