@extends('layout.main')

@section('title', 'Importar Archivo CSV con datos de los suplentes')

@section('css')
<style>
    .btn-xs {
        padding: 0.25rem 0.5rem;
        font-size: 0.75rem;
        line-height: 1.5;
        border-radius: 0.2rem;
    }
</style>
@endsection

@section('content')
    @include('admin.gys.partials._mensajes')
    @include('admin.gys.partials._form_import')
    @include('admin.gys.partials._dashboard')
    @include('admin.gys.partials._reporte_quincena')
    @include('admin.gys.partials._reporte_mensual')



@endsection
