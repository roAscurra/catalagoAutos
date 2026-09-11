<section class="seller-contact-band seller-contact-rich" id="contacto">
    <div class="contact-heading"><span class="eyebrow">Hablemos</span>
        <h2>Tu próximo vehículo puede empezar con un mensaje.</h2>
        <p>Contanos qué estás buscando y te ayudamos a encontrar la opción indicada.</p>
    </div>
    <div class="contact-panel">
        <div><small>Contacto
                directo</small><strong>{{ $perfil->whatsapp ?: ($perfil->telefono ?: 'Disponible por consulta') }}</strong>
        </div>
        <div><small>Ubicación</small><strong>{{ $perfil->direccion ?: 'Atención personalizada' }}</strong></div>
        @if($perfil->whatsapp)<a class="button button-whatsapp"
            href="https://wa.me/{{ preg_replace('/[^0-9]/','',$perfil->whatsapp) }}?text={{ urlencode('Hola ' . $perfil->nombre_negocio . ', quiero consultar por sus vehículos disponibles.') }}">Escribir
            por WhatsApp <span>↗</span></a>@endif
    </div>
</section>