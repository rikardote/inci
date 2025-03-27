<div>
    <h3>Ejercido por Quincena y Centro</h3>
    <a href="{{ route('exportar.toda.suplencias') }}" class="btn btn-success">
        <i class="fas fa-file-excel"></i> Exportar
    </a>
    <table class="table table-striped table-condensed">
        <thead>
            <tr>
                <th class="text-center">Quincena</th>
                @foreach ($montosPorQuincenaCentro['centrosOriginales'] as $index => $centroOriginal)
                <th class="text-right">{{ $montosPorQuincenaCentro['centros'][$index] }}</th>
                @endforeach
                <th class="text-right">Total</th>
                <th class="text-center">Exportar</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($montosPorQuincenaCentro['periodos'] as $periodo => $datos)
            @php
            list($year, $quincena) = explode('.', $periodo);
            @endphp
            <tr data-toggle="collapse" data-target="#quincena-{{ $year }}-{{ $quincena }}"
                class="accordion-toggle"
                data-year="{{ $year }}"
                data-quincena="{{ $quincena }}"
                style="cursor: pointer;">
                <td class="text-center">{{ $quincena }}</td>
                @foreach ($montosPorQuincenaCentro['centrosOriginales'] as $centro)
                <td class="text-right">
                    @php
                    $montoRegistro = $datos->where('centro', $centro)->first();
                    $monto = $montoRegistro ? $montoRegistro->monto_total : 0;
                    @endphp
                    {{ number_format($monto, 2) }}
                </td>
                @endforeach
                <td class="text-right font-weight-bold">{{ number_format($datos->sum('monto_total'), 2) }}</td>
                <td class="text-center">
                    <a href="{{ route('exportar.suplentes.quincena', ['year' => $year, 'quincena' => $quincena]) }}"
                        class="">
                        <i class="fas fa-file-excel" style="color: green;"></i>
                    </a>
                </td>
            </tr>
            <tr>
                <td colspan="{{ count($montosPorQuincenaCentro['centros']) + 3 }}" class="p-0">
                    <div id="quincena-{{ $year }}-{{ $quincena }}" class="collapse">
                        <div class="p-3" id="content-quincena-{{ $year }}-{{ $quincena }}">
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
        <tfoot>
            <tr class="font-weight-bold">
                <td colspan="1">Total por Centro</td>
                @foreach ($montosPorQuincenaCentro['centrosOriginales'] as $centro)
                <td class="text-right">
                    @php
                    $total = $montosPorQuincenaCentro['periodos']->sum(function ($datos) use ($centro) {
                    $montoRegistro = $datos->where('centro', $centro)->first();
                    return $montoRegistro ? $montoRegistro->monto_total : 0;
                    });
                    @endphp
                    {{ number_format($total, 2) }}
                </td>
                @endforeach
                <td class="text-right"><span class="font-weight-bold">{{
                        number_format($montosPorQuincenaCentro['periodos']->sum(function ($datos) { return
                        $datos->sum('monto_total'); }), 2) }}</span></td>
                <td class="text-center"></td>
            </tr>
        </tfoot>
    </table>
</div>
@section('js')
    <script>
    $(document).ready(function() {
        // Maneja el evento de expansión del acordeón
        $('.accordion-toggle').on('click', function() {
            const year = $(this).data('year');
            const quincena = $(this).data('quincena');
            const contentId = `#content-quincena-${year}-${quincena}`;
            const isLoaded = $(contentId).data('loaded');

            // Verifica si los datos ya se han cargado previamente
            if (!isLoaded) {
                // Realiza la petición AJAX para obtener las suplencias
                $.ajax({
                    url: "{{ route('obtener.suplencias.quincena') }}",
                    method: 'GET',
                    data: { year: year, quincena: quincena },
                    success: function(response) {
                        // Actualiza el contenido con los datos recibidos
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
    });
    </script>
@endsection
