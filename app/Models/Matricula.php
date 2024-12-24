<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Matricula extends Model
{
    use HasFactory;
    protected $fillable = [
        'estudiante_id',
        'gestion_id',
        'carrera_nivel_id',
        'fecha_matricula',
        'estado'
    ];

     // Relación: Una matrícula pertenece a un estudiante
     public function estudiante()
     {
         return $this->belongsTo(Estudiantes::class, 'estudiante_id');
     }
 
     // Relación: Una matrícula pertenece a una gestión
     public function gestion()
     {
         return $this->belongsTo(Gestion::class, 'gestion_id');
     }
 
     // Relación: Una matrícula pertenece a una carrera y nivel
     public function carreraNivel()
     {
         return $this->belongsTo(carrera_niveles::class, 'carrera_nivel_id');
     }
 
     // Relación: Una matrícula tiene muchos pagos
     public function pagos()
     {
         return $this->hasMany(Pagos::class, 'matricula_id');
     }
}
