<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class carrera_Estudiantes extends Model
{
    use HasFactory;
    protected $table = 'estudiante_carrera';
    protected $primaryKey = 'estudiante_carrera_id';
    protected $fillable = ['estudiante_id', 'carrera_id', 'fecha_inscripcion'];
    public $timestamps = false;
    public function estudiante()
    {
        return $this->belongsTo(Estudiantes::class, 'estudiante_id');
    }

    public function carrera()
    {
        return $this->belongsTo(Carreras::class, 'carrera_id');
    }

    public function matriculas()
    {
        return $this->hasMany(Matricula::class, 'estudiante_carrera_id');
    }
}
