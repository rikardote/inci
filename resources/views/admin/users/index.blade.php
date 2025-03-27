@extends('layout.main')

@section('title', 'Usuarios')

@section('content')
<div class="supreme-container">
<a data-url="{{ route('users.create') }}" class="load-form-modal panelColorGreen" data-toggle ="modal" data-target='#form-modal'>
    <span class="fa-solid fa-user-plus fa-2x" aria-hidden='true'></span>
  </a>
    <table class="table table-striped">
        <thead>
            <th>Num Empleado</th>
            <th>Nombre</th>
            <th>Activo/Desactivo</th>
            <th>Accion</th>
        </thead>
        <tbody id="after_tr">
            @foreach($users as $user)
            <tr>
             <td>{{ $user->username }}</td>
             <td>{{ $user->name }}</td>
             <td>{{ $user->email }}</td>

             <td>
                <a data-url="{{ route('users.edit', $user->id) }}" class="load-form-modal panelColorGreen" data-toggle ="modal" data-target='#form-modal'>
                     <span class="fa-solid fa-pencil fa-2x" aria-hidden='true'></span>
                  </a>
                  <a data-url="{{ route('user.change', $user->id) }}" class="load-form-modal panelColorGreen" data-toggle ="modal" data-target='#form-modal'>
                     <span class="fa-solid fa-user-secret fa-2x" aria-hidden='true'></span>
                  </a>
                 <a href="{{route('user.destroy', $user->id) }}">
                    <span class="fa-solid fa-xmark fa-2x" aria-hidden="true"></span>
                </a>
             </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
    @include('modals.form-modal', ['title'=>'Agregar/Modificar Usuarios'])
@endsection
