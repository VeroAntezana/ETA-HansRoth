<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gestion extends Model
{
    use HasFactory;
    protected $table = 'gestion';
    protected $primaryKey = 'gestion_id';
    protected $fillable = [
        'descripcion',
        'fecha_inicio',
        'fecha_fin'

    ];

    public function estudiantes()
    {
        return $this->hasMany(Estudiantes::class, 'gestion_id');
    }

    public function pagos()
    {
        return $this->hasMany(Pagos::class);
    }

    public function egreso()
    {
        return $this->hasMany(Egreso::class);
    }
    public function matriculas()
    {
        return $this->hasMany(Matricula::class, 'gestion_id');
    }
    public function semestres()
    {
        return $this->hasMany(Semestre::class, 'gestion_id', 'gestion_id');
    }

    public $timestamps = false;
}
