<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Modelo extends Model
{
    use HasFactory;

    protected $table = 'modelos';

    protected $fillable = ['marca_id', 'nombre', 'slug'];

    public function marca()
    {
        return $this->belongsTo(Marca::class);
    }

    public function vehiculos()
    {
        return $this->hasMany(Vehiculo::class);
    }
}