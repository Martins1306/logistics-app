<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gasto extends Model
{
    use HasFactory;

    protected $fillable = [
        'vehiculo_id',
        'tipo',
        'monto',
        'descripcion',
        'fecha',
    ];

    // Relación: un gasto pertenece a un vehículo
    public function vehiculo()
    {
        return $this->belongsTo(Vehiculo::class);
    }
}