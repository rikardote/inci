@extends('layout.main')

@section('title', 'Agregar Incidencia')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/flotante.css') }}">
@endsection


@section('content')
@include('incidencias.search')

<hr>
@if(isset($employee))

    <div class="social">
        <ul>
            <li><a data-url="{{ route('incidencias.create.comment', [$employee->emp_id]) }}" class="load-form-modal  panelColorGreen" data-toggle ="modal" data-target='#form-modal'><i class="fa fa-plus "></i> Comentario</a></li>
        </ul>
    </div>

  <div class="col-md-5">
  <div class="well well-sm well-deco">
	<strong>
   <h4>{{$employee->num_empleado}} 
<br>
        {{$employee->name}} {{$employee->father_lastname}} {{$employee->mother_lastname}}</h4> 
        @if($employee->code == "00104")
          {{str_pad("00105", 5, '0', STR_PAD_LEFT)}} - {{$employee->description}}
        @else
          {{str_pad($employee->code, 5, '0', STR_PAD_LEFT)}} - {{$employee->description}}
        @endif
        
    
        <p> {{$employee->puesto}}</p>
        <p> {{$employee->horario}} / {{$employee->jornada}}</p>
        <p> {{$employee->estancia ? 'ESTANCIA':''}}</p>
        <p> {{$employee->lactancia ? 'LACTANCIA':''}}</p>
        <p> {{$employee->comisionado ? 'COMISIONADO':''}}</p>
  </strong>
  @if(isset($comment))
  <div class="form-group">
    ** {!! Form::label('comment', $comment->comment) !!}
  </div>
@endif
   </div>
   <form method="POST" action="#" accept-charset="UTF-8" id="miFormulario"> 
    @include('incidencias._form')
    <div id="msj-success" class="alert alert-success alert-dismissible" role="alert" style="display:none">
      <strong>Incidencia Agregada Correctamente</strong>
    </div>
    <div id="msj-delete" class="alert alert-danger alert-dismissible" role="alert" style="display:none">
      <strong>Incidencia Eliminada Correctamente</strong>
    </div>

	{!! Form::close() !!}
</div>
<div id="showdata" class="col-md-7">
@include('incidencias.side')
  <?php //<iframe src='{{route('admin.incidencias.show',$employee->num_empleado) }}' id='calendarioFrame' class='myframe' height='500' width='600'></iframe> ?>

</div>
@endif
@include('modals.form-modal', ['title'=>'Agregar/Modificar Comentario'])
@endsection

@section('js')

<script src="{{ asset('js/ajax.js') }}"></script>

<script src="{{ asset('js/moment-with-locales.js') }}"></script>

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
$('#datepicker_expedida').datepicker({
    dateFormat: 'dd/mm/yy',
    changeMonth: true,
    changeYear: true,
    firstDay: 1,
});


</script>
<script>
  $(document).ready(function () {
    $('#periodo').hide();
    $('#medicos').hide();
    $('#otorgado').hide();
    $('#pendientes').hide();
    $('#div_saltar_validacion_inca').hide();
    $('#div_saltar_validacion_lic').hide();
    $('#div_saltar_validacion_txt').hide();

  });
</script>
<script>
  $('#codigo').on('change', function() {
      //alert( this.value ); // or $(this).val()
    if(this.value == 16 || this.value == 25 || this.value == 42) {
      $('#periodo').show();
      //
    } else {
      //
      $('#periodo').hide();
      document.getElementById("periodo_id").value = "";
    }
  });
</script>

<script>
  $('#codigo').on('change', function() {
      //alert( this.value ); // or $(this).val()
    if(this.value == 31 || this.value == 5 || this.value == 23) {
      $('#medicos').show();
      //
    } else {
      //
      $('#medicos').hide();
     /* document.getElementById("auto_medicos").value = "";
      document.getElementById("diagnostico").value = "";
      document.getElementById("num_licencia").value = "";
      document.getElementById("datepicker_expedida").value = "";
      */
    }
  });
</script>
<script>
  $('#codigo').on('change', function() {
      //alert( 'hola' ); // or $(this).val()
    if(this.value == 34) {
      
      $('#otorgado').show();
      //
    } else {
      //
      $('#otorgado').hide();
      document.getElementById("otorgado_id").value = null;
     
    }
  });
</script>
<script>
  $('#codigo').on('change', function() {
      //alert( this.value ); // or $(this).val()
    if(this.value == 35 || this.value == 36 || this.value == 37) {
      $('#pendientes').show();
      //
    } else {
      //
      $('#pendientes').hide();
      //document.getElementById("pendientes_com").value = "";
     
    }
  });
</script>

<script>
  $('#codigo').on('change', function() {
      //alert( this.value ); // or $(this).val()
    if(this.value == 31 || this.value == 23 || this.value == 5) {
      $('#div_saltar_validacion_inca').show();
      //
    } else {
      //
      $('#div_saltar_validacion_inca').hide();
      //document.getElementById("#div_saltar_validacion_inca").value = "";
     
    }
    if(this.value == 3 || this.value == 22 || this.value == 4) {
      $('#div_saltar_validacion_lic').show();
      //
    } else {
      //
      $('#div_saltar_validacion_lic').hide();
      //document.getElementById("#div_saltar_validacion_inca").value = "";
     
    }
  });
</script>

<script>
  $('#codigo').on('change', function() {
      //alert( this.value ); // or $(this).val()
    if(this.value == 38) {
      $('#div_saltar_validacion_txt').show();
      //
    } else {
      //
      $('#div_saltar_validacion_txt').hide();
      //document.getElementById("#div_saltar_validacion_inca").value = "";
     
    }
   
  });
</script>


<script>
  $(document).ready(function () {
    $('input:text').bind({
    });

    $("#auto_medicos").autocomplete({
      minLength:3,
     autoFocus: true,
    //source: 'http://192.161.59.137/incidencias/getdoctors',
    source: 'http://incidencias.slyip.com/getdoctors',
     // source: 'http://incidencias.app/getdoctors',


    });
});
</script>
<script type="text/javascript">
  $("#codigo").chosen();
  $("#medico_id").chosen();

</script>

<script>
 /*
  $('#saltar_validacion_inca').on('change', function() {
     //alert( 'HOLA' ); // or $(this).val()

    if(document.getElementById('saltar_validacion_inca').checked) {
       document.getElementById('auto_medicos').disabled = true;
       document.getElementById('diag').disabled = true;
       document.getElementById('datepicker_expedida').disabled = true;
       document.getElementById('folio_inc').disabled = true;
    } else {
       document.getElementById('auto_medicos').disabled = false;
       document.getElementById('diag').disabled = false;
       document.getElementById('datepicker_expedida').disabled = false;
       document.getElementById('folio_inc').disabled = false;
    }
  
  });
  */
</script>

@endsection
