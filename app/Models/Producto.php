<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Producto extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'categoria',
        'unidad',
        'precio',
    ];

    // Opcional: accesor para formato de precio
    public function getPrecioFormattedAttribute()
    {
        return '$ ' . number_format($this->precio, 2, ',', '.');
    }
    // Relación: un producto puede ir en muchos viajes
public function viajes()
{
    return $this->belongsToMany(Viaje::class)
                ->withPivot('cantidad', 'notas')
                ->withTimestamps();
}
}