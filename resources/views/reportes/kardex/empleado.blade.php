@extends('layout.main')

@section('title', $title)
 
@section('css')
    <link rel="stylesheet" href="{{ asset('css/flotante.css') }}">
@endsection
 
@section('content')
   
    @include('reportes.kardex.searchEmpleado')
     @if(isset($num_empleado))
    <div class="social">
        <ul>
            <li><a href="{{route('reporte.kardex.pdf', [$num_empleado, $fecha_inicio, $fecha_final])}}" class="icon-pdf"><i class="fa fa-file-pdf-o fa-2x "></i></a></li>
        </ul>
    </div>
    @endif
    
@endsection

@section('js')

<script src="{{ asset('js/ajax.js') }}"></script>

<script type="text/javascript">
  $(function() {
    $( "#datepicker_inicial" ).datepicker();
  });
  </script>
<script>
$.datepicker.setDefaults($.datepicker.regional['es-MX']);
$('#datepicker_inicial').datepicker({
    dateFormat: 'dd/mm/yy',
    changeMonth: true,
    changeYear: true,
    firstDay: 1,
    onClose: function () {
        $('#datepicker_final').val(this.value);
    }
});
$('#datepicker_final').datepicker({
    dateFormat: 'dd/mm/yy',
    changeMonth: true,
    changeYear: true,
    firstDay: 1
});
</script> 
@endsection