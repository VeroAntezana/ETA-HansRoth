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
        return $this->hasManyThrough(
            Matricula::class,
            carrera_Estudiantes::class,
            'estudiante_id', // Clave foránea en `carrera_Estudiantes`
            'estudiante_carrera_id', // Clave foránea en `Matricula`
            'estudiante_id', // Clave primaria en `Estudiantes`
            'estudiante_carrera_id' // Clave primaria en `carrera_Estudiantes`
        );
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
    public function estudianteCarreras()
    {
        return $this->hasMany(carrera_Estudiantes::class, 'estudiante_id');
    }
}
