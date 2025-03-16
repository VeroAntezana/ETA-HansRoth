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
            $estudiante = $pago->matricula->estudianteCarrera->estudiante;
            $carrera = $pago->matricula->estudianteCarrera->carrera;
            $nivel = $carrera->nivel;

            return [
                'id' => $pago->pago_id,
                'fecha' => $pago->fecha,
                'detalle' => sprintf(
                    "%s %s, Meses Pagados: %s, Carrera y Nivel: %s - %s",
                    $estudiante->nombre,
                    $estudiante->apellidos,
                    $pago->mes_pago,
                    $carrera->nombre,
                    $nivel->nombre
                ),
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
            ->whereBetween('fecha', [$fechaInicio, $fechaFin])
            ->get();

        // Crear los datos de la primera tabla (detalles de pagos)
        $datosPagos = $pagos->map(function ($pago) {
            $estudiante = $pago->matricula->estudianteCarrera->estudiante;
            $carrera = $pago->matricula->estudianteCarrera->carrera;
            $nivel = $carrera->nivel;

            return [
                'Recibo' => $pago->pago_id,
                'Fecha de Pago' => $pago->fecha,
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
        $fechaInicioSistema = Carbon::create(2025, 1, 1)->startOfMonth(); // Inicio del sistema (1 de enero de 2025)
        $fechaInicio = Carbon::parse($fechaInicio); // Convertir la fecha de inicio a un objeto Carbon
        $fechaFin = Carbon::parse($fechaFin); // Convertir la fecha de fin a un objeto Carbon
        $montoTotalPagos = pagos::sum('monto'); // Total de todos los pagos
        $montoPagosMesActual = pagos::whereBetween(DB::raw('DATE(fecha)'), [$fechaInicio, $fechaFin])->sum('monto'); // Total de pagos en el rango actual
        $montoPagosSinMesActual =pagos::whereBetween(DB::raw('DATE(fecha)'), [$fechaInicioSistema, $fechaInicio->copy()->subDay()])->sum('monto')
        - Egreso::whereBetween(DB::raw('DATE(fecha)'), [$fechaInicioSistema, $fechaInicio->copy()->subDay()])->sum('monto');
        $montoEgresosMesActual = Egreso::whereBetween(DB::raw('DATE(fecha)'), [$fechaInicio, $fechaFin])->sum('monto'); // Egresos del rango actual
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

        // Exportar a Excel con dos tablas
        return Excel::download(new class($datosPagos, $datosTotales) implements FromCollection, WithMultipleSheets {
            private $pagos;
            private $totales;

            public function __construct($pagos, $totales)
            {
                $this->pagos = $pagos;
                $this->totales = $totales;
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

                    // Segunda hoja
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
                ];
            }
        }, 'ReportePagos.xlsx');
    }
}
