<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    use HasFactory;

    // ✅ Campos que se pueden asignar masivamente
    protected $fillable = [
        'nombre',
        'categoria',
        'unidad',
        'precio',
        'stock_actual',   // ← Nuevo: para control de inventario
        'stock_minimo',   // ← Nuevo: para alertas de bajo stock
    ];

    // ✅ Casts para asegurar tipos correctos
    protected $casts = [
        'precio' => 'decimal:2',
        'stock_actual' => 'integer',
        'stock_minimo' => 'integer',
    ];

    // ✅ Accesor: precio formateado
    public function getPrecioFormattedAttribute()
    {
        return '$ ' . number_format($this->precio, 2, ',', '.');
    }

    // ✅ Relación: un producto puede ir en muchos viajes
    public function viajes()
    {
        return $this->belongsToMany(Viaje::class, 'producto_viaje')
                    ->withPivot('cantidad', 'notas')
                    ->withTimestamps();
    }

    // ✅ Relación: movimientos de inventario
    public function movimientos()
    {
        return $this->hasMany(MovimientoInventario::class);
    }
}