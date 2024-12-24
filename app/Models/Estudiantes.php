<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Estudiantes extends Model
{
    use HasFactory;
    protected $fillable = [
        'nombre',
        'apellidos',
        'fecha_nacimiento',
        'carnet',
        'sexo'
        
        
    ];
     // Relación: Un estudiante tiene muchas matrículas
     public function matriculas()
     {
         return $this->hasMany(Matricula::class, 'estudiante_id');
     }
 
     // Relación: Acceder a los pagos a través de las matrículas
     public function pagos()
     {
         return $this->hasManyThrough(Pagos::class, Matricula::class, 'estudiante_id', 'matricula_id');
     }

    

   
}
