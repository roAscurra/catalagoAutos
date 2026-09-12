@php($vehiculosActivos = $perfil->vehiculos->filter(fn($vehiculo) => $vehiculo->publicado && !$vehiculo->vendido)->values())
@php($vehiculosVendidos = $perfil->vehiculos->filter(fn($vehiculo) => $vehiculo->vendido && $vehiculo->mostrar_en_landing)->values())

<section class="seller-inventory" id="inventario">
    <div class="section-heading">
        <div>
            <span class="eyebrow">En stock</span>
            <h2>Vehículos disponibles</h2>
        </div>
        <span class="result-count">{{ $vehiculosActivos->count() }} {{ trans_choice('vehículo|vehículos', $vehiculosActivos->count()) }}</span>
    </div>
    <div class="vehicle-grid">
        @forelse($vehiculosActivos as $vehiculo)
            @php($vehicleImage = $vehiculo->imagenes->first()?->ruta ?: $vehiculo->imagen)
            @php($vehicleImage = $vehicleImage ? asset('storage/' . $vehicleImage) : 'https://images.unsplash.com/photo-1553440569-bcc63803a83d?auto=format&fit=crop&w=900&q=80')
            <article class="vehicle-card"><a href="{{ route('vehiculo.show', $vehiculo) }}"><div class="vehicle-image" style="background-image:url('{{ $vehicleImage }}')"><span class="vehicle-type">{{ ucfirst($vehiculo->tipo) }}</span></div></a><div class="vehicle-info"><p class="muted">{{ $vehiculo->marca?->nombre }}</p><h3>{{ $vehiculo->modelo?->nombre }}</h3><div class="vehicle-meta"><span>{{ $vehiculo->anio }}</span><strong>{{ $vehiculo->moneda }} {{ number_format($vehiculo->precio ?? 0,0,',','.') }}</strong></div><a href="{{ route('vehiculo.show', $vehiculo) }}">Ver detalles <span>↗</span></a></div></article>
        @empty
            <div class="empty-state"><strong>Sin publicaciones activas.</strong><span>Pronto habrá novedades.</span></div>
        @endforelse
    </div>

    @if($vehiculosVendidos->isNotEmpty())
        <div class="section-heading sold-section-heading">
            <div>
                <span class="eyebrow">Ventas recientes</span>
                <h2>Vehículos vendidos</h2>
            </div>
        </div>
        <div class="vehicle-grid sold-grid">
            @foreach($vehiculosVendidos as $vehiculo)
                @php($vehicleImage = $vehiculo->imagenes->first()?->ruta ?: $vehiculo->imagen)
                @php($vehicleImage = $vehicleImage ? asset('storage/' . $vehicleImage) : 'https://images.unsplash.com/photo-1553440569-bcc63803a83d?auto=format&fit=crop&w=900&q=80')
                <article class="vehicle-card sold-card">
                    <div class="vehicle-image" style="background-image:url('{{ $vehicleImage }}')">
                        <span class="vehicle-type">{{ ucfirst($vehiculo->tipo) }}</span>
                        <span class="vehicle-status sold">Vendido</span>
                    </div>
                    <div class="vehicle-info">
                        <p class="muted">{{ $vehiculo->marca?->nombre }}</p>
                        <h3>{{ $vehiculo->modelo?->nombre }}</h3>
                        <div class="vehicle-meta"><span>{{ $vehiculo->fecha_venta ? $vehiculo->fecha_venta->format('d/m/Y') : 'Fecha disponible' }}</span><strong>{{ $vehiculo->moneda }} {{ number_format($vehiculo->precio ?? 0,0,',','.') }}</strong></div>
                    </div>
                </article>
            @endforeach
        </div>
    @endif
</section>