@extends('layout.main')

@section('content')
    <div align="center" class="alert alert-warning">
            <strong><i class='fa fa-exclamation-triangle'></i> Atencion!</strong>
            <br>
            @if($qna->cierre)
            	<h3>La fecha de cierre de la Qna: {{$qna->qna}}/{{$qna->year}} es el dia: <strong>{{fecha_dmy($qna->cierre)}}</strong></h3>
            @else
				Fecha de Cierre aun no asignada
            @endif

    </div>  
    
@endsection
