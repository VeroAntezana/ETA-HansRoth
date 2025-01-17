<?php

namespace App\Http\Controllers;

use App\Models\carrera_Estudiantes;
use App\Models\Carreras;
use App\Models\Estudiantes;
use App\Models\Gestion;
use App\Models\Niveles;
use App\Models\Matricula;
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
        $estudiantes = Estudiantes::with('carreras', 'matriculas.gestion')->orderBy('estudiante_id', 'asc')->paginate(9);
        $gestiones = Gestion::all();
        $carreras = Carreras::with('nivel')->get();
        return view('estudiantes.index', compact('estudiantes', 'gestiones', 'carreras'));
    }


    public function indexByNivel($carrera_nombre, $nivel)
    {
        // Busca la carrera según el nombre
        $carrera = Carreras::where('nombre', str_replace('-', ' ', $carrera_nombre))->first();

        if (!$carrera) {
            $estudiantes = collect(); // Colección vacía
            $gestiones = Gestion::all();
            return view('estudiantes.index_carrera_nivel', compact('estudiantes', 'carrera', 'nivel', 'gestiones'))
                ->with('error', 'La carrera especificada no existe.');
        }

        // Busca el nivel según el nombre
        $nivel = niveles::where('nombre', $nivel)->first();

        if (!$nivel) {
            return redirect()->route('estudiantes.index')->with('error', 'El nivel especificado no existe.');
        }

        // Filtra estudiantes por carrera y nivel
        $estudiantes = Estudiantes::whereHas('carreras', function ($query) use ($carrera, $nivel) {
            $query->where('carrera.carrera_id', $carrera->carrera_id) // Filtra por carrera_id
                  ->where('carrera.nivel_id', $nivel->nivel_id);      // Filtra por nivel_id
        })
            ->with([
                'carreras' => function ($query) {
                    $query->with('nivel'); // Relación con niveles
                },
                'matriculas' => function ($query) {
                    $query->with('gestion'); // Relación con gestiones
                }
            ])
            ->get();


        $gestiones = Gestion::all();

        if ($estudiantes->isEmpty()) {
            return view('estudiantes.index_carrera_nivel', compact('estudiantes', 'carrera', 'nivel', 'gestiones'))
                ->with('info', 'No hay estudiantes registrados para esta carrera y nivel.');
        }

        return view('estudiantes.index_carrera_nivel', compact('estudiantes', 'carrera', 'nivel', 'gestiones'));
    }






    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $estudiantes = Estudiantes::all();
        $carreras = Carreras::with('nivel')->get();
        $gestiones = Gestion::all();

        return view('estudiantes.create', compact('estudiantes', 'carreras', 'gestiones'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Validar los datos del request, excepto la unicidad del CI
        $request->validate([
            'nombre' => 'required',
            'apellidos' => 'required',
            'fecha_nacimiento' => 'required|date',
            'ci' => 'required',
            'sexo' => 'required|in:M,F',
            'carrera_id' => 'required|exists:carrera,carrera_id',
            'gestion_id' => 'required|exists:gestion,gestion_id',
        ]);

        // Comprobar si el estudiante ya existe por su CI
        $estudianteExistente = Estudiantes::where('ci', $request->input('ci'))->first();

        if ($estudianteExistente) {
            // Si el estudiante existe, cargar sus datos, incluidas las carreras
            $datosEstudiante = Estudiantes::with('carreras')
                ->where('ci', $request->input('ci'))
                ->first();

            return redirect()->route('estudiantes.index')->with('error', 'El estudiante ya existe, Matricule en otra carrera');
        }

        // Si el estudiante no existe, proceder a crearlo
        $estudiante = Estudiantes::create([
            'nombre' => $request->input('nombre'),
            'apellidos' => $request->input('apellidos'),
            'fecha_nacimiento' => $request->input('fecha_nacimiento'),
            'ci' => $request->input('ci'),
            'sexo' => $request->input('sexo'),
        ]);

        // Crear la relación con la carrera
        $carreraEstudiante = carrera_Estudiantes::create([
            'estudiante_id' => $estudiante->estudiante_id,
            'carrera_id' => $request->input('carrera_id'),
            'fecha_inscripcion' => now(),
        ]);

        // Crear la matrícula
        Matricula::create([
            'estudiante_carrera_id' => $carreraEstudiante->estudiante_carrera_id,
            'gestion_id' => $request->input('gestion_id'),
            'fecha_matricula' => now(),
            'estado' => 'Activa',
        ]);

        // Retornar éxito si el estudiante fue creado
        return redirect()->route('estudiantes.index')->with('success', 'Estudiante creado exitosamente.');
    }

    public function MatricularEstudianteAntiguo(Request $request)
    {

        // Validar los datos del request, excepto la unicidad del CI
        $request->validate([
            'estudiante_id'=> 'required',
            'carrera_id' => 'required',
            'gestion_id' => 'required',
        ]);

        

        // Crear la relación con la carrera
        $carreraEstudiante = carrera_Estudiantes::create([
            'estudiante_id' => $request->estudiante_id,
            'carrera_id' => $request->input('carrera_id'),
            'fecha_inscripcion' => now(),
        ]);

        // Crear la matrícula
        Matricula::create([
            'estudiante_carrera_id' => $carreraEstudiante->estudiante_carrera_id,
            'gestion_id' => $request->input('gestion_id'),
            'fecha_matricula' => now(),
            'estado' => 'Activa',
        ]);

        // Retornar éxito si el estudiante fue creado
        return redirect()->route('estudiantes.index')->with('success', 'Estudiante Matriculado exitosamente.');
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

    public function buscarEstudiante(Request $request)
    {
        $texto = trim($request->q);

        // Búsqueda en el modelo Estudiante con relaciones
        $resultado = Estudiantes::with(['carreras.nivel', 'matriculas.gestion'])
            ->where(function ($query) use ($texto) {
                $query->where('ci', 'like', "%$texto%")
                    ->orWhereRaw("LOWER(CONCAT(nombre, ' ', apellidos)) LIKE LOWER(?)", ["%$texto%"])
                    ->orWhereRaw("LOWER(CONCAT(apellidos, ' ', nombre)) LIKE LOWER(?)", ["%$texto%"]);
            })
            ->orderBy('estudiante_id', 'desc')
            ->get()
            ->map(function ($estudiante) {
                $nombreCompleto = "{$estudiante->nombre} {$estudiante->apellidos}";
                $carreras = $estudiante->carreras->map(function ($carrera) {
                    return [
                        'id_carrera'=> $carrera->carrera_id,
                        'nombre_carrera' => $carrera->nombre,
                        'nivel' => $carrera->nivel->nombre ?? 'Sin nivel',
                    ];
                });

                $gestiones = $estudiante->matriculas->map(function ($matricula) {
                    return [
                        'gestion' => $matricula->gestion->descripcion ?? 'Sin gestión',
                        'fecha_inicio' => $matricula->gestion->fecha_inicio ?? null,
                        'fecha_fin' => $matricula->gestion->fecha_fin ?? null,
                    ];
                });

                return [
                    'id_estudiante' => $estudiante->estudiante_id,
                    'nombre_completo' => $nombreCompleto,
                    'ci' => $estudiante->ci,
                    'sexo' => $estudiante->sexo,
                    'fecha_nacimiento' => $estudiante->fecha_nacimiento,
                    'carreras' => $carreras,
                    'gestiones' => $gestiones,
                ];
            });

        return response()->json($resultado);
    }
}
