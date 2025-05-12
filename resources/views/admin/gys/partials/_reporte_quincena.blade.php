<div>
    <!-- Título y botón de exportación -->
    <h3>Ejercido por Quincena y Centro</h3>
    <a href="{{ route('exportar.toda.suplencias') }}" class="btn btn-success">
        <i class="fas fa-file-excel"></i> Exportar
    </a>

    <!-- Tabla principal -->
    <table class="table table-striped table-condensed">
        <!-- Cabecera de la tabla -->
        <thead>
            <tr>
                <th class="text-center">Quincena</th>
                <!-- Columnas dinámicas para cada centro -->
                @foreach ($montosPorQuincenaCentro['centrosOriginales'] as $index => $centroOriginal)
                <th class="text-right">{{ $montosPorQuincenaCentro['centros'][$index] }}</th>
                @endforeach
                <th class="text-right">Total</th>
                <th class="text-center">Exportar</th>
                @if(Auth::user()->username == '332618')
                    <th class="text-center">Eliminar</th>
                @endif
            </tr>
        </thead>

        <!-- Cuerpo de la tabla -->
        <tbody>
            <!-- Loop por cada periodo (quincena) -->
            @foreach ($montosPorQuincenaCentro['periodos'] as $periodo => $datos)
            @php
            // Extraer año y quincena del periodo (formato: año.quincena)
            list($year, $quincena) = explode('.', $periodo);
            @endphp

            <!-- Fila principal con datos de la quincena -->
            <tr data-toggle="collapse" data-target="#quincena-{{ $year }}-{{ $quincena }}"
                class="accordion-toggle"
                data-year="{{ $year }}"
                data-quincena="{{ $quincena }}"
                style="cursor: pointer;">

                <!-- Columna de quincena (formateada a 2 dígitos) -->
                <td class="text-center">{{ str_pad($quincena, 2, '0', STR_PAD_LEFT) }}</td>

                <!-- Columnas dinámicas con montos por centro -->
                @foreach ($montosPorQuincenaCentro['centrosOriginales'] as $centro)
                <td class="text-right">
                    @php
                    // Buscar monto para este centro en esta quincena
                    $montoRegistro = $datos->where('centro', $centro)->first();
                    $monto = $montoRegistro ? $montoRegistro->monto_total : 0;
                    @endphp
                    ${{ number_format($monto, 2) }}
                </td>
                @endforeach

                <!-- Columna de total para la quincena -->
                <td class="text-right font-weight-bold">
                    ${{ number_format($datos->sum('monto_total'), 2) }}
                </td>

                <!-- Columna con botón para exportar esta quincena -->
                <td class="text-center">
                    <a href="{{ route('exportar.suplentes.quincena', ['year' => $year, 'quincena' => $quincena]) }}">
                        <i class="fas fa-file-excel" style="color: green;"></i>
                    </a>
                </td>
                {{-- Nuevo botón para eliminar la quincena --}}
                <td class="text-center">
                    @if(Auth::user()->username == '332618')
                        <button class="btn btn-danger btn-xs delete-quincena" data-year="{{ $year }}" data-quincena="{{ $quincena }}">
                            <i class="fas fa-trash"></i>
                        </button>
                    @endif
                </td>
            </tr>

            <!-- Fila desplegable (collapse) para detalles -->
            <tr>
                <td colspan="{{ count($montosPorQuincenaCentro['centros']) + 3 }}" class="p-0">
                    <div id="quincena-{{ $year }}-{{ $quincena }}" class="collapse">
                        <div class="p-3" id="content-quincena-{{ $year }}-{{ $quincena }}">
                            <!-- Placeholder para contenido cargado via AJAX -->
                            <div class="text-center py-3">
                                <div class="spinner-border text-primary" role="status">
                                    <span class="sr-only">Cargando...</span>
                                </div>
                                <p>Cargando datos...</p>
                            </div>
                        </div>
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>

        <!-- Pie de tabla con totales por centro -->
        <tfoot>
            <tr class="font-weight-bold">
                <td colspan="1">Total por Centro</td>
                @foreach ($montosPorQuincenaCentro['centrosOriginales'] as $centro)
                <td class="text-right">
                    @php
                    // Calcular total por centro (suma de todas las quincenas)
                    $total = $montosPorQuincenaCentro['periodos']->sum(function ($datos) use ($centro) {
                        $montoRegistro = $datos->where('centro', $centro)->first();
                        return $montoRegistro ? $montoRegistro->monto_total : 0;
                    });
                    @endphp
                    ${{ number_format($total, 2) }}
                </td>
                @endforeach
                <!-- Total general -->
                <td class="text-right">
                    <span class="font-weight-bold">
                        ${{ number_format($montosPorQuincenaCentro['periodos']->sum(function ($datos) {
                            return $datos->sum('monto_total');
                        }), 2) }}
                    </span>
                </td>
                <td class="text-center"></td>
                {{-- Nueva columna vacía para alinear con el botón de eliminar --}}
                <td class="text-center"></td>
            </tr>
        </tfoot>
    </table>
