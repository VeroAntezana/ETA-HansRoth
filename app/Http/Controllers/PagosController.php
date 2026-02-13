<?php

namespace App\Http\Controllers;

use App\Models\Carreras;
use App\Models\Estudiantes;
use App\Models\Matricula;
use App\Models\niveles;
use App\Models\pagos;
use App\Support\GestionContextResolver;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class PagosController extends Controller
{
    private GestionContextResolver $gestionResolver;

    public function __construct(GestionContextResolver $gestionResolver)
    {
        $this->gestionResolver = $gestionResolver;
    }

    public function index(Request $request)
    {
        $context = $this->gestionResolver->resolve($request->input('gestion_id'));
        $pago = pagos::all();
        $gestiones = $context['gestiones'];
        $gestionActiva = $context['gestionActiva'];
        $gestionAlert = $context['gestionAlert'];
        return view('pagos.index', compact('pago', 'gestiones', 'gestionActiva', 'gestionAlert'));
    }

    public function pdf()
    {
        $pagos = Pagos::all();
        $pdf = Pdf::loadView('pagos.pdf', compact('pagos'));
        return $pdf->stream();
    }

    public function lista(Request $request)
    {
        $context = $this->gestionResolver->resolve($request->input('gestion_id'));

        $matriculaQuery = Matricula::with([
            'estudianteCarrera.estudiante',
            'estudianteCarrera.carrera.nivel',
            'pagos'
        ]);

        if ($context['gestionActiva']) {
            $matriculaQuery->where('gestion_id', $context['gestionActiva']->gestion_id);
        } else {
            $matriculaQuery->whereRaw('1 = 0');
        }

        $matriculas = $matriculaQuery->get();
        $carreras = Carreras::with('nivel')->get();

        $pagosAgrupados = $matriculas->map(function ($matricula) {
            $estudiante = $matricula->estudianteCarrera->estudiante ?? null;
            $carrera = $matricula->estudianteCarrera->carrera ?? null;
            $nivel = $carrera->nivel ?? null;
            $pagos = $matricula->pagos ?? collect();

            $string_modulos = $pagos->sortBy('fecha')->pluck('mes_pago')->filter()->join(', ');
            $array_modulos = $string_modulos === '' ? [] : explode(', ', $string_modulos);

            return [
                'matricula_id' => $matricula->matricula_id ?? null,
                'ids_pagos' => $pagos->pluck('pago_id')->join(', '),
                'carrera_id' => $carrera->carrera_id ?? null,
                'nombre' => $estudiante->nombre ?? 'Desconocido',
                'apellidos' => $estudiante->apellidos ?? '',
                'meses_pagos' => array_map(fn($item) => (int) str_replace('Mod ', '', $item), $array_modulos),
                'carrera_nivel' => $carrera ? ($carrera->nombre . ' - ' . $nivel->nombre) : 'N/A',
                'duracion_carrera' => $carrera->duracion_meses ?? 0,
                'total_pagado' => $pagos->sum('monto')
            ];
        });

        $totalPagos = $pagosAgrupados->sum('total_pagado');
        $gestiones = $context['gestiones'];
        $gestionActiva = $context['gestionActiva'];
        $gestionAlert = $context['gestionAlert'];

        return view('pagos.lista', compact('pagosAgrupados', 'totalPagos', 'carreras', 'gestiones', 'gestionActiva', 'gestionAlert'));
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

        $carreraNivelIds = explode('_', $estudiante->carrera_nivel);
        $carreraId = $carreraNivelIds[0];
        $nivelId = $carreraNivelIds[1];

        $carrera = Carreras::find($carreraId);
        $nivel = niveles::find($nivelId);

        if (!$carrera || !$nivel) {
            return response()->json(['success' => false, 'message' => 'Carrera o nivel no encontrado'], 404);
        }

        $carreraNivel = $carrera->nombre . ' - ' . $nivel->nombre;

        return response()->json([
            'success' => true,
            'carreraNivel' => $carreraNivel,
        ]);
    }

    public function create(Request $request)
    {
        $context = $this->gestionResolver->resolve($request->input('gestion_id'));
        $pago = pagos::get();
        $gestiones = $context['gestiones'];
        $gestionActiva = $context['gestionActiva'];
        $gestionAlert = $context['gestionAlert'];
        return view('pagos.index', compact('pago', 'gestiones', 'gestionActiva', 'gestionAlert'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'concepto' => 'required',
            'fecha' => 'required',
            'monto' => 'required',
            'meses' => 'required|array',
            'matricula_id' => 'required|int',
        ]);

        $selectedMonths = $request->input('meses');
        $mesesPagados = implode(', ', $selectedMonths);

        $pago = Pagos::create([
            'concepto' => $request->input('concepto'),
            'fecha' => $request->input('fecha'),
            'monto' => $request->input('monto'),
            'mes_pago' => $mesesPagados,
            'matricula_id' => $request->input('matricula_id'),
        ]);

        $pago->save();
        return redirect()->route('pagos.show', $pago->pago_id);
    }

    public function show($id)
    {
        $pago = Pagos::findOrFail($id);
        if ($pago->matricula_id === null) {
            return view('pagos.show_extra', compact('pago'));
        }

        if (!$pago->matricula || !$pago->matricula->estudianteCarrera || !$pago->matricula->estudianteCarrera->estudiante) {
            return redirect()->route('pagos.lista')->with('error', 'No se encontraron los datos del estudiante.');
        }

        $estudiante = $pago->matricula->estudianteCarrera->estudiante;
        $carrera = $pago->matricula->estudianteCarrera->carrera;
        $nivel = $pago->matricula->estudianteCarrera->carrera->nivel;
        $mesesPagados = $pago->meses_pagados;
        return view('pagos.show', compact('pago', 'estudiante', 'carrera', 'mesesPagados', 'nivel'));
    }

    public function print($id)
    {
        $pago = Pagos::findOrFail($id);

        if ($pago->matricula_id === null) {
            return view('pagos.print_extra', compact('pago'));
        }

        if (!$pago->matricula || !$pago->matricula->estudianteCarrera || !$pago->matricula->estudianteCarrera->estudiante) {
            return redirect()->route('pagos.lista')->with('error', 'No se encontraron los datos del estudiante.');
        }

        $estudiante = $pago->matricula->estudianteCarrera->estudiante;
        $carrera = $pago->matricula->estudianteCarrera->carrera;
        $nivel = $carrera->nivel;
        $mesesPagados = $pago->mes_pago;

        return view('pagos.print', compact('pago', 'estudiante', 'carrera', 'mesesPagados', 'nivel'));
    }

    public function edit(pagos $pagos)
    {
        //
    }

    public function update(Request $request, pagos $pagos)
    {
        //
    }

    public function destroy(pagos $pagos)
    {
        //
    }

    public function createExtra()
    {
        return view('pagos.pago_extra');
    }

    public function showExtra($id)
    {
        $pago = Pagos::findOrFail($id);

        if ($pago->matricula_id === null) {
            return view('pagos.show_extra', compact('pago'));
        }
    }

    public function storeExtra(Request $request)
    {
        $request->validate([
            'concepto' => 'required|string|max:255',
            'monto' => 'required|numeric|min:0',
            'fecha' => 'required|date',
        ]);

        $pagoExtra = Pagos::create([
            'matricula_id' => null,
            'concepto' => $request->concepto,
            'fecha' => $request->fecha,
            'monto' => $request->monto,
            'mes_pago' => null,
        ]);

        return redirect()->route('pagos.show_extra', ['id' => $pagoExtra->pago_id])->with('success', 'Ingreso extra registrado exitosamente.');
    }
}
