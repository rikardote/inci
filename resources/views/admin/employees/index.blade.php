<!-- filepath: /d:/swtools/laragon/www/incidencias/resources/views/admin/employees/index.blade.php -->
@extends('layout.main')

@section('title', 'Empleados')

@section('content')
<div class="supreme-container">
    <a data-url="{{ route('employees.create') }}" class="load-form-modal panelColorGreen" data-toggle="modal"
        data-target='#form-modal'>
        <span class="fas fa-circle-plus fa-lg" aria-hidden='true'> Nuevo Empleado</span>
    </a>
    @include('admin.employees.search')
</div>

@include('modals.form-modal', ['title'=>'Agregar/Modificar Empleados'])
@include('modals.confirmation_modal', ['title'=>'Confirmación'])
@endsection

@section('js')
<script>
    function deleteRow(r, num_empleado) {
        var i = r.parentNode.parentNode.rowIndex;
        var route = "{{ url('/employees') }}/" + num_empleado + "/destroy";
        var token = $("#token").val();
        swal({
            title: "¿Seguro de borrar este empleado?",
            text: "De ser necesario deberá solicitar el alta de nuevo",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Sí, ¡Elimínalo!",
            cancelButtonText: "Cancelar",
            closeOnConfirm: false
        }, function (isConfirm) {
            if (!isConfirm) return;
            $.ajax({
                url: route,
                headers: {'X-CSRF-TOKEN': token},
                type: 'GET',
                success: function (res) {
                    swal("¡Borrado!", "El empleado ha sido eliminado.", "success");
                    document.getElementById("myTable").deleteRow(i);
                },
                error: function (xhr, ajaxOptions, thrownError) {
                    swal("¡Error al borrar!", "Intenta de nuevo", "error");
                }
            });
        });
    }
</script>
@endsection