</div>

<!-- Script para manejar el despliegue de detalles -->
@section('js')
<script>
$(document).ready(function() {
    // Manejar clic en filas con clase accordion-toggle
    $('.accordion-toggle').on('click', function() {
        const year = $(this).data('year');
        const quincena = $(this).data('quincena');
        const contentId = `#content-quincena-${year}-${quincena}`;
        const isLoaded = $(contentId).data('loaded');

        // Cargar datos via AJAX solo si no están cargados
        if (!isLoaded) {
            $.ajax({
                url: "{{ route('obtener.suplencias.quincena') }}",
                method: 'GET',
                data: { year: year, quincena: quincena },
                success: function(response) {
                    $(contentId).html(response);
                    $(contentId).data('loaded', true);
                },
                error: function(xhr) {
                    $(contentId).html(`
                        <div class="alert alert-danger">
                            Error al cargar los datos: ${xhr.statusText}
                        </div>
                    `);
                }
            });
        }
    });

    // Manejar clic en el botón de eliminar quincena
    $('.delete-quincena').on('click', function(e) {
        e.stopPropagation();

        const year = $(this).data('year');
        const quincena = $(this).data('quincena');
        const $button = $(this);

        if (confirm(`¿Eliminar quincena ${quincena} del ${year}?`)) {
            $.ajax({
                url: "{{ route('eliminar.suplencias.quincena') }}",
                method: 'POST',
                data: { year, quincena },
                success: function(response) {
                    if (response.success) {
                        // 1. Eliminar fila visualmente
                        $button.closest('tr').next('tr').remove(); // Elimina fila de detalles
                        $button.closest('tr').remove(); // Elimina fila principal

                        // 2. Actualizar totales
                        updateTotals(response.montosPorQuincenaCentro);

                        // Configurar opciones de toastr
                        toastr.options = {
                            "closeButton": false,
                            "debug": false,
                            "newestOnTop": false,
                            "progressBar": true,
                            "positionClass": "toast-bottom-center",
                            "timeOut": "1500",
                        };
                        toastr.success('Quincena eliminada correctamente.');
                    }
                },
                error: function(xhr) {
                    toastr.error('Error: ' + (xhr.responseJSON?.message || 'Error desconocido'));
                }
            });
        }
    });

    // Función reutilizable para actualizar totales
    function updateTotals(montos) {
        // Actualizar totales por centro
        montos.centrosOriginales.forEach((centro, index) => {
            let total = 0;
            for (const periodo in montos.periodos) {
                const datos = montos.periodos[periodo];
                const montoRegistro = datos.find(item => item.centro === centro);
                if (montoRegistro) {
                    // Usar el valor ya formateado
                    total += parseFloat(montoRegistro.monto_total.replace(/,/g, ''));
                }
            }
            // Mantener el formato existente
            $('tfoot tr td.text-right').eq(index).text('$' + total.toLocaleString('en-US', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            }));
        });

        // Actualizar total general
        let totalGeneral = 0;
        for (const periodo in montos.periodos) {
            const datos = montos.periodos[periodo];
            datos.forEach(item => {
                totalGeneral += parseFloat(item.monto_total.replace(/,/g, ''));
            });
        }
        $('tfoot tr td.text-right span.font-weight-bold').text(
            '$' + totalGeneral.toLocaleString('en-US', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            })
        );
    }
});

$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});
</script>
@endsection
