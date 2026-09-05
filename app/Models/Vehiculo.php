<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vehiculo extends Model
{
    use HasFactory;

    protected $table = 'vehiculos';

    protected $fillable = [
        'perfil_id', 'tipo', 'marca_id', 'modelo_id', 'anio', 'kilometros',
        'precio', 'moneda', 'ubicacion', 'imagen', 'descripcion', 'publicado',
    ];

    protected $casts = ['publicado' => 'boolean', 'precio' => 'decimal:2'];

    public function perfil()
    {
        return $this->belongsTo(Perfil::class);
    }

    public function marca()
    {
        return $this->belongsTo(Marca::class);
    }

    public function modelo()
    {
        return $this->belongsTo(Modelo::class);
    }
}