<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Semestre extends Model
{
    use HasFactory;

    protected $table = 'semestre';
    protected $primaryKey = 'semestre_id';
    public $timestamps = false;

    protected $fillable = [
        'gestion_id',
        'nombre',
        'activo',
    ];

    public function gestion()
    {
        return $this->belongsTo(Gestion::class, 'gestion_id', 'gestion_id');
    }

    public function matriculas()
    {
        return $this->hasMany(Matricula::class, 'semestre_id', 'semestre_id');
    }
}
