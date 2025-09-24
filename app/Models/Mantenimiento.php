<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Mantenimiento extends Model
{
    use HasFactory;

    /**
     * Atributos asignables masivamente.
     *
     * @var array
     */
    protected $fillable = [
        'vehiculo_id',
        'tipo',
        'descripcion',
        'fecha',
        'kilometraje',
        'costo_real',
        'proveedor',       // ← Agregado: si usás campo texto (no proveedor_id)
        'estado',
        'observaciones',
    ];

    /**
     * Atributos que deben ser convertidos a Carbon.
     *
     * @var array
     */
    protected $dates = [
        'fecha',
        'created_at',
        'updated_at',
    ];

    /**
     * Relación con el vehículo.
     */
    public function vehiculo()
    {
        return $this->belongsTo(Vehiculo::class);
    }
    
    public function getCostoAttribute()
{
    return $this->costo_real;
}
}