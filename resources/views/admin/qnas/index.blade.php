@extends('layout.main')

@section('title', 'Qnas')

@section('content')
<div class="supreme-container">
<a data-url="{{ route('qnas.create') }}" class="load-form-modal  panelColorGreen" data-toggle ="modal" data-target='#form-modal'>
    <span class="fa fa-plus-circle fa-2x" aria-hidden='true'></span>
  </a>
	<table class="table table-striped table-condensed">
		<thead>
			<th>Qna</th>
			<th>Año</th>
			<th>Descripcion</th>
			<th>Condicion</th>
	<!--		<th>Accion</th>  	-->
		</thead>
		<tbody id="after_tr">	
			@foreach($qnas as $qna)
				<tr>
				 <td>{{ $qna->qna }}</td>
				 <td>{{ $qna->year }}</td>
				 <td>{{ $qna->description }}</td>
				 <td>
				
					<a id="" class="switch" onClick=check({{$qna->id}}) href="#"> {!! $qna->active ? '<input type="checkbox" checked><span class="slider round"></span>':'<input type="checkbox"> <span class="slider round"></span>' !!}	</a> 
					
				  </td>

				
				<!--	 
					 <td>
					 	<a data-url="{{ route('qnas.edit', $qna->id) }}" class="load-form-modal  panelColorGreen" data-toggle ="modal" data-target='#form-modal'>
					 		<span class="fa fa-pencil-square-o fa-2x" aria-hidden='true'></span>
		  				</a> 
					 	<a href="{{route('qnas.destroy', $qna->id) }}"><span class="fa fa-times fa-2x" aria-hidden="true"></span></a>
					 </td>
					-->
			
				</tr>

			@endforeach
		</tbody>
	</table>
</div>
	
	@include('modals.form-modal', ['title'=>'Agregar/Modificar Departamentos'])
@endsection

@section('js')
<script>
function check(value) {
	var tablaDatos = $("#after_tr");
   	$.ajax({
   		"_token": $( this ).find( 'input[name=_token]' ).val(),
  		type: "GET",
  		dataType: "json",
  		url: 'qnas/'+value+'/condicion',
  		success: function(data){
  			$("#after_tr").empty();

			$(data).each(function(key, value){

				if (value.active == 1) {
					tablaDatos.append("<tr><td>"+value.qna+"</td><td>"+value.year+"</td><td>"+value.description+"</td><td><a id='button' class='switch' onClick=check("+value.id+") href='#'><input type='checkbox' checked><span class='slider round'></span></a>  </td></tr>"); 
				}else{
					tablaDatos.append("<tr><td>"+value.qna+"</td><td>"+value.year+"</td><td>"+value.description+"</td><td><a id='button' class='switch' onClick=check("+value.id+") href='#'><input type='checkbox'><span class='slider round'></span></a>  </td></tr>"); 
				};
                  
              });
				
			//console.log(data),
			//$('#ajax').load('qnas/'+value+'/condicion');
			//window.location.reload(true);
  		}
  	});
  	
}
</script>

@endsection