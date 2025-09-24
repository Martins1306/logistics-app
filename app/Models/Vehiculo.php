<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Chofer;

class Vehiculo extends Model
{
    use HasFactory;

    protected $table = 'vehiculos';

    // ✅ Campos que se pueden asignar masivamente
    protected $fillable = [
        'patente',
        'marca',
        'modelo',
        'tipo',
        'capacidad_kg',
        'fecha_compra',
        'kilometraje_actual',
        'ultimo_mantenimiento_km',
        'intervalo_mantenimiento',
        'estado',
        'chofer_id',
    ];

    // ✅ Fechas correctamente tipadas
    protected $casts = [
        'fecha_compra' => 'date',
    ];

    // Relaciones
    public function viajes()
    {
        return $this->hasMany(Viaje::class);
    }

    public function mantenimientos()
    {
        return $this->hasMany(Mantenimiento::class);
    }

    // ✅ Accesor: próximo mantenimiento (calculado)
    public function getProximoMantenimientoAttribute()
    {
        $ultimo = $this->ultimo_mantenimiento_km ?? 0;
        $intervalo = $this->intervalo_mantenimiento ?? 10000;
        return $ultimo + $intervalo;
    }

    // ✅ ¿Necesita mantenimiento?
    public function necesitaMantenimiento()
    {
        $kmActual = $this->kilometraje_actual ?? 0;
        return $kmActual >= $this->proximo_mantenimiento;
    }

    // ✅ ¿Próximos 1000 km al mantenimiento?
    public function proximoAMantenimiento()
    {
        $kmActual = $this->kilometraje_actual ?? 0;
        return $kmActual >= ($this->proximo_mantenimiento - 1000);
    }
       
     // ✅ Si no hay chofer asignado
    public function choferActual()
    {
        if (!$this->chofer_id) {
            return null;
        }
        return $this->belongsTo(Chofer::class, 'chofer_id')->first();
    }
}