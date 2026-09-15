<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Perfil extends Model
{
    use HasFactory;

    public const DEFAULT_SECTIONS = ['hero', 'intro', 'inventario', 'contacto', 'footer'];
    public const VALID_TEMPLATES = ['editorial', 'alto-contraste', 'calma'];
    public const VALID_HERO_STYLES = ['showcase', 'spotlight', 'gallery'];

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
        'hero_estilo',
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

    public function getEffectiveTemplateAttribute(): string
    {
        return in_array($this->plantilla, self::VALID_TEMPLATES, true)
            ? $this->plantilla
            : 'editorial';
    }

    public function getEffectiveHeroStyleAttribute(): string
    {
        return in_array($this->hero_estilo, self::VALID_HERO_STYLES, true)
            ? $this->hero_estilo
            : 'showcase';
    }

    public function getEffectiveSectionsAttribute(): array
    {
        $sections = $this->secciones ?? self::DEFAULT_SECTIONS;

        if (!is_array($sections)) {
            return self::DEFAULT_SECTIONS;
        }

        $normalized = array_values(array_unique(array_filter(
            $sections,
            fn ($section) => in_array($section, self::DEFAULT_SECTIONS, true)
        )));

        return $normalized ?: self::DEFAULT_SECTIONS;
    }

    public function hasLandingSection(string $section): bool
    {
        return in_array($section, $this->effective_sections, true);
    }

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
