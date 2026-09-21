<?php

namespace Tests\Feature;

use App\Models\Perfil;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LandingHeroStyleTest extends TestCase
{
    use RefreshDatabase;

    public function test_landing_hero_style_is_applied_and_visible(): void
    {
        $user = User::factory()->create([
            'rol' => 'agencia',
        ]);

        Perfil::create([
            'user_id' => $user->id,
            'plan_id' => null,
            'slug' => 'mi-agencia-hero',
            'nombre_negocio' => 'Mi agencia hero',
            'descripcion' => 'Venta de autos',
            'telefono' => '123456789',
            'direccion' => 'Calle Falsa 123',
            'hero_estilo' => 'showcase',
        ]);

        $response = $this->get('/vendedor/mi-agencia-hero');

        $response->assertOk();
        $response->assertSee('hero-showcase');
    }
}
