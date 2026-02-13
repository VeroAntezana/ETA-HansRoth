<?php

namespace App\Support;

use App\Models\Gestion;
use Carbon\Carbon;

class GestionContextResolver
{
    public function resolve(?int $gestionId = null): array
    {
        $gestiones = Gestion::orderBy('fecha_inicio', 'asc')->get();
        $today = Carbon::now('America/La_Paz')->startOfDay();
        $expectedDescripcion = $this->expectedDescripcion($today);

        $gestionActiva = null;
        $gestionAlert = null;

        if ($gestionId !== null) {
            $gestionActiva = $gestiones->firstWhere('gestion_id', $gestionId);
        }

        if (!$gestionActiva && session()->has('gestion_id')) {
            $gestionActiva = $gestiones->firstWhere('gestion_id', (int) session('gestion_id'));
        }

        if (!$gestionActiva) {
            $gestionActiva = $gestiones->first(function ($gestion) use ($today) {
                $start = Carbon::parse($gestion->fecha_inicio)->startOfDay();
                $end = Carbon::parse($gestion->fecha_fin)->endOfDay();
                return $today->betweenIncluded($start, $end);
            });
        }

        if (!$gestionActiva) {
            $gestionActiva = $gestiones->firstWhere('descripcion', $expectedDescripcion);
        }

        if ($gestionActiva) {
            session(['gestion_id' => $gestionActiva->gestion_id]);
        } else {
            $gestionAlert = "No existe la gestión {$expectedDescripcion}. Créela en el módulo Gestiones.";
        }

        return [
            'gestiones' => $gestiones,
            'gestionActiva' => $gestionActiva,
            'gestionAlert' => $gestionAlert,
            'expectedDescripcion' => $expectedDescripcion,
        ];
    }

    public function expectedDescripcion(Carbon $date): string
    {
        $year = $date->year;
        $semester = $date->month <= 6 ? 'I' : 'II';
        return "{$year}-{$semester}";
    }
}

