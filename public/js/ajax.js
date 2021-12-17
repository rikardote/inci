  $('#register').click(function(){
    var frmemployee = $('#empleado_id').val();
    var frmcodigo = $('#codigo').val();
    var frmfecha_inicio = $('#datepicker_inicial').val();
    var frmfecha_final = $('#datepicker_final').val();
    var frmperiodo = $('#periodo_id').val();
    var frmdiagnostico = $("#diagnostico").val();
    var frmdmedico_id = $("#medico_id").val();
    var frmexpedida = $("#datepicker_expedida").val();
    var frmnum_licencia = $("#num_licencia").val();
    var frmotorgado = "";
    var frmotorgado = $("#otorgado_id").val();
    var frmpendientes = $("#pendientes_com").val();
    var frmbecas_comments = $("#becas_comments").val();
    var frmqna_id = $("#qna_id").val();
    var frmsaltavalidacion = '';
    var frmsaltar_validacion_inca = '';
    var frmsaltar_validacion_lic = '';
    var frmsaltar_validacion_txt = '';
    var c=document.getElementById("saltar_validacion");
    var c2=document.getElementById("saltar_validacion_inca");
    var c3=document.getElementById("saltar_validacion_lic");
    var c4=document.getElementById("saltar_validacion_txt");
    if (c.checked) {
      frmsaltavalidacion = true;
    }else {
      frmsaltavalidacion = false;
    }
    if (c2.checked) {
      frmsaltar_validacion_inca = true;
    }else {
      frmsaltar_validacion_inca = false;
    }
    if (c3.checked) {
      frmsaltar_validacion_lic = true;
    }else {
      frmsaltar_validacion_lic = false;
    }
    if (c4.checked) {
      frmsaltar_validacion_txt = true;
    }else {
      frmsaltar_validacion_txt = false;
    }

    document.getElementById('register').style.visibility='hidden';    
    var token = $("#token").val();

    var tablaDatos = $("#after_tr");

    //var route = "http://incidencias.local/incidencias";
    //var route = "http://localhost/incidencias";
    //var route = "http://incidencias.slyip.com/incidencias/";
    //var route = "http://incidencias.ddns.net/incidencias/";

    var dataString = 'codigo='+frmcodigo+'&empleado_id='+frmemployee+'&datepicker_inicial='+frmfecha_inicio+'&datepicker_final='+frmfecha_final+'&periodo_id='+frmperiodo+'&medico_id='+frmdmedico_id+'&diagnostico='+frmdiagnostico+'&datepicker_expedida='+frmexpedida+'&num_licencia='+frmnum_licencia+'&otorgado='+frmotorgado+'&pendientes='+frmpendientes+'&saltar_validacion='+frmsaltavalidacion+'&saltar_validacion_inca='+frmsaltar_validacion_inca+'&saltar_validacion_lic='+frmsaltar_validacion_lic+'&saltar_validacion_txt='+frmsaltar_validacion_txt+'&qna_id='+frmqna_id+'&becas_comments='+frmbecas_comments; 
    $.ajax({
      url: route,
      headers: {'X-CSRF-TOKEN': token},
      type: 'POST',
      data: dataString,
           success: function(res) {
            console.log(res);
             moment.locale('es');
              $("#after_tr").empty();
              $(res).each(function(key, value){
                var finicio = moment(value.fecha_inicio);
                var ffinal = moment(value.fecha_final);

                if (value.periodo==null) {
                  tablaDatos.append("<tr><td>"+value.qna+"/"+value.qna_year+"</td><td>"+zPad(value.code, 2)+"</td><td>"+finicio.format("L")+"</td><td>"+ffinal.format("L")+"</td><td>"+value.total_dias+"</td><td></td><td>"+value.capturado_por+"</td><td><button class='fa fa-trash fa-2x' value='"+value.token+"/"+value.num_empleado+"/"+value.id+"/destroy'  OnClick='Eliminar(this);'></button></td></tr>");                  
                }
                else{
                  tablaDatos.append("<tr><td>"+value.qna+"/"+value.qna_year+"</td><td>"+zPad(value.code, 2)+"</td><td>"+finicio.format("L")+"</td><td>"+ffinal.format("L")+"</td><td>"+value.total_dias+"</td><td>"+value.periodo+"/"+value.periodo_year+"</td><td>"+value.capturado_por+"</td><td><button class='fa fa-trash fa-2x' value='"+value.token+"/"+value.num_empleado+"/"+value.id+"/destroy'  OnClick='Eliminar(this);'></button></td></tr>"); 
                };
              });
               //$('#periodo').hide();
               function toasterOptions() {
                      toastr.options = {
                          "closeButton": false,
                          "debug": false,
                          "newestOnTop": false,
                          "progressBar": true,
                          "positionClass": "toast-bottom-center",
                          "timeOut": "1500",
                      };
                };
               toasterOptions(); 
               toastr.success('Incidencia Registrada Correctamente');

               document.getElementById('register').style.visibility='visible';    


            
           }, 

             error: function (res) {
               swal({
                title: "Error!!... ",   
                text: res.responseText,   
                type: "error",   
                confirmButtonColor: "#DD6B55",   
                closeOnConfirm: false,
                timer: 3000
               });
               document.getElementById('register').style.visibility='visible';
   
              
              
             }
        });
  }); 

