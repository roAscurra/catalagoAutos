<section class="seller-hero seller-hero--{{ $perfil->effective_hero_style ?? 'showcase' }}">
    <div class="seller-hero-content">
        <span class="eyebrow">{{ $perfil->plan?->name ?: 'Vendedor verificado' }}</span>
        <h1>{{ $landingTitle }}</h1>
        <p>{{ $landingSubtitle }}</p>
        @if(in_array('inventario',$sections,true))
            <a class="seller-cta" href="#inventario">Ver inventario <span>↓</span></a>
        @endif
    </div>

    @if($perfil->imagen_portada)
        <div class="seller-hero-image" data-cover-image="{{ asset('storage/' . $perfil->imagen_portada) }}"></div>
    @else
        <div class="seller-hero-pattern"></div>
    @endif
</section>