@extends('layout.main')
@section('title', 'REPORTE DE FALTAS Y LICENCIAS S/G')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/flotante.css') }}">
@endsection

@section('content')

    <div class="social">
        <ul>
            <li>
                <a href="{{ route('reports.val_aguinaldopdf') }}" class="icon-pdf"><i class="fa fa-file-pdf-o fa-2x "></i></a>
            </li>

        </ul>
    </div>
    <h3><strong>IMPORTANTE! VALIDAR DOBLEMENTE LAS LICENCIAS SIN GOCE DE SUELDO PERIODO 01/01/2024 AL 31/12/2024</strong>
    </h3>
    <table class="table table-hover table-striped table-condensed" style="width:100%;" id="myTable">
        <thead>
            <th>Departamento</th>
            <th>Num Empleado</th>
            <th>Empleado</th>
            <th>Codigo</th>
            <th>Total Dias</th>
        </thead>
        <tbody>
            @foreach ($incidencias as $incidencia)
                <tr>
                    {{-- */ $depa = getDeparment($incidencia->deparment_id) /* --}}
                    <td>{{ $depa == '00104' ? '00105' : $depa }}</td>
                    <td align=center>{{ $incidencia->num_empleado }}</td>
                    <td>{{ $incidencia->father_lastname }} {{ $incidencia->mother_lastname }} {{ $incidencia->name }} </td>
                    <td align=center>{{ str_pad($incidencia->code, '2', '0', STR_PAD_LEFT) }}</td>
                    <td align="center">{{ $incidencia->total }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
