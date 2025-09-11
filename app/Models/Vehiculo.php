<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Viaje;
use App\Models\Mantenimiento; // ← Importa el modelo Mantenimiento

class Vehiculo extends Model
{
    use HasFactory;

    // Tabla asociada (opcional, por si no sigue convención)
    protected $table = 'vehiculos';

    // Campos que se pueden asignar masivamente
    protected $fillable = [
        'patente',
        'marca',
        'modelo',
        'tipo',
        'capacidad_kg',
        'fecha_compra',
        'kilometraje_actual',
    ];

    // Atributos que deben ser tratados como fechas
    protected $dates = [
        'fecha_compra',
        'created_at',
        'updated_at'
    ];

    // Relación: un vehículo tiene muchos viajes
    public function viajes()
    {
        return $this->hasMany(Viaje::class);
    }

    // Relación: un vehículo tiene muchos mantenimientos
    public function mantenimientos()
    {
        return $this->hasMany(Mantenimiento::class);
    }
}