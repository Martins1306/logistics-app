<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Viaje;

class MovimientoInventario extends Model
{
    // ✅ Forzar el nombre correcto de la tabla
    protected $table = 'movimientos_inventario';

    protected $fillable = [
        'vehiculo_id',
        'tipo',
        'descripcion',
        'fecha',
        'kilometraje',
        'costo_real',
        'estado',
        'observaciones', // ✅ Agregado
    ];

    public function producto()
    {
        return $this->belongsTo(\App\Models\Producto::class);
    }

    public function referencia()
    {
        return $this->morphTo();
    }

    public function usuario()
    {
        return $this->belongsTo(\App\Models\User::class);
    }
    /**
     * Relación con el viaje que generó este movimiento de inventario.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function viaje()
    {
        return $this->belongsTo(Viaje::class);
    }

}