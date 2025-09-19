<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Vehiculo extends Model
{
    use HasFactory;

    // Nombre de la tabla en la base de datos
    protected $table = 'vehiculos';

    // Campos que se pueden asignar masivamente
    protected $fillable = [
        'patente',
        'marca',
        'modelo',
        'tipo',
        'capacidad_kg',
        'fecha_compra',
        'ultimo_mantenimiento_km',
        'kilometraje_actual',
        'intervalo_mantenimiento',
    ];

    // Atributos que deben ser tratados como fechas
    protected $dates = [
        'fecha_compra',
        'created_at',
        'updated_at'
    ];

    // Tipos de atributos (opcional, más moderno que $dates)
    protected $casts = [
        'fecha_compra' => 'date',
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

    // Accesor: próximo mantenimiento (en km)
    public function getProximoMantenimientoAttribute()
    {
        $ultimo = $this->ultimo_mantenimiento_km ?? 0;
        $intervalo = $this->intervalo_mantenimiento ?? 10000; // valor por defecto: cada 10.000 km
        return $ultimo + $intervalo;
    }

    // Método: ¿el vehículo necesita mantenimiento?
    public function necesitaMantenimiento()
    {
        $kmActual = $this->kilometraje_actual ?? 0;
        $proximo = $this->getProximoMantenimientoAttribute();
        return $kmActual >= $proximo;
    }
}