@extends('layout.main')
@section('title', $title)
@section('content')
<table class="table table-striped">
		<tr>
			{!! Form::open(['route' => ['reports.captura_diaria.post'], 'method' => 'POST']) !!}
			<td><strong>Seleccione fecha de consulta  </strong></td>
			<td>

				<div class="form-group col-md-12">
					 {!! Form::text('fecha_inicio', null, [
					    'class' => 'form-control',
					    'placeholder' => 'Fecha Inicial',
					    'required',
					    'id' => 'datepicker_inicial'
					  ]) !!}
				</div>

                <div class="form-group col-md-12">
					{!! Form::label('solo_medicos', 'Seleccionar solo medicos: ') !!}
					{!! Form::checkbox('solo_medicos') !!}

		        </div>


				{!! Form::submit('OK', ['class' => 'fa fa-search btn btn-success pull pull-right']) !!}


			</td>
			{!!Form::close()!!}
		</tr>
</table>
@endsection

@section('js')
	<script type="text/javascript">
        $("#datepicker_inicial").flatpickr({
            enableTime: false,
            allowInput: true,
            dateFormat: "d/m/Y",
            locale: 'es',
        });

	</script>
@endsection
