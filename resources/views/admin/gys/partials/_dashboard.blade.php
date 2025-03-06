<div class="row">
    <div class="col-md-2">
        <div class="notice notice-success notice-sm">
            <h5 class="card-title">Total Asignado</h5>
            <p class="card-text">$ {{ number_format(\App\Suplencia::ASIGNADO_ANUAL, 2) }}</p>
        </div>
    </div>
    <div class="col-md-2">
        <div class="notice notice-warning notice-sm">
            <h5 class="card-title">Total Ejercido</h5>
            @php
            $totalEjercido = $montosPorQuincenaCentro['periodos']->sum(function ($datos) {
            return $datos->sum('monto_total');
            });
            $porcentajeEjercido = ($totalEjercido / \App\Suplencia::ASIGNADO_ANUAL) * 100;
            @endphp
            <p class="card-text">
                $ {{ number_format($totalEjercido, 2) }} ({{ number_format($porcentajeEjercido, 2) }}%)
            </p>
        </div>
    </div>
    <div class="col-md-2">
        <div class="notice notice-info notice-sm">
            <h5 class="card-title">Total Disponible</h5>
            @php
            $totalDisponible = \App\Suplencia::ASIGNADO_ANUAL - $montosPorQuincenaCentro['periodos']->sum(function
            ($datos) {
            return $datos->sum('monto_total');
            });
            $porcentajeDisponible = ($totalDisponible / \App\Suplencia::ASIGNADO_ANUAL) * 100;
            @endphp
            <p class="card-text">
                $ {{ number_format($totalDisponible, 2) }} ({{ number_format($porcentajeDisponible, 2) }}%)
            </p>
        </div>
    </div>
</div>
