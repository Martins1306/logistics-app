<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MovimientoInventario extends Model
{
    // ✅ Forzar el nombre correcto de la tabla
    protected $table = 'movimientos_inventario';

    protected $fillable = [
        'producto_id',
        'tipo',
        'cantidad',
        'motivo',
        'referencia_id',
        'referencia_tipo',
        'usuario_id',
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
}