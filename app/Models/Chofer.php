<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Chofer extends Model
{
    use HasFactory, SoftDeletes;

    // Especificar el nombre correcto de la tabla
    protected $table = 'choferes';

    // Campos que se pueden asignar masivamente
    protected $fillable = [
        'nombre',
        'apellido',
        'dni',
        'licencia_tipo',
        'licencia_vencimiento',
        'telefono',
        'observaciones',
    ];

    // Atributos de fecha
    protected $dates = ['licencia_vencimiento', 'deleted_at'];

    // Relación: un chofer puede tener muchos viajes
    public function viajes()
    {
        return $this->hasMany(\App\Models\Viaje::class);
    }
}