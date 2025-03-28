@extends('layout.main')

@section('title', 'Registrar Incidencia')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/flotante.css') }}">
@endsection


@section('content')
<div class="form-group">
   <div class="col-md-5">
       @include('incidencias.search')
       @include('incidencias.captura_form')
  </div>

  <div class="col-md-7">
       @include('incidencias.datos_empleado')
       @include('incidencias.side')
  </div>
 </div>

@include('modals.form-modal', ['title'=>'Agregar/Modificar Comentario'])

@endsection

@section('js')

<script src="{{ asset('js/ajax.js') }}"></script>

<script src="{{ asset('js/moment-with-locales.js') }}"></script>


<script type="text/javascript">
/*
  $(function() {
    $( "#datepicker_inicial" ).datepicker();
  });
*/
  </script>
<script>

//$.datepicker.setDefaults($.datepicker.regional['es-MX']);
/*
$('#datepicker_inicial').datepicker({
    dateFormat: 'dd/mm/yy',
    //multidate: true,
    changeMonth: true,
    changeYear: true,
    firstDay: 1,
    onClose: function () {
        $('#datepicker_final').val(this.value);
    }
});
*/
$("#datepicker_inicial").flatpickr({
    enableTime: false,
    allowInput: true,
    dateFormat: "d/m/Y",
    locale: 'es',
    onChange : function() {
      document.getElementById("datepicker_final").value = document.getElementById("datepicker_inicial").value;
    }
});

$("#datepicker_final").flatpickr({
    enableTime: false,
    allowInput: true,
    dateFormat: "d/m/Y",
    locale: 'es',
});

$('#datepicker_expedida').flatpickr({
    dateFormat: 'd/m/Y',
    allowInput: true,
    locale: 'es',
    enableTime: false,
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
    $('#ingresar_fechas').hide();
    $('#datepicker_inicial').hide();
    $('#datepicker_final').hide();
    $('#register').hide();
    $('#becas').hide();
    $('#qnas').hide();
    $('#coberturaTXT').hide();
    $('#horas_otorgadas_div').hide();

  });
</script>
<script>
  $('#codigo').on('change', function() {

      $('#ingresar_fechas').show();
      $('#datepicker_inicial').show();
      $('#datepicker_final').show();
      $('#register').show();

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
       // alert( this.value ); // or $(this).val()
      if(this.value == 97) {

        $('#horas_otorgadas_div').show();
        //
      } else {
        //
        $('#horas_otorgadas_div').hide();
        document.getElementById("horas_otorgadas").value = null;

      }
    });
  </script>

<script>
    $('#codigo').on('change', function() {
        //alert( 'hola' ); // or $(this).val()
      if(this.value == 38) {

        $('#coberturaTXT').show();
        //
      } else {
        //
        $('#coberturaTXT').hide();
        document.getElementById("cobertura_txt").value = null;
        document.getElementById("autoriza_txt").value = null;

      }
    });
  </script>

<script>
    $('#codigo').on('change', function() {
        //alert( 'hola' ); // or $(this).val()
      if(this.value == 24) {

        $('#becas').show();
        //
      } else {
        //
        $('#becas').hide();
        document.getElementById("becas").value = null;

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
    if(this.value == 93) {
      $('#datepicker_inicial').hide();
      $('#datepicker_final').hide();
      $('#ingresar_fechas').hide();
      $('#qnas').show();
    }
    else {
      $('#qnas').hide();
      document.getElementById("qna_id").value = 0;
    }

  });
</script>


<script>
  $(document).ready(function () {
    var url = document.location.origin;

    $('input:text').bind({
    });

    $("#auto_medicos").autocomplete({
      minLength:3,
     autoFocus: true,
     source: url+"/getdoctors"
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
<script>
    $(document).ready(function() {
        // Verificar que exista un empleado con ID válido
        @if(isset($employee->num_empleado))

            // Variable para almacenar el último valor recibido
            var ultimoValorLicencias = -1;

            // Función para cargar licencias médicas
            function cargarLicenciasMedicas() {
                $.ajax({
                    url: "{{ route('empleado.licencias-medicas', $employee->num_empleado) }}",
                    type: "GET",
                    dataType: "json",
                    success: function(response) {
                        // Solo actualizar si el valor ha cambiado
                        if (response.licenciasMedicas !== ultimoValorLicencias) {
                            ultimoValorLicencias = response.licenciasMedicas;

                            if (response.licenciasMedicas > 0) {
                                var html = '<span class="text-danger blink" style="color: #ff0000; font-weight: bold; animation: blink 1s linear infinite;">';
                                html += 'EL EMPLEADO TIENE ' + response.licenciasMedicas + ' DIAS DE LICENCIAS MEDICAS, VALIDAR EXCESOS.';
                                html += '</span>';
                                html += '<style>';
                                html += '@keyframes blink { 0% { opacity: 1; } 50% { opacity: 0.5; } 100% { opacity: 1; } }';
                                html += '.blink { animation: blink 1s linear infinite; }';
                                html += '</style>';

                                $('#licencias-medicas-container').html(html);
                            } else {
                                // Limpiar el contenedor si no hay licencias médicas
                                $('#licencias-medicas-container').html('');
                            }

                            console.log('Licencias médicas actualizadas: ' + response.licenciasMedicas);
                        }
                    },
                    error: function(xhr) {
                        console.error('Error al cargar licencias médicas:', xhr.responseText);
                    }
                });
            }

            // Cargar datos inmediatamente al cargar la página
            cargarLicenciasMedicas();
        @endif
        // Escuchar el evento licenciaAgregada
        $(document).on('licenciaAgregada', function() {
           cargarLicenciasMedicas();
        });
        // Escuchar el evento licenciaEliminada
        $(document).on('licenciaEliminada', function() {
           cargarLicenciasMedicas();
        });
    });
</script>


@endsection
