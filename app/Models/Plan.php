<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'slug', 'vehicle_limit', 'monthly_price'];

    protected $casts = ['monthly_price' => 'decimal:2'];

    public function perfiles()
    {
        return $this->hasMany(Perfil::class);
    }
}