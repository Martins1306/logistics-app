<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Viaje extends Model
{
    use HasFactory;

    // Nombre de la tabla principal
    protected $table = 'viajes';

    // Campos que se pueden asignar masivamente
    protected $fillable = [
        'vehiculo_id',
        'chofer_id',
        'cliente_id', 
        'origen',
        'destino',
        'fecha_salida',
        'fecha_llegada',
        'kilometros',
        'descripcion_carga',
        'estado',
        'tipo', // ← Añadido
    ];

    // Atributos que deben tratarse como fechas
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
  
    // Relación: un viaje un cliente  
    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    // Relación: un viaje pertenece a un chofer
    public function chofer()
    {
        return $this->belongsTo(Chofer::class);
    }

    // Relación muchos a muchos: un viaje lleva muchos productos
    // Tabla pivote: producto_viaje (sin ID autoincremental)
    public function productos()
    {
        return $this->belongsToMany(
            Producto::class,           // Modelo relacionado
            'producto_viaje',          // Nombre de la tabla pivote
            'viaje_id',                // FK en pivote hacia este modelo
            'producto_id'              // FK en pivote hacia Producto
        )
        ->withPivot('cantidad', 'notas')  // Campos adicionales en pivote
        ->withTimestamps();               // created_at y updated_at en pivote
    }
}