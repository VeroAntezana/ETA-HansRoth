<?php

namespace App\Http\Controllers;

use App\Models\Egreso;
use App\Support\GestionContextResolver;
use Carbon\Carbon;
use Illuminate\Http\Request;

class EgresoController extends Controller
{
    private GestionContextResolver $gestionResolver;

    public function __construct(GestionContextResolver $gestionResolver)
    {
        $this->gestionResolver = $gestionResolver;
    }

    public function index(Request $request)
    {
        $context = $this->gestionResolver->resolve($request->input('gestion_id'));
        $fechaInicio = $request->input('fecha_inicio');
        $fechaFin = $request->input('fecha_fin');

        $query = Egreso::query();

        if ($context['gestionActiva']) {
            $query->where('gestion_id', $context['gestionActiva']->gestion_id);
        } else {
            $query->whereRaw('1 = 0');
        }

        if ($fechaInicio && $fechaFin) {
            $query->whereBetween('fecha', [
                Carbon::parse($fechaInicio)->startOfDay(),
                Carbon::parse($fechaFin)->endOfDay()
            ]);
        }

        $egresos = (clone $query)->orderBy('fecha', 'desc')->get();
        $totalEgresos = (clone $query)->sum('monto');
        $gestiones = $context['gestiones'];
        $gestionActiva = $context['gestionActiva'];
        $gestionAlert = $context['gestionAlert'];

        return view('egresos.index', compact('egresos', 'gestiones', 'totalEgresos', 'gestionActiva', 'gestionAlert'));
    }

    public function create(Request $request)
    {
        $context = $this->gestionResolver->resolve($request->input('gestion_id'));
        $gestiones = $context['gestiones'];
        $gestionActiva = $context['gestionActiva'];
        $gestionAlert = $context['gestionAlert'];

        return view('egresos.create', compact('gestiones', 'gestionActiva', 'gestionAlert'));
    }

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

    public function show(Egreso $egreso)
    {
        //
    }

    public function print($egreso_id)
    {
        $egreso = Egreso::findOrFail($egreso_id);
        return view('egresos.print', compact('egreso'));
    }

    public function edit(Egreso $egreso)
    {
        $context = $this->gestionResolver->resolve(request()->input('gestion_id'));
        $gestiones = $context['gestiones'];
        $gestionActiva = $context['gestionActiva'];
        return view('egresos.edit', compact('egreso', 'gestiones', 'gestionActiva'));
    }

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

    public function destroy(Egreso $egreso)
    {
        $egreso->delete();
        return redirect()->route('egresos.index')->with('success', 'Egreso eliminado exitosamente');
    }
}
