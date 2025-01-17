<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Matricula extends Model
{
    use HasFactory;
    protected $table = 'matricula';
    protected $primaryKey = 'matricula_id';
    public $timestamps = false;

    protected $fillable = [
        'estudiante_carrera_id',
        'gestion_id',
        'fecha_matricula',
        'estado',
    ];

    public function estudianteCarrera()
    {
        return $this->belongsTo(carrera_Estudiantes::class, 'estudiante_carrera_id', 'estudiante_carrera_id');
    }

    public function pagos()
    {
        return $this->hasMany(pagos::class, 'matricula_id', 'matricula_id');
    }
    public function gestion()
    {
        return $this->belongsTo(Gestion::class, 'gestion_id', 'gestion_id');
    }
}
