<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Perfil extends Model
{
    use HasFactory;

    protected $table = 'perfil';
    
    protected $fillable = [
        'user_id',
        'plan_id',
        'slug',
        'nombre_negocio',
        'logo',
        'telefono',
        'direccion',
        'descripcion',
        'color_principal',
        'plantilla',
        'color_secundario',
        'imagen_portada',
        'titulo_portada',
        'subtitulo_portada',
        'whatsapp',
        'instagram',
        'facebook',
        'secciones',
    ];

    protected $casts = [
        'secciones' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }

    public function vehiculos()
    {
        return $this->hasMany(Vehiculo::class);
    }

    public function esAgencia(): bool
    {
        return $this->user?->rol === 'agencia';
    }
}
