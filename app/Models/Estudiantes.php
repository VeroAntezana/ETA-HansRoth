<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Estudiantes extends Model
{
    use HasFactory;
    protected $table = 'estudiante';

    protected $primaryKey = 'estudiante_id';
    protected $fillable = [
        'nombre',
        'apellidos',
        'fecha_nacimiento',
        'ci',
        'sexo'


    ];

    public $timestamps = false;


    public function matriculas()
    {
        return $this->hasMany(Matricula::class, 'estudiante_carrera_id', 'estudiante_id');
    }


    public function pagos()
    {
        return $this->hasManyThrough(
            pagos::class,
            Matricula::class,
            'estudiante_carrera_id',
            'matricula_id',
            'estudiante_id',
            'matricula_id'
        );
    }


    public function carreras()
    {
        return $this->belongsToMany(
            Carreras::class,
            'estudiante_carrera',
            'estudiante_id',
            'carrera_id',
            'estudiante_id',
            'carrera_id'
        )->withPivot('fecha_inscripcion');
    }
}
