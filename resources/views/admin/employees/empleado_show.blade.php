<!-- filepath: /d:/swtools/laragon/www/incidencias/resources/views/admin/employees/empleado_show.blade.php -->
@extends('layout.main')

@section('title', 'Empleados')

@section('content')

@if (isset($employe))
<div class="form-group">

    <div class="col-md-5">
        <a data-url="{{ route('employees.create') }}" class="load-form-modal panelColorGreen" data-toggle="modal"
            data-target='#form-modal'>
            <span class="fas fa-circle-plus fa-x" aria-hidden='true'> Nuevo Empleado</span>
        </a>
        @include('admin.employees.search')
        @include('admin.employees.partials._checadas')
    </div>

    <div class="col-md-7">
        <div class="notice notice-info notice-sm">
            <h4 align="center"><strong>{{ $employe->num_empleado }} - {{ $employe->name }}
                    {{ $employe->father_lastname }} {{ $employe->mother_lastname }}</strong></h4>
        </div>
        <div align="center">
            @if ($employe->code == '00104')
            {{ str_pad('00105', 5, '0', STR_PAD_LEFT) }} - {{ $employe->description }}
            @else
            {{ str_pad($employe->code, 5, '0', STR_PAD_LEFT) }} - {{ $employe->description }}
            @endif
        </div>
        <div align="center">{{ $employe->puesto }}</div>

        <div align="center">{{ $employe->horario }} | {{ $employe->jornada }} </div>
        <br>

        <div align="center">
            <a data-url="{{ route('employees.edit', $employe->num_empleado) }}" class="load-form-modal panelColorGreen"
                data-toggle="modal" data-target='#form-modal'>
                <span class="fas fa-pen-to-square fa-3x" aria-hidden='true'></span>
            </a>

            @if (\Auth::user()->admin())
            <a href="#" onclick='deleteRow(this, <?= $employe->num_empleado ?>)'>
                <span class="fas fa-trash-can fa-3x"></span>
            </a>
            @endif
        </div>
    </div>
</div>
@endif

@include('modals.form-modal', ['title' => 'Agregar/Modificar Empleados'])

@endsection

@section('js')
<script>
    function deleteRow(r, num_empleado) {
            var i = r.parentNode.parentNode.rowIndex;
            var route = "{{ url('/employees') }}/" + num_empleado + "/destroy";
            var token = $("#token").val();

            swal({
                title: "¿Seguro de borrar este Empleado?",
                text: "De ser necesario deberá solicitar el alta de nuevo",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Sí, ¡Elimínalo!",
                cancelButtonText: "Cancelar",
                closeOnConfirm: false
            }, function(isConfirm) {
                if (!isConfirm) return;
                $.ajax({
                    url: route,
                    headers: {'X-CSRF-TOKEN': token},
                    type: 'GET',
                    success: function(res) {
                        swal("¡Borrado!", "El empleado ha sido eliminado.", "success");
                        document.getElementById("myTable").deleteRow(i);
                    },
                    error: function(xhr, ajaxOptions, thrownError) {
                        swal("¡Error al borrar!", "Intenta de nuevo", "error");
                    }
                });
            });
        }
</script>
@endsection
