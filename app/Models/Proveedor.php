<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Proveedor extends Model
{
    // ✅ Forzar el nombre correcto de la tabla
    protected $table = 'proveedores';

    protected $fillable = [
        'nombre',
    'contacto',
    'telefono',
    'email',
    'notas',
    // Dirección detallada
    'calle',
    'numero',
    'codigo_postal',
    'localidad',
    'partido',
    'provincia',
    ];

    public function compras()
    {
        return $this->hasMany(\App\Models\Compra::class);
    }
}