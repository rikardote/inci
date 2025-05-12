@extends('layout.main')

@section('content')
    <h1>Respaldo de Base de Datos</h1>

    <form method="GET" action="{{ route('mantenimiento.respaldo') }}">
        <!-- <button type="submit" name="action" value="create" class="btn btn-primary">Crear Respaldo</button> -->
    </form>

    <h2>Archivos de Respaldo</h2>
    <ul>
        @forelse ($backups as $backup)
            <li>
                <a href="{{ route('mantenimiento.descargar', ['file' => $backup]) }}" download>{{ $backup }}</a>
            </li>
        @empty
            <li>No hay respaldos disponibles.</li>
        @endforelse
    </ul>
@endsection
