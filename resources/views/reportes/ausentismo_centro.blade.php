@extends('layout.main')
@section('title', 'Ausentimo por Centro')

@section('content')
    @include('reportes.estadistica')

    <div id="container" style="height: 400px"></div>
@endsection

@section('js')
    <script src="{{ asset('plugins/hightcharts/js/highcharts.js') }}"></script>
    <script src="{{ asset('plugins/hightcharts/js/highcharts-3d.js') }}"></script>
    <script src="{{ asset('plugins/hightcharts/js/modules/exporting.js') }}"></script>

    <script type="text/javascript">
        $(function () {
            $('#container').highcharts({
                chart: {
                    type: 'column'
                },
                credits: {
                    enabled: false
                },
                title: {
                    text: '{{ $dpto->code }} - {!! str_replace('"', ' ', $dpto->description) !!}'
                },
                subtitle: {
                    text: 'REPORTE DE AUSENTISMO'
                },
                xAxis: {
                    categories: [
                        @foreach([1, 2, 3, 4, 8, 9, 10, 14, 15, 17, 30, 40, 41, 42, 46, 47, 48, 49, 51, 53, 54, 55, 60, 61, 62, 63, 94, 100] as $codigo)
                            '{{ sprintf("%02d", $codigo) }} ({{ $incidencias['codigo_' . sprintf("%02d", $codigo)] ?? 0 }})',
                        @endforeach
                    ],
                    crosshair: true
                },
                yAxis: {
                    min: 0,
                    title: {
                        text: 'Total Incidencias'
                    }
                },
                tooltip: {
                    headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
                    pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                        '<td style="padding:0"><b>{point.y:1f} </b></td></tr>',
                    footerFormat: '</table>',
                    shared: true,
                    useHTML: true
                },
                plotOptions: {
                    column: {
                        pointPadding: 0.2,
                        borderWidth: 0
                    }
                },
                series: [{
                    name: 'Total de Incidencias',
                    data: [
                        @foreach([1, 2, 3, 4, 8, 9, 10, 14, 15, 17, 30, 40, 41, 42, 46, 47, 48, 49, 51, 53, 54, 55, 60, 61, 62, 63, 94, 100] as $codigo)
                            {{ $incidencias['codigo_' . sprintf("%02d", $codigo)] ?? 0 }},
                        @endforeach
                    ]
                }]
            });
        });
    </script>
@endsection
