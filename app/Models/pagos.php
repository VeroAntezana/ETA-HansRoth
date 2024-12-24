<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class pagos extends Model
{
    use HasFactory;
    protected $fillable = [
       'matricula_id',
       'concepto',
       'fecha',
       'monto',
       'mes_pago',
    ];
    // Relación: Un pago pertenece a una matrícula
    public function matricula()
    {
        return $this->belongsTo(Matricula::class, 'matricula_id');
    }

    // Relación indirecta: Obtener el estudiante a través de la matrícula
    public function estudiante()
    {
        return $this->hasOneThrough(Estudiantes::class, Matricula::class, 'id', 'id', 'matricula_id', 'estudiante_id');
    }
}
