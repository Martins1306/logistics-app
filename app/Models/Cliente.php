<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    protected $fillable = [
        'nombre',
        'razon_social',
        'cuit',
        'telefono',
        'email',
        'direccion',
        'localidad',
        'provincia',
        'tipo',
        'notas',
    ];

    // Accesor: nombre corto
    public function getNombreCortoAttribute()
    {
        return strlen($this->nombre) > 20 ? substr($this->nombre, 0, 20) . '...' : $this->nombre;
    }
}