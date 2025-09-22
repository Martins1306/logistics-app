<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    protected $fillable = [
        'nombre',
        'tipo', // ✅ Debe estar aquí
        'telefono',
        'email',
        'cuit',
        'notas',
        'calle',
        'numero',
        'codigo_postal',
        'localidad',
        'partido',
        'provincia',
    ];

    // Accesor: nombre corto
    public function getNombreCortoAttribute()
    {
        return strlen($this->nombre) > 20 ? substr($this->nombre, 0, 20) . '...' : $this->nombre;
    }
}