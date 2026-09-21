<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VehiculoVentaImagen extends Model
{
    use HasFactory;

    protected $table = 'vehiculo_venta_imagenes';

    protected $fillable = ['vehiculo_id', 'ruta', 'orden'];

    public function vehiculo()
    {
        return $this->belongsTo(Vehiculo::class);
    }
}
