<?php

namespace App\Http\Controllers;

use App\Models\carrera_Estudiantes;
use App\Models\Carreras;
use App\Models\Estudiantes;
use App\Models\Matricula;
use App\Models\niveles;
use App\Support\GestionContextResolver;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class EstudiantesController extends Controller
{
    private GestionContextResolver $gestionResolver;

    public function __construct(GestionContextResolver $gestionResolver)
    {
        $this->gestionResolver = $gestionResolver;
    }

    public function index(Request $request)
    {
        $context = $this->gestionResolver->resolve($request->input('gestion_id'));

        $query = Estudiantes::with('carreras', 'matriculas.gestion')->orderBy('estudiante_id', 'asc');
        if ($context['gestionActiva']) {
            $gestionId = $context['gestionActiva']->gestion_id;
            $query->whereHas('matriculas', function ($q) use ($gestionId) {
                $q->where('gestion_id', $gestionId);
            });
        } else {
            $query->whereRaw('1 = 0');
        }

        $estudiantes = $query->paginate(9)->appends($request->query());
        $gestiones = $context['gestiones'];
        $carreras = Carreras::with('nivel')->get();
        $gestionActiva = $context['gestionActiva'];
        $gestionAlert = $context['gestionAlert'];

        return view('estudiantes.index', compact('estudiantes', 'gestiones', 'carreras', 'gestionActiva', 'gestionAlert'));
    }

    public function indexByNivel($carrera_nombre, $nivel_nombre)
    {
        $context = $this->gestionResolver->resolve(request()->input('gestion_id'));

        $carrera = DB::table('carrera')
            ->join('nivel', 'carrera.nivel_id', '=', 'nivel.nivel_id')
            ->select('carrera.*', 'nivel.nombre as nivel_nombre')
            ->where('carrera.nombre', str_replace('-', ' ', $carrera_nombre))
            ->where('nivel.nombre', $nivel_nombre)
            ->first();

        $nivel = niveles::where('nombre', $nivel_nombre)->first();

        $gestiones = $context['gestiones'];
        $gestionActiva = $context['gestionActiva'];
        $gestionAlert = $context['gestionAlert'];

        if (!$carrera) {
            $estudiantes = collect();
            return view('estudiantes.index_carrera_nivel', compact('estudiantes', 'carrera', 'nivel', 'gestiones', 'gestionActiva', 'gestionAlert'))
                ->with('error', 'La carrera especificada no existe.');
        }

        if (!$nivel) {
            return redirect()->route('estudiantes.index')->with('error', 'El nivel especificado no existe.');
        }

        if ($gestionActiva) {
            $estudiantes = DB::table('estudiante as e')
                ->join('estudiante_carrera as ec', 'e.estudiante_id', '=', 'ec.estudiante_id')
                ->join('carrera as c', 'ec.carrera_id', '=', 'c.carrera_id')
                ->join('nivel as n', 'c.nivel_id', '=', 'n.nivel_id')
                ->join('matricula as m', 'm.estudiante_carrera_id', '=', 'ec.estudiante_carrera_id')
                ->where('c.carrera_id', $carrera->carrera_id)
                ->where('m.gestion_id', $gestionActiva->gestion_id)
                ->select(
                    'e.estudiante_id as ID_Estudiante',
                    'e.nombre as NombreEstudiante',
                    'e.apellidos as ApellidosEstudiante',
                    'e.ci as CI',
                    'c.nombre as NombreCarrera',
                    'n.nombre as Nivel'
                )
                ->distinct()
                ->get();
        } else {
            $estudiantes = collect();
        }

        if (empty($estudiantes)) {
            return view('estudiantes.index_carrera_nivel', compact('estudiantes', 'carrera', 'nivel', 'gestiones', 'gestionActiva', 'gestionAlert'))
                ->with('info', 'No hay estudiantes registrados para esta carrera y nivel.');
        }

        return view('estudiantes.index_carrera_nivel', compact('estudiantes', 'carrera', 'nivel', 'gestiones', 'gestionActiva', 'gestionAlert'));
    }

    public function create(Request $request)
    {
        $context = $this->gestionResolver->resolve($request->input('gestion_id'));

        $estudiantes = Estudiantes::all();
        $carreras = Carreras::with('nivel')->get();
        $gestiones = $context['gestiones'];
        $gestionActiva = $context['gestionActiva'];
        $gestionAlert = $context['gestionAlert'];

        return view('estudiantes.create', compact('estudiantes', 'carreras', 'gestiones', 'gestionActiva', 'gestionAlert'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required',
            'apellidos' => 'required',
            'fecha_nacimiento' => 'required|date',
            'ci' => 'required',
            'sexo' => 'required|in:M,F',
            'carrera_id' => 'required|exists:carrera,carrera_id',
            'gestion_id' => 'required|exists:gestion,gestion_id',
        ]);

        $estudianteExistente = Estudiantes::where('ci', $request->input('ci'))->first();

        if ($estudianteExistente) {
            return redirect()->route('estudiantes.index')->with('error', 'El estudiante ya existe, Matricule en otra carrera');
        }

        $estudiante = Estudiantes::create([
            'nombre' => $request->input('nombre'),
            'apellidos' => $request->input('apellidos'),
            'fecha_nacimiento' => $request->input('fecha_nacimiento'),
            'ci' => $request->input('ci'),
            'sexo' => $request->input('sexo'),
        ]);

        $carreraEstudiante = carrera_Estudiantes::create([
            'estudiante_id' => $estudiante->estudiante_id,
            'carrera_id' => $request->input('carrera_id'),
            'fecha_inscripcion' => now(),
        ]);

        Matricula::create([
            'estudiante_carrera_id' => $carreraEstudiante->estudiante_carrera_id,
            'gestion_id' => $request->input('gestion_id'),
            'fecha_matricula' => now(),
            'estado' => 'Activa',
        ]);

        return redirect()->route('estudiantes.index')->with('success', 'Estudiante creado exitosamente.');
    }

    public function MatricularEstudianteAntiguo(Request $request)
    {
        $request->validate([
            'estudiante_id' => 'required',
            'carrera_id' => 'required',
            'gestion_id' => 'required',
        ]);

        $carreraEstudiante = carrera_Estudiantes::create([
            'estudiante_id' => $request->estudiante_id,
            'carrera_id' => $request->input('carrera_id'),
            'fecha_inscripcion' => now(),
        ]);

        Matricula::create([
            'estudiante_carrera_id' => $carreraEstudiante->estudiante_carrera_id,
            'gestion_id' => $request->input('gestion_id'),
            'fecha_matricula' => now(),
            'estado' => 'Activa',
        ]);

        return redirect()->route('estudiantes.index')->with('success', 'Estudiante Matriculado exitosamente.');
    }

    public function show(Estudiantes $estudiantes)
    {
        //
    }

    public function edit(Estudiantes $estudiantes)
    {
        return view('estudiantes.edit', compact('estudiante'));
    }

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

    public function destroy($id)
    {
        try {
            DB::beginTransaction();

            $estudiante = Estudiantes::findOrFail($id);

            DB::table('matricula')
                ->join('estudiante_carrera', 'matricula.estudiante_carrera_id', '=', 'estudiante_carrera.estudiante_carrera_id')
                ->where('estudiante_carrera.estudiante_id', $id)
                ->delete();

            DB::table('estudiante_carrera')
                ->where('estudiante_id', $id)
                ->delete();

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
        $context = $this->gestionResolver->resolve($request->input('gestion_id'));

        $query = Estudiantes::with([
            'estudianteCarreras.carrera.nivel',
            'estudianteCarreras.matriculas.gestion',
            'estudianteCarreras.matriculas.pagos'
        ])->where(function ($builder) use ($texto) {
            $builder->where('ci', 'like', "%$texto%")
                ->orWhereRaw("LOWER(CONCAT(nombre, ' ', apellidos)) LIKE LOWER(?)", ["%$texto%"])
                ->orWhereRaw("LOWER(CONCAT(apellidos, ' ', nombre)) LIKE LOWER(?)", ["%$texto%"]);
        });

        if ($context['gestionActiva']) {
            $gestionId = $context['gestionActiva']->gestion_id;
            $query->whereHas('estudianteCarreras.matriculas', function ($q) use ($gestionId) {
                $q->where('gestion_id', $gestionId);
            });
        } else {
            $query->whereRaw('1 = 0');
        }

        $resultado = $query->orderBy('estudiante_id', 'desc')
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
                            })->flatten()->unique();

                            $todosLosMeses = collect();
                            for ($i = 1; $i <= $duracionMeses; $i++) {
                                $todosLosMeses->push("Mod $i");
                            }

                            $mesesPendientes = $todosLosMeses->diff($modulosPagados);

                            return [
                                'id_matricula' => $matricula->matricula_id,
                                'gestion' => $matricula->gestion->descripcion ?? 'Sin gestion',
                                'meses_pagados' => $modulosPagados->values(),
                                'meses_pendientes' => $mesesPendientes->values(),
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
            $context = $this->gestionResolver->resolve($request->input('gestion_id'));
            $query = Estudiantes::with('carreras');

            if ($context['gestionActiva']) {
                $gestionId = $context['gestionActiva']->gestion_id;
                $query->whereHas('matriculas', function ($q) use ($gestionId) {
                    $q->where('gestion_id', $gestionId);
                });
            } else {
                $query->whereRaw('1 = 0');
            }

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

            $nombreArchivo = 'estudiantes';
            if ($request->carrera_id && $request->carrera_id !== 'todas') {
                $carrera = Carreras::find($request->carrera_id);
                $nombreArchivo .= '_' . str_replace(' ', '_', $carrera->nombre) . '_' . str_replace(' ', '_', $carrera->nivel->nombre);
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
