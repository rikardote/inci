@extends('layout.main')

@section('title', 'Empleados')

@section('content')
		
@if(isset($employe))
	<div class="form-group">
	  
		  <div class="col-md-5">  
			  	<a data-url="{{ route('employees.create') }}" class="load-form-modal  panelColorGreen" data-toggle ="modal" data-target='#form-modal'>
			    	<span class="fa fa-plus-circle fa-x" aria-hidden='true'> Nuevo Empleado</span>
				</a> 
		       @include('admin.employees.search')  
                <table  class="table table-striped">
                    <thead>
                      <th>Fecha</th>
                      <th>Ultimas Checadas</th>
                    </thead>
                    <tbody>
                       @foreach ($checadas as $checada)
                         <tr>
                             <td>{{ fecha_dmy($checada->fecha) }}</td>
                             <td>{{ check_entrada($checada->fecha, $checada->num_empleado) }}</td>
                         </tr>
                       @endforeach
                    </tbody>
                </table>
		  </div>

		  <div class="col-md-7"> 
		    <div class="notice notice-info notice-sm">
	                <h4 align="center"><strong>{{$employe->num_empleado}} - {{$employe->name}} {{$employe->father_lastname}} {{$employe->mother_lastname}}</strong></h4>  
	         </div>
	         <div align="center">
              @if($employe->code == "00104")
                {{str_pad("00105", 5, '0', STR_PAD_LEFT)}} - {{$employe->description}}
              @else
                {{str_pad($employe->code, 5, '0', STR_PAD_LEFT)}} - {{$employe->description}}
              @endif
              </div>
              <div align="center">{{$employe->puesto}}</div>

              <div align="center" >{{$employe->horario}} | {{$employe->jornada}} </div> 
              <br>

              

              <div align="center">

              		<a data-url="{{ route('employees.edit', $employe->num_empleado) }}" class="load-form-modal  panelColorGreen" data-toggle ="modal" data-target='#form-modal'>
			 			<span class="fa fa-pencil-square-o fa-3x" aria-hidden='true'></span>
  					</a> 

	  				@if (\Auth::user()->admin())
				 	 <a href="#" onclick='deleteRow(this, <?=$employe->num_empleado?>)'> <span class="fa fa-trash fa-3x"></span></a>
				 	@endif

			  

                    
              </div>
		  </div>
	</div>

 
@endif

@include('modals.form-modal', ['title'=>'Agregar/Modificar Empleados'])
   
@endsection

@section('js')
<script>
        function deleteRow(r, num_empleado) {
            var i = r.parentNode.parentNode.rowIndex;
            //var route = "http://192.161.59.137/incidencias/employes/"+num_empleado+"/destroy";
            //var route = "http://192.168.1.95/employes/"+num_empleado+"/destroy";
            //var route = "http://incidencias.app/employes/"+num_empleado+"/destroy";
            var route = "http://incidencias.ddns.net/employes/"+num_empleado+"/destroy";
            var token = $("#token").val();
            swal({
		        title: "Seguro de borrar este Empleado?",   
				text: "De ser necesario debera solicitar la alta de nuevo",   
				type: "warning",   
				showCancelButton: true,   
				confirmButtonColor: "#DD6B55",   
				confirmButtonText: "Si, Eliminalo!",   
				cancelButtonText: "Cancelar",
				closeOnConfirm: false 
		       
		    }, function (isConfirm) {
		        if (!isConfirm) return;
       	    	$.ajax({
	                url: route,
	                headers: {'X-CSRF-TOKEN': token},
	                type: 'GET',
	                success: function (res) {
	               	 swal("Borrado!", "El empleado ha sido eliminado.", "success");
	               	 document.getElementById("myTable").deleteRow(i);
	            	},
	            	error: function (xhr, ajaxOptions, thrownError) {
	                	swal("Error al borrar!", "Intenta de nuevo", "error");
	            	}
	            });
        		
            }); 
        }
</script>
@endsection
