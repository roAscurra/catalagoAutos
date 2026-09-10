@extends('admin.layout')
@section('content')
@php($labels = ['planes'=>'Planes','perfiles'=>'Vendedores','marcas'=>'Marcas','modelos'=>'Modelos','vehiculos'=>'Vehículos'])
<div class="admin-heading">
    <div>
        <p class="eyebrow">Gestión</p>
        <h1>{{ $labels[$resource] }}</h1>
        <p>{{ $items->total() }} registros en el sistema.</p>
    </div><a class="button button-orange" href="{{ route('admin.create', $resource) }}">Nuevo registro <span>+</span></a>
</div>
<div class="admin-table-wrap resource-table">
    <table class="admin-table">
        <thead>
            <tr>
                <th>Registro</th>
                <th>Relaciones</th>
                <th>Información</th>
                <th></th>
            </tr>
        </thead>
        <tbody>@forelse($items as $item)<tr>
                <td><strong>@if($resource === 'vehiculos'){{ $item->marca?->nombre }} {{ $item->modelo?->nombre }}@elseif($resource === 'perfiles'){{ $item->nombre_negocio }}@elseif($resource === 'modelos'){{ $item->nombre }}@else{{ $item->name ?? $item->nombre }}@endif</strong><small>{{ $item->slug ?? 'ID #' . $item->id }}</small></td>
                <td>@if($resource === 'vehiculos'){{ $item->perfil?->nombre_negocio }}@elseif($resource === 'modelos'){{ $item->marca?->nombre }}@elseif($resource === 'perfiles'){{ $item->plan?->name ?: 'Sin plan' }}@elseif($resource === 'marcas'){{ $item->modelos->count() }} modelos @else{{ $item->vehicle_limit ? $item->vehicle_limit . ' vehículos' : 'Ilimitado' }}@endif</td>
                <td>@if($resource === 'vehiculos'){{ strtoupper($item->tipo) }} · {{ $item->moneda }} {{ number_format($item->precio ?? 0, 0, ',', '.') }}@elseif($resource === 'planes')${{ number_format($item->monthly_price, 0, ',', '.') }}/mes @else{{ $item->telefono ?? $item->direccion ?? '' }}@endif</td>
                <td class="table-actions"><a href="{{ route('admin.edit', [$resource, $item->id]) }}">Editar</a>
                    <form method="POST" action="{{ route('admin.destroy', [$resource, $item->id]) }}" onsubmit="return confirm('¿Eliminar este registro?')">@csrf @method('DELETE')<button type="submit">Eliminar</button></form>
                </td>
            </tr>@empty<tr>
                <td colspan="4" class="table-empty">Todavía no hay registros.</td>
            </tr>@endforelse</tbody>
    </table>
</div>{{ $items->links() }}
@endsection