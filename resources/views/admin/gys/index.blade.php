@extends('layout.main')

@section('title', 'Importar Archivo CSV con datos de los suplentes')

@section('content')
@include('admin.gys.partials._mensajes')
@include('admin.gys.partials._form_import')
@include('admin.gys.partials._dashboard')
@include('admin.gys.partials._reporte_quincena')
@include('admin.gys.partials._reporte_mensual')

@endsection
