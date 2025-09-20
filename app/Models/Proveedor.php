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
        'direccion',
        'notas',
    ];

    public function compras()
    {
        return $this->hasMany(\App\Models\Compra::class);
    }
}