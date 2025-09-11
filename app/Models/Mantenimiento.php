<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Mantenimiento extends Model
{
    use HasFactory;

    protected $fillable = [
        'vehiculo_id',
        'tipo',
        'kilometraje',
        'fecha',
        'costo',
        'descripcion',
        'proveedor',
    ];

    protected $dates = ['fecha'];

    public function vehiculo()
    {
        return $this->belongsTo(Vehiculo::class);
    }
}