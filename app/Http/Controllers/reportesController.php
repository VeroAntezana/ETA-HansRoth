<?php

namespace App\Http\Controllers;

use App\Models\Egreso;
use App\Models\pagos;
use App\Support\GestionContextResolver;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Facades\Excel;

class reportesController extends Controller
{
    private GestionContextResolver $gestionResolver;

    public function __construct(GestionContextResolver $gestionResolver)
    {
        $this->gestionResolver = $gestionResolver;
    }

    public function dashboard()
    {
        return view('welcome');
    }

    public function index(Request $request)
    {
        $context = $this->gestionResolver->resolve($request->input('gestion_id'));
        $gestionActiva = $context['gestionActiva'];

        $pagoConDetalles = collect();
        $totalPagos = 0;
        $totalegresos = 0;

        if ($gestionActiva) {
            $inicio = Carbon::parse($gestionActiva->fecha_inicio)->startOfDay();
            $fin = Carbon::parse($gestionActiva->fecha_fin)->endOfDay();

            $pagos = pagos::with([
                'matricula.estudianteCarrera.estudiante',
                'matricula.estudianteCarrera.carrera.nivel'
            ])
                ->whereBetween(DB::raw('DATE(fecha)'), [$inicio->toDateString(), $fin->toDateString()])
                ->get();

            $totalPagos = $pagos->sum('monto');
            $totalegresos = Egreso::whereBetween(DB::raw('DATE(fecha)'), [$inicio->toDateString(), $fin->toDateString()])
                ->sum('monto');

            $pagoConDetalles = $pagos->map(function ($pago) {
                $matricula = $pago->matricula;
                $estudiante = $matricula->estudianteCarrera->estudiante ?? null;
                $carrera = $matricula->estudianteCarrera->carrera ?? null;
                $nivel = $carrera->nivel ?? null;

                $detalle = $matricula
                    ? sprintf(
                        '%s %s, Meses Pagados: %s, Carrera y Nivel: %s - %s',
                        $estudiante->nombre ?? 'Desconocido',
                        $estudiante->apellidos ?? '',
                        $pago->mes_pago ?? 'N/A',
                        $carrera->nombre ?? 'Pago Varios',
                        $nivel->nombre ?? 'N/A'
                    )
                    : sprintf('Pagos Varios, Concepto: %s, Monto: %s', $pago->concepto, $pago->monto);

                return [
                    'id' => $pago->pago_id,
                    'fecha' => Carbon::parse($pago->fecha)->format('Y-m-d'),
                    'detalle' => $detalle,
                    'ingreso' => $pago->monto
                ];
            });
        }

        $gestiones = $context['gestiones'];
        $gestionAlert = $context['gestionAlert'];

        return view('Reportes.index', compact('pagoConDetalles', 'totalPagos', 'totalegresos', 'gestiones', 'gestionActiva', 'gestionAlert'));
    }

    public function create()
    {
        //
    }

    public function index_egreso()
    {
        return view('reportes.index-egreso');
    }

    public function store(Request $request)
    {
        //
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        //
    }

    public function destroy($id)
    {
        //
    }

