@php
    $vehiculosActivos = $perfil->vehiculos
        ->filter(fn($vehiculo) => $vehiculo->publicado && !$vehiculo->vendido)
        ->values();
    $ventasLanding = $perfil->vehiculos
        ->filter(fn($vehiculo) => $vehiculo->vendido && $vehiculo->mostrar_en_landing)
        ->values();
    $cantidadVehiculosDisponibles = $vehiculosActivos->count();
    $cantidadVentas = $ventasLanding->count();
@endphp

<section class="seller-intro seller-intro-rich" id="sobre-nosotros">
    <div class="intro-copy"><span class="eyebrow">Sobre nosotros</span>
        <h2>{{ $perfil->nombre_negocio }}</h2>
        <p>{{ $perfil->descripcion ?: 'Atención personalizada y vehículos seleccionados para que encuentres exactamente lo que estás buscando.' }}
        </p>
    </div>
    <div class="intro-aside">
        <div class="intro-stat">
            <strong class="stat-counter" data-target="{{ $cantidadVehiculosDisponibles }}">
                0
            </strong>

            <span>
                {{ trans_choice('vehículo|vehículos', $cantidadVehiculosDisponibles) }}
                <br>
                disponible
            </span>
        </div>

        @if($cantidadVentas > 0)

            <div class="intro-stat">+
                <strong class="stat-counter" data-target="{{ $cantidadVentas }}">
                    0
                </strong>

                <span>
                    {{ trans_choice('venta|ventas', $cantidadVentas) }}
                </span>
            </div>
            
        @endif
        
        <div class="intro-detail"><small>Encontranos
                en</small><strong>{{ $perfil->direccion ?: 'Ubicación a confirmar' }}</strong></div>
        <div class="intro-detail"><small>Atención
                directa</small><strong>{{ $perfil->telefono ?: 'Consultá por WhatsApp' }}</strong></div>
    </div>
</section>
