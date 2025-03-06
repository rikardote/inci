@extends('layout.main')

@section('title', 'Reporte Biometrico por Centro de Trabajo')

@section('content')

@if(session('error'))
<div class="alert alert-danger">
    {{ session('error') }}
</div>
@endif

{!! Form::open(['route' => 'biometrico.registros', 'method' => 'GET', 'class' => 'form-horizontal']) !!}

<div class="row">
    <!-- Período y Centro de Trabajo -->
    <div class="col-md-12">
        <div class="periodo-container">
            <div class="well">
                <div class="row">
                    <!-- Centro de Trabajo -->
                    <div class="col-md-12 mb-4">
                        <div class="form-group">
                            <label class="control-label">
                                <i class="fa fa-building"></i> Centro de Trabajo
                            </label>
                            {!! Form::select('centro', $centros, $centro_seleccionado, [
                            'class' => 'form-control select2',
                            'placeholder' => 'Seleccione un centro de trabajo...',
                            'required' => true
                            ]) !!}
                        </div>
                    </div>

                    <!-- Año -->
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="control-label">
                                <i class="fa fa-calendar-o"></i> Año
                            </label>
                            {!! Form::select('año', array_combine($años, $años), $año_seleccionado, [
                            'class' => 'form-control select2',
                            'id' => 'año'
                            ]) !!}
                        </div>
                    </div>

                    <!-- Quincena -->
                    <div class="col-md-8">
                        <div class="form-group">
                            <label class="control-label">
                                <i class="fa fa-calendar-check-o"></i> Quincena
                            </label>
                            {!! Form::select('quincena',
                            collect($quincenas)->pluck('label', 'value')->toArray(),
                            $quincena_seleccionada,
                            [
                            'class' => 'form-control select2',
                            'id' => 'quincena'
                            ]
                            ) !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Botón de búsqueda -->
    <div class="col-md-12">
        <div class="button-container text-right">
            <button type="submit" class="btn btn-primary">
                <i class="fa fa-search"></i> Consultar Registros
            </button>
        </div>
    </div>
</div>

{!! Form::close() !!}

{{-- Verificar registros usando método count() --}}
@if(isset($registros) && count($registros) > 0)
@include('biometrico.partials._tabla_registros')
@elseif($centro_seleccionado)
<div class="alert alert-info">
    <i class="fa fa-info-circle"></i> No se encontraron registros para el período seleccionado.
</div>
@endif


@endsection

@section('css')
<link rel="stylesheet" href="{{ asset('plugins/select2/select2.min.css') }}">
<link rel="stylesheet" href="{{ asset('css/biometrico-registros.css') }}">
@endsection

@section('js')
<script src="{{ asset('plugins/select2/select2.min.js') }}"></script>
<script>
    $(document).ready(function() {
        $('.select2').select2({
            width: '100%',
            language: 'es'
        });

        $('#quincena').change(function() {
            if ($('select[name="centro"]').val()) {
                $(this).closest('form').submit();
            }
        });
    });
</script>
@endsection
