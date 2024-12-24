<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gestion extends Model
{
    use HasFactory;
    protected $table = 'gestiones';
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
}
