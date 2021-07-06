@extends('layout.main')


@section('content')

	@if(isset($checadas))
	 	<div class="row">
            <table class="table table-striped">
                <tr>
                    <th colspan="7">Data Attendance</th>
                </tr>
                <tr>
                    <th>Numero de empleado</th>
                    <th>Fecha</th>
                    <th>Hora</th>
                </tr>
                    @if (count($checadas) > 0) 
                        @foreach ($checadas as $attItem) 
                            <tr>
                                <td>{{ $attItem['num_empleado'] }} </td>
                                <td>{{ date("d/m/Y", strtotime($attItem['fecha'])) }}</td>
                                <td>{{ date("H:i", strtotime($attItem['time'])) }}</td>
                            </tr>
                        @endforeach
                    @endif
            </table>
		</div>
	@endif

@endsection
