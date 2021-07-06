@extends('layout.main')

@section('title', $title)

@section('content')
	<p>Para iniciar la captura seleccione como desea realizarla: </p>
<table class="table">
	<tr>
	<thead>	
		<td align='center'>Opcion</td>
		<td align='center'>Qnas</td>
	</thead>	
	</tr>		
		<tr>
			<td><?php echo "Capturar por centro de trabajo";?></td>
			<td align='center'>
			@foreach ($qnas as $qna)
				<a class="btn btn-primary" href="{{route('admin.capturar.centro', [$qna->id])}}">{{$qna->qna}}/{{$qna->year}}</a>
				
			@endforeach
			</td>
		</tr>
</table>
@endsection