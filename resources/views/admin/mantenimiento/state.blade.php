@extends('layout.main')

@section('title', 'Mantenimiento')

@section('content')
    <div class="supreme-container">
    <a id="button" class="switch" onClick=check({{$mantenimiento->id}}) href="#"> {!! $mantenimiento->state ? '<input type="checkbox" checked><span class="slider round"></span>':'<input type="checkbox"> <span class="slider round"></span>' !!}	</a> 
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