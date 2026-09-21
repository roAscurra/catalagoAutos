@extends('admin.layout')

@section('content')
<div class="admin-heading">
    <div>
        <p class="eyebrow">Suscripción</p>
        <h1>Mi plan</h1>
        <p>Controlá tu plan activo, rendimiento y estado de tus publicaciones.</p>
    </div>
</div>

<section class="panel-summary-grid">
    <div class="summary-card highlight-card">
        <span class="eyebrow">Plan activo</span>
        <h2>{{ $currentPlan?->name ?: 'Sin plan seleccionado' }}</h2>
        <p>{{ $currentPlan ? '$' . number_format($currentPlan->monthly_price, 0, ',', '.') . '/mes' : 'Elegí un plan para activar funciones premium.' }}</p>
    </div>

    <div class="summary-card">
        <span class="eyebrow">Estadísticas</span>
        <ul class="stats-list">
            <li><strong>{{ $stats['publicadas'] }}</strong> disponibles</li>
            <li><strong>{{ $stats['vendidas'] }}</strong> vendidos</li>
            <li><strong>{{ $stats['landing'] }}</strong> en landing</li>
        </ul>
    </div>

    <div class="summary-card">
        <span class="eyebrow">Límite</span>
        <p>{{ $currentPlan?->vehicle_limit ? 'Máximo ' . $currentPlan->vehicle_limit . ' publicaciones activas' : 'Sin límite definido' }}</p>
    </div>
</section>

<section class="panel-form-card">
    <div class="card-header">
        <h2>Planes disponibles</h2>
    </div>

    <form method="POST" action="{{ route('panel.plan.update') }}" class="admin-form">
        @csrf
        @method('PUT')

        <div class="form-grid">
            <label>
                Cambiar plan
                <select name="plan_id" required>
                    @foreach($plans as $plan)
                        <option value="{{ $plan->id }}" @selected($currentPlan?->id == $plan->id)>
                            {{ $plan->name }} · ${{ number_format($plan->monthly_price, 0, ',', '.') }}/mes
                        </option>
                    @endforeach
                </select>
            </label>
        </div>

        <div class="panel-actions">
            <button type="submit" class="button button-orange">Guardar plan</button>
        </div>
    </form>

    <div class="plan-list">
        @foreach($plans as $plan)
            <article class="plan-option @if($currentPlan && $currentPlan->id === $plan->id) is-current @endif">
                <div>
                    <span class="eyebrow">{{ $plan->name }}</span>
                    <h3>${{ number_format($plan->monthly_price, 0, ',', '.') }}/mes</h3>
                </div>
                <p>{{ $plan->vehicle_limit ? 'Hasta ' . $plan->vehicle_limit . ' publicaciones' : 'Sin límite de publicaciones' }}</p>
                @if($currentPlan && $currentPlan->id === $plan->id)
                    <span class="plan-pill">Actual</span>
                @else
                    <span class="plan-pill muted">Disponible</span>
                @endif
            </article>
        @endforeach
    </div>
</section>
@endsection
