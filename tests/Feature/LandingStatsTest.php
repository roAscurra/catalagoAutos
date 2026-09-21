<?php

namespace Tests\Feature;

use App\Models\Perfil;
use App\Models\User;
use App\Models\Vehiculo;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LandingStatsTest extends TestCase
{
    use RefreshDatabase;

    public function test_landing_shows_active_vehicle_count_and_sales_count(): void
    {
        $user = User::factory()->create([
            'rol' => 'agencia',
        ]);

        $perfil = Perfil::create([
            'user_id' => $user->id,
            'plan_id' => null,
            'slug' => 'mi-agencia-test',
            'nombre_negocio' => 'Mi agencia',
            'descripcion' => 'Venta de autos',
            'telefono' => '123456789',
            'direccion' => 'Calle Falsa 123',
        ]);

        $marca = \App\Models\Marca::create(['nombre' => 'Ford', 'slug' => 'ford']);
        $modelo = \App\Models\Modelo::create(['marca_id' => $marca->id, 'nombre' => 'Focus', 'slug' => 'focus']);

        Vehiculo::create([
            'perfil_id' => $perfil->id,
            'tipo' => 'sedan',
            'marca_id' => $marca->id,
            'modelo_id' => $modelo->id,
            'anio' => 2024,
            'kilometros' => 15000,
            'precio' => 25000,
            'moneda' => 'USD',
            'publicado' => true,
            'vendido' => false,
            'mostrar_en_landing' => false,
        ]);

        Vehiculo::create([
            'perfil_id' => $perfil->id,
            'tipo' => 'suv',
            'marca_id' => $marca->id,
            'modelo_id' => $modelo->id,
            'anio' => 2023,
            'kilometros' => 30000,
            'precio' => 32000,
            'moneda' => 'USD',
            'publicado' => true,
            'vendido' => true,
            'mostrar_en_landing' => true,
            'fecha_venta' => '2026-09-10',
        ]);

        $response = $this->get('/vendedor/mi-agencia-test');

        $response->assertOk();
        $response->assertSee('1');
        $response->assertSee('vehículo');
        $response->assertSee('disponible');
        $response->assertSee('venta');
    }
}
