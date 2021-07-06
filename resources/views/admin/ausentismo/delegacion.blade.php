@extends('layout.main')
	@section('title', 'Ausentimo por Delegacion')
	
	@section('content')
		@include('admin.ausentismo.estadistica_dele')
	
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
		            text: 'DELEGACION ESTATAL BAJA CALIFORNIA NORTE'
		        },
		        subtitle: {
		            text: 'REPORTE DE AUSENTISMO'
		        },
		        xAxis: {
		            categories: [
						'01 '+'({{$codigo_01->count ? $codigo_01->count:0}})',
						'02 '+'({{$codigo_02->count ? $codigo_02->count:0}})',
						'03 '+'({{$codigo_03->count ? $codigo_03->count:0}})',
		                '10 '+'({{$codigo_10->count ? $codigo_10->count:0}})',
		                '14 '+'({{$codigo_14->count ? $codigo_14->count:0}})',
		                '17 '+'({{$codigo_17->count ? $codigo_17->count:0}})',
		                '40 '+'({{$codigo_40->count ? $codigo_40->count:0}})',
		                '41 '+'({{$codigo_41->count ? $codigo_41->count:0}})',
		                '42 '+'({{$codigo_42->count ? $codigo_42->count:0}})',
		                '46 '+'({{$codigo_46->count ? $codigo_46->count:0}})',
		                '47 '+'({{$codigo_47->count ? $codigo_47->count:0}})',
		                '48 '+'({{$codigo_48->count ? $codigo_48->count:0}})',
		                '49 '+'({{$codigo_49->count ? $codigo_49->count:0}})',
		                '51 '+'({{$codigo_51->count ? $codigo_51->count:0}})',
		                '53 '+'({{$codigo_53->count ? $codigo_53->count:0}})',
		                '54 '+'({{$codigo_54->count ? $codigo_54->count:0}})',
		                '55 '+'({{$codigo_55->count ? $codigo_55->count:0}})',
		                '60 '+'({{$codigo_60->count ? $codigo_60->count:0}})',
		                '62 '+'({{$codigo_62->count ? $codigo_62->count:0}})',
		                '63 '+'({{$codigo_63->count ? $codigo_63->count:0}})',
		                '94 '+'({{$codigo_94->count ? $codigo_94->count:0}})',
						'907 '+'({{$codigo_907->count ? $codigo_907->count:0}})',
		                '100 '+'({{$codigo_100->count ? $codigo_100->count:0}})'
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
		            data: [{{$codigo_01->count ? $codigo_01->count:0}},{{$codigo_02->count ? $codigo_02->count:0}},{{$codigo_03->count ? $codigo_03->count:0}},{{$codigo_10->count ? $codigo_10->count:0}},{{$codigo_14->count ? $codigo_14->count:0}},{{$codigo_17->count ? $codigo_17->count:0}}, {{$codigo_40->count ? $codigo_40->count:0}}, {{$codigo_41->count ? $codigo_41->count:0}},{{$codigo_42->count ? $codigo_42->count:0}}, {{$codigo_46->count ? $codigo_46->count:0}}, {{$codigo_47->count ? $codigo_47->count:0}}, {{$codigo_48->count ? $codigo_48->count:0}}, {{$codigo_49->count ? $codigo_49->count:0}}, {{$codigo_51->count ? $codigo_51->count:0}}, {{$codigo_53->count ? $codigo_53->count:0}}, {{$codigo_54->count ? $codigo_54->count:0}}, {{$codigo_55->count ? $codigo_55->count:0}}, {{$codigo_60->count ? $codigo_60->count:0}}, {{$codigo_62->count ? $codigo_62->count:0}}, {{$codigo_63->count ? $codigo_63->count:0}}, {{$codigo_94->count ? $codigo_94->count:0}}, {{$codigo_907->count ? $codigo_907->count:0}},{{$codigo_100->count ? $codigo_100->count:0}}]
		      
		        }]
		    });
		});
	</script>

	@endsection