<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Viaje extends Model
{
    use HasFactory;

    // Tabla asociada (opcional)
    protected $table = 'viajes';

    // Campos que se pueden asignar masivamente (una sola vez)
    protected $fillable = [
        'vehiculo_id',
        'chofer_id',
        'origen',
        'destino',
        'fecha_salida',
        'fecha_llegada',
        'kilometros',
        'descripcion_carga',
        'estado',
    ];

    // Atributos de fecha
    protected $dates = [
        'fecha_salida',
        'fecha_llegada',
        'created_at',
        'updated_at'
    ];

    // Relación: un viaje pertenece a un vehículo
    public function vehiculo()
    {
        return $this->belongsTo(Vehiculo::class);
    }

    // Relación: un viaje puede llevar muchos productos
    public function productos()
    {
        return $this->belongsToMany(Producto::class)
                    ->withPivot('cantidad', 'notas')
                    ->withTimestamps();
    }

    // Relación: un viaje pertenece a un chofer
    public function chofer()
    {
        return $this->belongsTo(Chofer::class);
    }
}