function zPad(n, l, r){
    return(a=String(n).match(/(^-?)(\d*)\.?(\d*)/))?a[1]+(Array(l).join(0)+a[2]).slice(-Math.max(l,a[2].length))+('undefined'!==typeof r?(0<r?'.':'')+(a[3]+Array(r+1).join(0)).slice(0,r):a[3]?'.'+a[3]:''):0
}

function Eliminar(btn){
  //var route = "http://incidencias.local/incidencias/"+btn.value+"";
  //var route = "http://incidencias.slyip.com/incidencias/"+btn.value+"";
  var route = "http://incidencias.ddns.net/incidencias/"+btn.value+"";
  //var route = "http://localhost/incidencias/"+btn.value+"";

  var token = $("#token").val();
  var tablaDatos = $("#after_tr");

  $.ajax({
    url: route,
    headers: {'X-CSRF-TOKEN': token},
    type: 'GET',
    dataType: 'json',

    success: function(res){
 
    moment.locale('es');
      $("#after_tr").empty();
       $(res).each(function(key, value){
          
        var finicio = moment(value.fecha_inicio);
        var ffinal = moment(value.fecha_final);
          if (value.periodo==null) {
             tablaDatos.append("<tr><td>"+value.qna+"/"+value.qna_year+"</td><td>"+zPad(value.code, 2)+"</td><td>"+finicio.format("L")+"</td><td>"+ffinal.format("L")+"</td><td>"+value.total_dias+"</td><td></td><td>"+value.capturado_por+"</td><td><button class='fa fa-trash fa-2x' value='"+value.token+"/"+value.num_empleado+"/"+value.id+"/destroy'  OnClick='Eliminar(this);'></button></td></tr>");                  
          }
          else{
             tablaDatos.append("<tr><td>"+value.qna+"/"+value.qna_year+"</td><td>"+zPad(value.code, 2)+"</td><td>"+finicio.format("L")+"</td><td>"+ffinal.format("L")+"</td><td>"+value.total_dias+"</td><td>"+value.periodo+"/"+value.periodo_year+"</td><td>"+value.capturado_por+"</td><td><button class='fa fa-trash fa-2x' value='"+value.token+"/"+value.num_empleado+"/"+value.id+"/destroy'  OnClick='Eliminar(this);'></button></td></tr>"); 
          };
      });
      function toasterOptions() {
        toastr.options = {
          "closeButton": false,
          "debug": false,
          "newestOnTop": false,
          "progressBar": true,
          "positionClass": "toast-bottom-center",
          "timeOut": "1500",
        };
      }; 
      toasterOptions();
      toastr.warning('Incidencia Eliminada Correctamente');
    }
  });
 
}