    public function exportToExcel(Request $request)
    {
        $context = $this->gestionResolver->resolve($request->input('gestion_id'));
        $gestionActiva = $context['gestionActiva'];

        if (!$gestionActiva) {
            return back()->with('error', $context['gestionAlert'] ?? 'No existe una gestion activa para exportar.');
        }

        $fechaInicio = $request->input('fecha_inicio', $gestionActiva->fecha_inicio);
        $fechaFin = $request->input('fecha_fin', $gestionActiva->fecha_fin);

        $request->merge([
            'fecha_inicio' => $fechaInicio,
            'fecha_fin' => $fechaFin,
        ]);

        $request->validate([
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date|after_or_equal:fecha_inicio',
        ]);

        $fechaInicioCarbon = Carbon::parse($fechaInicio)->startOfDay();
        $fechaFinCarbon = Carbon::parse($fechaFin)->endOfDay();
        $gestionInicioCarbon = Carbon::parse($gestionActiva->fecha_inicio)->startOfDay();
        $gestionFinCarbon = Carbon::parse($gestionActiva->fecha_fin)->endOfDay();

        $pagos = pagos::with([
            'matricula.estudianteCarrera.estudiante',
            'matricula.estudianteCarrera.carrera.nivel'
        ])
            ->whereBetween(DB::raw('DATE(fecha)'), [$fechaInicioCarbon->toDateString(), $fechaFinCarbon->toDateString()])
            ->get();

        $datosPagos = $pagos->map(function ($pago) {
            $matricula = $pago->matricula;
            if ($matricula) {
                $estudiante = $matricula->estudianteCarrera->estudiante;
                $carrera = $matricula->estudianteCarrera->carrera;
                $nivel = $carrera->nivel;
                $detalle = sprintf(
                    '%s %s, Meses Pagados: %s, Carrera y Nivel: %s - %s',
                    $estudiante->nombre ?? 'Desconocido',
                    $estudiante->apellidos ?? '',
                    $pago->mes_pago ?? 'N/A',
                    $carrera->nombre ?? 'Pago Varios',
                    $nivel->nombre ?? 'N/A'
                );
            } else {
                $detalle = sprintf('Pagos Varios, Concepto: %s, Monto: %s', $pago->concepto, $pago->monto);
            }

            return [
                'Recibo' => $pago->pago_id,
                'Fecha de Pago' => Carbon::parse($pago->fecha)->format('Y-m-d'),
                'Detalle' => $detalle,
                'Ingreso' => $pago->monto,
            ];
        });

        $montoPagosRango = $pagos->sum('monto');
        $montoEgresosRango = Egreso::whereBetween(DB::raw('DATE(fecha)'), [$fechaInicioCarbon->toDateString(), $fechaFinCarbon->toDateString()])
            ->sum('monto');

        $mesActualInicio = $fechaInicioCarbon->copy()->startOfMonth();
        $mesAnteriorInicio = $mesActualInicio->copy()->subMonth()->startOfMonth();
        $mesAnteriorFin = $mesActualInicio->copy()->subMonth()->endOfMonth();

        $montoCajaMesAnterior = 0;
        if (!($mesAnteriorFin->lt($gestionInicioCarbon) || $mesAnteriorInicio->gt($gestionFinCarbon))) {
            $mesAnteriorDesde = $mesAnteriorInicio->copy();
            $mesAnteriorHasta = $mesAnteriorFin->copy();

            if ($mesAnteriorDesde->lt($gestionInicioCarbon)) {
                $mesAnteriorDesde = $gestionInicioCarbon->copy();
            }
            if ($mesAnteriorHasta->gt($gestionFinCarbon)) {
                $mesAnteriorHasta = $gestionFinCarbon->copy();
            }

            $ingresosMesAnterior = pagos::whereBetween(DB::raw('DATE(fecha)'), [$mesAnteriorDesde->toDateString(), $mesAnteriorHasta->toDateString()])
                ->sum('monto');

            $egresosMesAnterior = Egreso::whereBetween(DB::raw('DATE(fecha)'), [$mesAnteriorDesde->toDateString(), $mesAnteriorHasta->toDateString()])
                ->sum('monto');

            $montoCajaMesAnterior = $ingresosMesAnterior - $egresosMesAnterior;
        }

        $datosTotales = collect([
            [
                'Titulo' => 'Monto Total del Mes Anterior',
                'Valor' => $montoCajaMesAnterior,
            ],
            [
                'Titulo' => 'Monto Total de Ingresos (Mes Actual)',
                'Valor' => $montoPagosRango,
            ],
            [
                'Titulo' => 'Monto Total de Egresos (Mes Actual)',
                'Valor' => $montoEgresosRango,
            ],
            [
                'Titulo' => 'Total en Caja (Mes Actual)',
                'Valor' => $montoCajaMesAnterior + $montoPagosRango - $montoEgresosRango,
            ],
        ]);

        $egresos = Egreso::whereBetween(DB::raw('DATE(fecha)'), [$fechaInicioCarbon->toDateString(), $fechaFinCarbon->toDateString()])
            ->get();

        $datosEgresos = $egresos->map(function ($egreso) {
            return [
                'Recibo' => $egreso->egreso_id,
                'Fecha' => Carbon::parse($egreso->fecha)->format('Y-m-d'),
                'Nombre' => $egreso->nombre,
                'Concepto' => $egreso->concepto,
                'Egreso' => $egreso->monto,
            ];
        });

        return Excel::download(new class($datosPagos, $datosTotales, $datosEgresos) implements FromCollection, WithMultipleSheets {
            private $pagos;
            private $totales;
            private $egresos;

            public function __construct($pagos, $totales, $egresos)
            {
                $this->pagos = $pagos;
                $this->totales = $totales;
                $this->egresos = $egresos;
            }

            public function collection()
            {
                return $this->pagos;
            }

            public function sheets(): array
            {
                return [
                    new class($this->pagos) implements FromCollection, WithHeadings {
                        private $pagos;

                        public function __construct($pagos)
                        {
                            $this->pagos = $pagos;
                        }

                        public function collection()
                        {
                            return $this->pagos;
                        }

                        public function headings(): array
                        {
                            return ['Recibo', 'Fecha de Pago', 'Detalle', 'Ingreso'];
                        }
                    },
                    new class($this->totales) implements FromCollection, WithHeadings {
                        private $totales;

                        public function __construct($totales)
                        {
                            $this->totales = $totales;
                        }

                        public function collection()
                        {
                            return $this->totales;
                        }

                        public function headings(): array
                        {
                            return ['Titulo', 'Valor'];
                        }
                    },
                    new class($this->egresos) implements FromCollection, WithHeadings {
                        private $egresos;

                        public function __construct($egresos)
                        {
                            $this->egresos = $egresos;
                        }

                        public function collection()
                        {
                            return $this->egresos;
                        }

                        public function headings(): array
                        {
                            return ['Recibo', 'Fecha', 'Nombre', 'Concepto', 'Egreso'];
                        }
                    },
                ];
            }
        }, 'ReportePagos.xlsx');
    }
}
