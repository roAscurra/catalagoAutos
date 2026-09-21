<?php

namespace Tests\Feature;

use App\Models\Marca;
use App\Models\Modelo;
use App\Models\Perfil;
use App\Models\User;
use App\Models\Vehiculo;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class VehicleLandingFormRulesTest extends TestCase
{
    use RefreshDatabase;

    public function test_modelo_debe_pertenecer_a_la_marca_seleccionada(): void
    {
        $user = User::factory()->create([
            'rol' => 'agencia',
        ]);

        $this->actingAs($user);

        Perfil::create([
            'user_id' => $user->id,
            'slug' => 'mi-agencia',
            'nombre_negocio' => 'Mi Agencia',
            'descripcion' => 'Venta de autos',
            'telefono' => '123456789',
            'direccion' => 'Calle Falsa 123',
        ]);

        $ford = Marca::create(['nombre' => 'Ford', 'slug' => 'ford']);
        $chevrolet = Marca::create(['nombre' => 'Chevrolet', 'slug' => 'chevrolet']);

        Modelo::create(['marca_id' => $ford->id, 'nombre' => 'Focus', 'slug' => 'focus']);
        Modelo::create(['marca_id' => $chevrolet->id, 'nombre' => 'Cruze', 'slug' => 'cruze']);

        $response = $this->post(route('panel.vehiculos.store'), [
            'tipo' => 'auto',
            'marca_id' => $ford->id,
            'modelo_id' => $chevrolet->id,
            'anio' => 2024,
            'kilometros' => 12000,
            'precio' => 25000,
            'moneda' => 'USD',
            'combustible' => 'Nafta',
            'ubicacion' => 'CABA',
            'publicado' => true,
            'vendido' => false,
            'mostrar_en_landing' => false,
        ]);

        $response->assertSessionHasErrors(['modelo_id']);
    }

    public function test_vehiculo_eliminado_queda_con_soft_delete(): void
    {
        $user = User::factory()->create([
            'rol' => 'agencia',
        ]);

        $perfil = Perfil::create([
            'user_id' => $user->id,
            'slug' => 'mi-agencia-soft',
            'nombre_negocio' => 'Mi Agencia Soft',
            'descripcion' => 'Venta de autos',
            'telefono' => '123456789',
            'direccion' => 'Calle Falsa 123',
        ]);

        $marca = Marca::create(['nombre' => 'Toyota', 'slug' => 'toyota']);
        $modelo = Modelo::create(['marca_id' => $marca->id, 'nombre' => 'Corolla', 'slug' => 'corolla']);

        $vehiculo = Vehiculo::create([
            'perfil_id' => $perfil->id,
            'tipo' => 'sedan',
            'marca_id' => $marca->id,
            'modelo_id' => $modelo->id,
            'anio' => 2024,
            'kilometros' => 15000,
            'precio' => 28000,
            'moneda' => 'USD',
            'combustible' => 'Nafta',
            'ubicacion' => 'CABA',
            'publicado' => true,
            'vendido' => false,
        ]);

        $vehiculo->delete();

        $this->assertNotNull($vehiculo->fresh()->deleted_at);
    }
}
