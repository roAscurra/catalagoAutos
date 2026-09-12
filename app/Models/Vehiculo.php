<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Vehiculo extends Model
{
    use HasFactory;

    protected $table = 'vehiculos';

    protected $fillable = [
        'perfil_id', 'public_id', 'tipo', 'marca_id', 'modelo_id', 'anio', 'kilometros',
        'precio', 'moneda', 'combustible', 'ubicacion', 'imagen', 'descripcion', 'publicado',
        'vendido', 'mostrar_en_landing', 'fecha_venta',
    ];

    protected $casts = [
        'publicado' => 'boolean',
        'vendido' => 'boolean',
        'mostrar_en_landing' => 'boolean',
        'precio' => 'decimal:2',
        'fecha_venta' => 'date',
    ];

    protected static function booted(): void
    {
        static::creating(function (Vehiculo $vehiculo): void {
            $vehiculo->public_id ??= (string) Str::uuid();
        });
    }

    public function getRouteKeyName(): string
    {
        return 'public_id';
    }

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

    public function imagenes()
    {
        return $this->hasMany(VehiculoImagen::class)->orderBy('orden');
    }

    public function ventaImagenes()
    {
        return $this->hasMany(VehiculoVentaImagen::class)->orderBy('orden');
    }
}