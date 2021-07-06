@extends('layout.main')

@section('title', 'Ver checadas')


@section('content')
    
    <div class="container">
        <table class="table table-condensed">
            <thead>
                <th>Numero de Empleado</th>
                <th>Fecha</th>
                <th>Entrada</th>
            </thead>
            <tbody>
                @foreach ($checadas as $checada)
                    <tr>
                        <td>{{ $checada->num_empleado }}</td>
                        <td>{{ $checada->fecha }}</td>
                        <td>{{ validar_entrada($checada->num_empleado, $checada->hora) }} / {{ validar_salida($checada->num_empleado, $checada->hora) }}</td>

                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

@endsection