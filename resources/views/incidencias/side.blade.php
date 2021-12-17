
		<table id="records_table" class="table table-hover table-condensed">
				<thead>
					<th>Qna</th>
					<th>Codigo</th>
					<th>Fecha Inicial</th>
					<th>Fecha Final</th>
					<th>Total</th>
					<th>Periodo</th>
                    <th>Eliminar</th>
                    <th>Detalle de captura</th>

				</thead>
				<tbody id="after_tr">
				
				@foreach($incidencias as $incidencia)
							<tr>
								<td>{{$incidencia->qna}}/{{$incidencia->qna_year}}</td>
								 <td>{{ str_pad($incidencia->code,'2','0',STR_PAD_LEFT ) }}</td>
								 <td> {{ fecha_dmy($incidencia->fecha_inicio) }}</td>
								 <td>{{ fecha_dmy($incidencia->fecha_final) }}</td>
								 <td>{{ $incidencia->total_dias }}</td>
								 @if(isset($incidencia->periodo))
										 <td align=center>{{ $incidencia->periodo }}/{{ $incidencia->periodo_year }}</td>
								 @else
								 			<td></td>
								 @endif
							 
								 @if(!$incidencia->capturada)
                                 <td>
								 	<button class="fa fa-trash fa-2x button button-info" value="{{$incidencia->token}}/{{$employee->num_empleado}}/{{$incidencia->id}}/destroy" OnClick='Eliminar(this);'></button>
								 </td>
                                 
								 @endif

                                 @if(isset($incidencia->capturado_por))
                                        <td>{{ $incidencia->capturado_por }} - {{ fecha_dmy($incidencia->fecha_capturado)}}</td>
                                @else
                                        <td></td>
                                @endif
							</tr>
				@endforeach
				</tbody>
		</table>

