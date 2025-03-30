<?php

namespace App\Http\Controllers;

use App\Models\carrera_Estudiantes;
use App\Models\Carreras;
use App\Models\Estudiantes;
use App\Models\Gestion;
use App\Models\niveles;
use App\Models\Matricula;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

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
        $carreras = carreras::with('nivel')->get();
        return view('estudiantes.index', compact('estudiantes', 'gestiones', 'carreras'));
    }


    public function indexByNivel($carrera_nombre, $nivel_nombre)
    {
        // Busca la carrera según el nombre
        $carrera = DB::table('carrera')
            ->join('nivel', 'carrera.nivel_id', '=', 'nivel.nivel_id')
            ->select('carrera.*', 'nivel.nombre as nivel_nombre')
            ->where('carrera.nombre', str_replace('-', ' ', $carrera_nombre))
            ->where('nivel.nombre', $nivel_nombre)
            ->first();
        // Busca el nivel según el nombre
        $nivel = niveles::where('nombre', $nivel_nombre)->first();

        if (!$carrera) {
            $estudiantes = collect(); // Colección vacía
            $gestiones = Gestion::all();
            return view('estudiantes.index_carrera_nivel', compact('estudiantes', 'carrera', 'nivel', 'gestiones'))
                ->with('error', 'La carrera especificada no existe.');
        }



        if (!$nivel) {
            return redirect()->route('estudiantes.index')->with('error', 'El nivel especificado no existe.');
        }

        // Llamada al procedimiento almacenado
        $estudiantes = DB::select('CALL ObtenerEstudiantesPorCarrera(?)', [$carrera->carrera_id]);


        $gestiones = Gestion::all();

        if (empty($estudiantes)) {
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
            'estudiante_id' => 'required',
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
        return view('estudiantes.edit', compact('estudiante'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Estudiantes  $estudiantes
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $estudiante = Estudiantes::findOrFail($id);

        $request->validate([
            'nombre' => 'required',
            'apellidos' => 'required',
            'fecha_nacimiento' => 'required|date',
            'ci' => 'required',
            'sexo' => 'required|in:M,F',
        ]);

        $estudiante->update($request->all());
        return redirect()->route('estudiantes.index')->with('success', 'Estudiante actualizado exitosamente');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Estudiantes  $estudiantes
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            DB::beginTransaction();

            $estudiante = Estudiantes::findOrFail($id);

            // Delete matriculas first
            DB::table('matricula')
                ->join('estudiante_carrera', 'matricula.estudiante_carrera_id', '=', 'estudiante_carrera.estudiante_carrera_id')
                ->where('estudiante_carrera.estudiante_id', $id)
                ->delete();

            // Delete estudiante_carrera records
            DB::table('estudiante_carrera')
                ->where('estudiante_id', $id)
                ->delete();

            // Finally delete the estudiante
            $estudiante->delete();

            DB::commit();
            return redirect()->route('estudiantes.index')->with('success', 'Estudiante eliminado exitosamente');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->route('estudiantes.index')->with('error', 'Error al eliminar el estudiante');
        }
    }

    public function buscarEstudiante(Request $request)
    {
        $texto = trim($request->q);

        $resultado = Estudiantes::with([
            'estudianteCarreras.carrera.nivel',
            'estudianteCarreras.matriculas.gestion',
            'estudianteCarreras.matriculas.pagos'
        ])
            ->where(function ($query) use ($texto) {
                $query->where('ci', 'like', "%$texto%")
                    ->orWhereRaw("LOWER(CONCAT(nombre, ' ', apellidos)) LIKE LOWER(?)", ["%$texto%"])
                    ->orWhereRaw("LOWER(CONCAT(apellidos, ' ', nombre)) LIKE LOWER(?)", ["%$texto%"]);
            })
            ->orderBy('estudiante_id', 'desc')
            ->get()
            ->map(function ($estudiante) {
                $nombreCompleto = "{$estudiante->nombre} {$estudiante->apellidos}";
                $carreras = $estudiante->estudianteCarreras->map(function ($estudianteCarrera) {
                    $carrera = $estudianteCarrera->carrera;
                    $duracionMeses = $carrera->duracion_meses;
                    $matriculas = $estudianteCarrera->matriculas;

                    return [
                        'id_carrera' => $carrera->carrera_id,
                        'nombre_carrera' => $carrera->nombre,
                        'nivel' => $carrera->nivel->nombre ?? 'Sin nivel',
                        'matriculas' => $matriculas->map(function ($matricula) use ($duracionMeses) {
                            $mesesPagados = $matricula->pagos->pluck('mes_pago')->unique();
                            $modulosPagados = $mesesPagados->map(function ($item) {
                                return explode(', ', $item);
                            })->flatten()->unique(); // Meses ya pagados

                            // Generar todos los meses esperados basados en la duración de la carrera
                            $todosLosMeses = collect();
                            for ($i = 1; $i <= $duracionMeses; $i++) {
                                $todosLosMeses->push("Mod $i");
                            }

                            $mesesPendientes = $todosLosMeses->diff($modulosPagados); // Filtro para obtener pendientes

                            return [
                                'id_matricula' => $matricula->matricula_id,
                                'gestion' => $matricula->gestion->descripcion ?? 'Sin gestión',
                                'meses_pagados' => $modulosPagados->values(), // Meses ya pagados
                                'meses_pendientes' => $mesesPendientes->values(), // Meses pendientes
                            ];
                        }),
                    ];
                });

                return [
                    'id_estudiante' => $estudiante->estudiante_id,
                    'nombre_completo' => $nombreCompleto,
                    'ci' => $estudiante->ci,
                    'sexo' => $estudiante->sexo,
                    'fecha_nacimiento' => $estudiante->fecha_nacimiento,
                    'carreras' => $carreras,
                ];
            });

        return response()->json($resultado);
    }


    public function exportExcel(Request $request)
    {
        try {
            $query = Estudiantes::with('carreras');

            // Si se seleccionó una carrera específica
            if ($request->carrera_id && $request->carrera_id !== 'todas') {
                $query->whereHas('carreras', function ($q) use ($request) {
                    $q->where('carrera.carrera_id', $request->carrera_id);
                });
            }

            $estudiantes = $query->get();

            $datosExcel = [];
            foreach ($estudiantes as $estudiante) {
                $carrerasNivel = [];
                foreach ($estudiante->carreras as $carrera) {
                    // Si es todas las carreras o es la carrera seleccionada
                    if ($request->carrera_id === 'todas' || $carrera->carrera_id == $request->carrera_id) {
                        $carrerasNivel[] = $carrera->nombre . ' - ' . optional($carrera->nivel)->nombre;
                    }
                }

                $datosExcel[] = [
                    'ID' => $estudiante->estudiante_id,
                    'NOMBRES' => $estudiante->nombre,
                    'APELLIDOS' => $estudiante->apellidos,
                    'CARNET IDENTIDAD' => $estudiante->ci,
                    'CARRERA-NIVEL' => implode(', ', $carrerasNivel)
                ];
            }

            // Nombre del archivo según el filtro
            $nombreArchivo = 'estudiantes';
            if ($request->carrera_id && $request->carrera_id !== 'todas') {
                $carrera = Carreras::find($request->carrera_id);
                $nombreArchivo .= '_' . str_replace(' ', '_', $carrera->nombre) .
                    '_' . str_replace(' ', '_', $carrera->nivel->nombre);
            }
            $nombreArchivo .= '.xlsx';

            return Excel::download(new class(collect($datosExcel)) implements FromCollection, WithHeadings {
                protected $datos;

                public function __construct($datos)
                {
                    $this->datos = $datos;
                }

                public function collection()
                {
                    return $this->datos;
                }

                public function headings(): array
                {
                    return [
                        'ID',
                        'NOMBRES',
                        'APELLIDOS',
                        'CARNET IDENTIDAD',
                        'CARRERA-NIVEL'
                    ];
                }
            }, $nombreArchivo);
        } catch (\Exception $e) {
            return back()->with('error', 'Error al exportar: ' . $e->getMessage());
        }
    }
}
