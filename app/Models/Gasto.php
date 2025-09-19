<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gasto extends Model
{
    use HasFactory;

    protected $fillable = [
        'vehiculo_id',
        'chofer_id',   // ← Nuevo campo
        'tipo',
        'monto',
        'descripcion',
        'fecha',
    ];

    protected $casts = [
        'fecha' => 'date',
        'monto' => 'decimal:2'
    ];

    // Relación: un gasto pertenece a un vehículo
    public function vehiculo()
    {
        return $this->belongsTo(Vehiculo::class);
    }

    // Relación: un gasto pertenece a un chofer (opcional)
    public function chofer()
    {
        return $this->belongsTo(Chofer::class);
    }
}