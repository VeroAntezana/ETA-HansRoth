<?php

namespace App\Http\Controllers;

use App\Models\Egreso;
use App\Models\pagos;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;


class reportesController extends Controller
{
    public function dashboard(){
        return view('welcome');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $pago = pagos::with([
            'matricula.estudianteCarrera.estudiante',
            'matricula.estudianteCarrera.carrera.nivel'
        ])->get();

        $totalPagos = pagos::sum('monto');

        $totalegresos  = Egreso::sum('monto');


        // Convertimos la colección a un formato más conveniente para la vista
        $pagoConDetalles = $pago->map(function ($pago) {
            $matricula = $pago->matricula;
            $estudiante = $matricula->estudianteCarrera->estudiante ?? null;
            $carrera = $matricula->estudianteCarrera->carrera ?? null;
            $nivel = $carrera->nivel ?? null;

            // Construcción del detalle del pago
            $detalle = $matricula
                ? sprintf(
                    "%s %s, Meses Pagados: %s, Carrera y Nivel: %s - %s",
                    $estudiante->nombre ?? 'Desconocido',
                    $estudiante->apellidos ?? '',
                    $pago->mes_pago ?? 'N/A',
                    $carrera->nombre ?? 'Pago Varios',
                    $nivel->nombre ?? 'N/A'
                )
                : sprintf("Pagos Varios, Concepto: %s, Monto: %s", $pago->concepto, $pago->monto);

            return [
                'id' => $pago->pago_id,
                'fecha' => Carbon::parse($pago->fecha)->format('Y-m-d'),
                'detalle' => $detalle,
                'ingreso' => $pago->monto
            ];
        });

        return view('Reportes.index', compact('pagoConDetalles', 'totalPagos', 'totalegresos'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    public function index_egreso()
    {
        return view('reportes.index-egreso');
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function exportToExcel(Request $request)
    {
        // Validar las fechas
        $request->validate([
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date|after_or_equal:fecha_inicio',
        ]);

        $fechaInicio = $request->input('fecha_inicio');
        $fechaFin = $request->input('fecha_fin'); // Obtener los pagos dentro del rango de fechas
        $pagos = pagos::with([
            'matricula.estudianteCarrera.estudiante',
            'matricula.estudianteCarrera.carrera.nivel'
        ])
            ->whereBetween(DB::raw('DATE(fecha)'), [$fechaInicio, $fechaFin])
            ->get();

        // Crear los datos de la primera tabla (detalles de pagos)
        $datosPagos = $pagos->map(function ($pago) {
            $matricula = $pago->matricula;
            $estudiante = $matricula->estudianteCarrera->estudiante ?? null;
            $carrera = $matricula->estudianteCarrera->carrera ?? null;
            $nivel = $carrera->nivel ?? null;

            // Construcción del detalle del pago
            $detalle = $matricula
                ? sprintf(
                    "%s %s, Meses Pagados: %s, Carrera y Nivel: %s - %s",
                    $estudiante->nombre ?? 'Desconocido',
                    $estudiante->apellidos ?? '',
                    $pago->mes_pago ?? 'N/A',
                    $carrera->nombre ?? 'Pago Varios',
                    $nivel->nombre ?? 'N/A'
                )
                : sprintf("Pagos Varios, Concepto: %s, Monto: %s", $pago->concepto, $pago->monto);

            return [
                'Recibo' => $pago->pago_id,
                'Fecha de Pago' => Carbon::parse($pago->fecha)->format('Y-m-d'),
                'Detalle' => sprintf(
                    "%s %s, Meses Pagados: %s, Carrera y Nivel: %s - %s",
                    $estudiante->nombre,
                    $estudiante->apellidos,
                    $pago->mes_pago,
                    $carrera->nombre,
                    $nivel->nombre
                ),
                'Ingreso' => $pago->monto,
            ];
        });

        // Calcular totales para la segunda tabla
        $fechaInicioSistema = Carbon::create(2025, 1, 1)->startOfMonth();
        $fechaInicio = Carbon::parse($fechaInicio);
        $fechaFin = Carbon::parse($fechaFin);
        $montoTotalPagos = pagos::sum('monto');
        $montoPagosMesActual = pagos::whereBetween(DB::raw('DATE(fecha)'), [$fechaInicio, $fechaFin])->sum('monto');
        $montoPagosSinMesActual = pagos::whereBetween(DB::raw('DATE(fecha)'), [$fechaInicioSistema, $fechaInicio->copy()->subDay()])->sum('monto')
            - Egreso::whereBetween(DB::raw('DATE(fecha)'), [$fechaInicioSistema, $fechaInicio->copy()->subDay()])->sum('monto');
        $montoEgresosMesActual = Egreso::whereBetween(DB::raw('DATE(fecha)'), [$fechaInicio, $fechaFin])->sum('monto');
        $totalCaja = ($montoPagosSinMesActual + $montoPagosMesActual) - $montoEgresosMesActual;


        // Crear los datos de la segunda tabla (totales)
        $datosTotales = collect([
            [
                'Titulo' => 'Monto Total de Ingresos',
                'Valor' => $montoTotalPagos,
            ],
            [
                'Titulo' => 'Monto Total del Mes Anterior',
                'Valor' =>  $montoPagosSinMesActual,
            ],
            [
                'Titulo' => 'Monto Total de Ingresos (Mes Actual)',
                'Valor' => $montoPagosMesActual,
            ],
            [
                'Titulo' => 'Monto Total de Egresos (Mes Actual)',
                'Valor' => $montoEgresosMesActual,
            ],
            [
                'Titulo' => 'Total en Caja (Mes Actual)',
                'Valor' => $totalCaja,
            ],
        ]);

        // Obtener los egresos dentro del rango de fechas
        $egresos = Egreso::whereBetween(DB::raw('DATE(fecha)'), [$fechaInicio, $fechaFin])->get();

        // Mapear datos de egresos para la tercera tabla
        $datosEgresos = $egresos->map(function ($egreso) {
            return [
                'Recibo' => $egreso->egreso_id,
                'Fecha' => Carbon::parse($egreso->fecha)->format('Y-m-d'),
                'Nombre' => $egreso->nombre,
                'Concepto' => $egreso->concepto,
                'Egreso' => $egreso->monto,
            ];
        });

        // Exportar a Excel
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
                            return ['Título', 'Valor'];
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
