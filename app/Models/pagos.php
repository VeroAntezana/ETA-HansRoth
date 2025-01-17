<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class pagos extends Model
{
    use HasFactory;
    protected $table = 'pago';
    protected $primaryKey = 'pago_id';
    public $timestamps = false;

    protected $fillable = [
        'matricula_id',
        'concepto',
        'fecha',
        'monto',
        'mes_pago',
    ];

    public function matricula()
    {
        return $this->belongsTo(Matricula::class, 'matricula_id', 'matricula_id');
    }
}
