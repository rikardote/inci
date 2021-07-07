@extends('layout.main')

@section('title', 'Empleados')

@section('content')
	<div class="supreme-container">
		  	<a data-url="{{ route('employees.create') }}" class="load-form-modal  panelColorGreen" data-toggle ="modal" data-target='#form-modal'>
		    	<span class="fa fa-plus-circle fa-x" aria-hidden='true'> Nuevo Empleado</span>
		  	</a> 
		@include('admin.employees.search')
    </div>

	@include('modals.form-modal', ['title'=>'Agregar/Modificar Empleados'])
    @include('modals.confirmation_modal', ['title'=>'Confirmation Modal'])
@endsection

@section('js')
<script>
        function deleteRow(r, num_empleado) {
            var i = r.parentNode.parentNode.rowIndex;
            //var route = "http://192.161.59.137/incidencias/employees/"+num_empleado+"/destroy";
            //var route = "http://192.168.1.95/employees/"+num_empleado+"/destroy;
            //var route = "http://incidencias.app/employees/"+num_empleado+"/destroy";
            var route = "http://incidencias.ddns.net/employees/"+num_empleado+"/destroy";
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
