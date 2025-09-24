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
        'telefono',
        'email',
        'licencia_numero',
        'licencia_categoria',
        'licencia_vencimiento',
        'notas',
        // Dirección detallada
        'calle',
        'numero',
        'codigo_postal',
        'localidad',
        'partido',
        'provincia',
    ];

    // Atributos de fecha
    protected $dates = ['licencia_vencimiento', 'deleted_at'];

    // Relación: un chofer puede tener muchos viajes
    public function viajes()
    {
        return $this->hasMany(\App\Models\Viaje::class);
    }
    /**
     * Obtiene el chofer actual asignado al vehículo.
     * Asume que hay un campo 'chofer_id' en la tabla 'vehiculos'.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function choferActual()
    {
        return $this->belongsTo(Chofer::class, 'chofer_id');
    }
}