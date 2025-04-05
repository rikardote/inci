<div class="col-md-5">
    {!! Form::open(['route' => 'reportes.empleado.search', 'method' => 'post', 'id' => 'employeeSearchForm']) !!}
    <div class="form-group">
        <div class="input-group">
            <input id="employeeNumber" type="text" name="num" class="form-control"
                placeholder="Buscar por número de empleado">
            <span class="input-group-btn">
                <button class="btn btn-primary" type="submit">
                    <i class="fa fa-search"></i> Buscar
                </button>
            </span>
        </div>
    </div>
    {!! Form::close() !!}

    @if(isset($empleado))
    <div class="mt-4">
        <h5>Ingresar por rango de fechas</h5>

        {!! Form::open(['route' => ['reports.empleado.show', $empleado->num_empleado], 'method' => 'post']) !!}
        <div class="form-row">
            <div class="form-group col-md-6">
                {!! Form::text('fecha_inicio', null, [
                'id' => 'datepicker_inicial',
                'class' => 'form-control',
                'placeholder' => 'Fecha Inicial',
                'required',
                'autocomplete' => 'off'
                ]) !!}
            </div>
            <div class="form-group col-md-6">
                {!! Form::text('fecha_final', null, [
                'id' => 'datepicker_final',
                'class' => 'form-control',
                'placeholder' => 'Fecha Final',
                'required',
                'autocomplete' => 'off'
                ]) !!}
            </div>
        </div>

        <div style="margin-top: 10px; text-align: right;">
            {!! Form::button('<i class="fa fa-calendar-alt"></i> Consultar por rango', ['id' => 'registro', 'type' => 'submit',
            'class' => 'btn btn-success', 'style' => 'display: block; margin-bottom: 5px; margin-left: auto;']) !!}
            <a href="{{route('reporte.kardex.todo', [$empleado->num_empleado])}}" class="btn btn-danger"
                style="margin-left: auto;">
                <i class="fa fa-list"></i> Todas las incidencias
            </a>
        </div>
        {!! Form::close() !!}
    </div>
    @endif
</div>

<div class="col-md-7">
    @if(isset($empleado))
    <div class="notice notice-info notice-sm">
        <h4 class="text-center"><strong>{{$empleado->num_empleado}} - {{$empleado->name}} {{$empleado->father_lastname}}
                {{$empleado->mother_lastname}}</strong></h4>
    </div>
    @endif
    @if(isset($incidencias))
    <table class="table table-hover table-condensed">
        <thead>
            <th>Código</th>
            <th>Fecha Inicial</th>
            <th>Fecha Final</th>
            <th>Periodo</th>
            <th>Total</th>
            <th>Comentario</th>
            <th class="text-center">Capturado por</th>
        </thead>
        <tbody>
            @foreach($incidencias as $incidencia)
            <tr>
                <td>{{ str_pad($incidencia->code, '2', '0', STR_PAD_LEFT) }}</td>
                <td>{{ fecha_dmy($incidencia->fecha_inicio) }}</td>
                <td>{{ fecha_dmy($incidencia->fecha_final) }}</td>
                <td>{{ $incidencia->periodo ? $incidencia->periodo . '/' . $incidencia->periodo_year : '' }}</td>
                <td>{{ $incidencia->total_dias }}</td>
                <td>{{ $incidencia->otorgado ?? $incidencia->horas_otorgadas ?? '' }}</td>
                <td class="text-center">{{ $incidencia->capturado_por }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif
</div>

<br><br>

@if(isset($noencontrado))
<div class="alert alert-warning text-center">
    <h4>{!! $noencontrado !!}</h4>
</div>
@endif
