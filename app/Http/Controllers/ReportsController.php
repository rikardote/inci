<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Incidencia;
use App\Codigo_De_Incidencia;
use App\Employe;
use App\Deparment;
use App\Qna;
use App\Jornada;
use App\Horario;
use \mPDF;
use Carbon\Carbon;
use Laracasts\Flash\Flash;

class ReportsController extends Controller
{

   public function general()
   {
   	$dptos = \Auth::user()->centros->pluck('id')->toArray();
		$dptos = Deparment::whereIn('deparments.id', $dptos)->get();
        $default_year = Carbon::now()->format('Y');
        $qnas = ['1' => '01 - 1RA ENERO','2' => '02 - 2DA ENERO','3' => '03 - 1RA FEBRERO','4' => '04 -	2DA FEBRERO','5' => '05 - 1RA MARZO','6' => '06 - 2DA MARZO','7' => '07 - 1RA ABRIL','8' => '08 - 2DA ABRIL','9' => '09 - 1RA MAYO','10' => '10 - 2DA MAYO','11' => '11 - 1RA JUNIO','12' => '12 - 2DA JUNIO','13' => '13 - 1RA JULIO','14' => '14 - 2DA JULIO'
            ,'15' => '15 - 1RA AGOSTO','16' => '16 - 2DA AGOSTO','17' => '17 - 1RA SEPTIEMBRE','18' => '18 - 2DA SEPTIEMBRE','19' => '19 - 1RA OCTUBRE','20' => '20 - 2DA OCTUBRE','21' => '21 - 1RA NOVIEMBRE','22' => '22 - 2DA NOVIEMBRE','23' => '23 - 1RA DICIEMBRE','24' => '24 - 2DA DICIEMBRE'
        ];
        $years = ['2023' => '2023','2022' => '2022','2021' => '2021','2020' => '2020','2019' => '2019','2018' => '2018'];


      $title = "Reporte General";
      return view('reportes.general')
         ->with('dptos', $dptos)
         ->with('qnas', $qnas)
         ->with('years', $years)
         ->with('default_year', $default_year)
         ->with('title', $title);
   }
   public function general_show(Request $request, $dpto)
   {

   	$dpto = Deparment::where('code', '=', $dpto)->first();
    $qna = Qna::where('qna', $request->qna)->where('year',$request->year)->first();
   	$incidencias = Incidencia::getIncidenciasCentro($qna->id, $dpto->id);
    $title = "Reporte General Qna: " . $qna->qna . "/" . $qna->year . " - " . $qna->description;

   	return view('reportes.show')
         ->with('incidencias', $incidencias)
         ->with('title', $title)
         ->with('qna',$qna)
         ->with('dpto',$dpto);

   }
   public function empleado()
   {
    $title = "Reporte General por empleado";
   	return view('reportes.kardex.empleado')->with('title', $title);
   }
   public function empleado_show(Request $request, $num_empleado)
   {

      $fecha_inicio = fecha_ymd($request->fecha_inicio);
      $fecha_final =  fecha_ymd($request->fecha_final);
      $title = "Reporte General del : " . $request->fecha_inicio . " Al " . $request->fecha_final;
      $incidencias = Incidencia::getIncidenciasEmpleado($fecha_inicio, $fecha_final, $num_empleado);
      $empleado = Employe::where('num_empleado', $num_empleado)->first();


      return view('reportes.kardex.empleado')
         ->with('incidencias', $incidencias)
         ->with('title', $title)
         ->with('num_empleado', $num_empleado)
         ->with('empleado', $empleado)
         ->with('fecha_inicio', $fecha_inicio)
         ->with('fecha_final', $fecha_final);
   }
   public function reporte_kardex_todo($num_empleado)
   {

      $fecha_inicio = "2016-01-01";
      $fecha_final =  Carbon::today()->toDateString();
      $title = "Reporte General del : " . $fecha_inicio . " Al " . $fecha_final;
      $empleado = Employe::where('num_empleado', $num_empleado)->first();
      $incidencias = Incidencia::getIncidenciasEmpleadoKardex($num_empleado);

      return view('reportes.kardex.empleado')
         ->with('incidencias', $incidencias)
         ->with('title', $title)
         ->with('num_empleado', $num_empleado)
         ->with('empleado', $empleado)
         ->with('fecha_inicio', $fecha_inicio)
         ->with('fecha_final', $fecha_final);
   }
   public function empleado_qna(Request $request, $num_empleado)
   {
      $qna_id = $request->qna_id;

      $qna = Qna::find($qna_id);

      $title = "Reporte General Qna: " . $qna->qna . "/" . $qna->year . " - " . $qna->description;

      $incidencias = Incidencia::getIncidenciasQna($num_empleado, $qna_id);

      return view('reportes.empleado')
         ->with('incidencias', $incidencias)
         ->with('title', $title);
   }
   public function diario()
   {
      $title = "Reporte Diario de Incidencias";
      return view('reportes.diario')->with('title', $title);
   }
   public function diario_post(Request $request)
   {
      $dptos = \Auth::user()->centros->pluck('id')->toArray();
      $fecha = $request->fecha_inicio;
      if ($request->solo_medicos == true) {
        $medicosIds = ['24','25','55','56','57','58','59','60','61','62','63','64','65','66','67','68','69'];
        $incidencias = Incidencia::GetIncidenciasPorDia_Solo_Medicos($dptos, $medicosIds, fecha_ymd($fecha));
      }
      else {
        $incidencias = Incidencia::GetIncidenciasPorDia($dptos, fecha_ymd($fecha));
        //dd($incidencias);
      }

      if ($incidencias == null) {
        Flash::warning('No hay datos para esta fecha: '.fecha_dmy($fecha));
        return redirect()->route('reports.diario');
      }
      else {
        $mpdf = new mPDF('', 'Letter', 0, '', 12.7, 12.7, 14, 12.7, 8, 8);
          $header = \View('reportes.header_diario', compact('fecha'))->render();
          $mpdf->SetFooter('Generado el: {DATE j-m-Y} |Hoja {PAGENO} de {nb}');
          $html =  \View('reportes.reporte_diario_generado', compact('incidencias'))->render();
          $pdfFilePath = 'REPORTE_DE_INCIDENCIAS_DIARIO_DEL_'.Carbon::now().'.pdf';
          $mpdf->setAutoTopMargin = 'stretch';
          $mpdf->setAutoBottomMargin = 'stretch';
          $mpdf->setHTMLHeader($header);
          $mpdf->SetDisplayMode('fullpage');
          $mpdf->WriteHTML($html);

          $mpdf->Output($pdfFilePath, "D");
      }
   }
   public function sinDerecho()
   {
      $dptos = \Auth::user()->centros->pluck('id')->toArray();
      $dptos = Deparment::whereIn('deparments.id', $dptos)->orderBy('code')->get();
      $now = Carbon::now();
      $month = $now->month-1;

      $years = array();
      for($i = $now->year;$i>= 2017;$i--) {
        $years["$i"] = $i;
      }

      $title = "Reporte sin derecho a nota buena por desempeño";
      return view('reportes.sinderecho')->with('title', $title)->with('dptos', $dptos)->with('month', $month)->with('years', $years);
   }
   public function getsinDerecho(Request $request, $dpto)
   {

      $dt = Carbon::create($request->year, $request->month, 1, 12, 0, 0);
      $fecha_inicio = $dt->startOfMonth();

      $dt2 = Carbon::create($request->year, $request->month, 1, 12, 0, 0);
      $fecha_final = $dt2->endOfMonth();

      $lic = ['40','41','46','47','53','54','55'];
      $inc = ['01','02','03','04','08','09','10','18','19','25','78','86','100','30','31'];

      $incidencias_inc = Incidencia::noDerecho_inc($dpto, $fecha_inicio, $fecha_final, $inc);
      $incidencias_lic = Incidencia::noDerecho_lic($dpto, $fecha_inicio, $fecha_final, $lic);

      $incidencias = array_merge($incidencias_inc, $incidencias_lic);

      $incidencias =collect($incidencias)->unique('num_empleado')->sortBy('num_empleado')->toArray();

      $title = "Reporte sin derecho a nota buena por desempeño: Del ".fecha_dmy($fecha_inicio)." Al ".fecha_dmy($fecha_final)." - ".$dpto;

    return view('reportes.sinderecho-show')
      ->with('title', $title)
      ->with('incidencias', $incidencias)
      ->with('fecha_inicio', $fecha_inicio)
      ->with('fecha_final', $fecha_final)
      ->with('dpto', $dpto);
   }
   public function getsinDerechoPDF($dpto,$fecha_inicio,$fecha_final)
    {
        $lic = ['40','41','46','47','53','54','55'];
        $inc = ['01','02','03','04','08','09','10','18','19','25','78','86','100','30','31'];

        $incidencias_inc = Incidencia::noDerecho_inc($dpto, $fecha_inicio, $fecha_final, $inc);
        $incidencias_lic = Incidencia::noDerecho_lic($dpto, $fecha_inicio, $fecha_final, $lic);

        $incidencias = array_merge($incidencias_inc, $incidencias_lic);

        $incidencias =collect($incidencias)->unique('num_empleado')->sortBy('num_empleado')->toArray();

        $dpto = Deparment::where('code', '=', $dpto)->first();

        $mpdf = new mPDF('', 'Letter-L');
        $mpdf->setAutoTopMargin = 'stretch';
        $mpdf->setAutoBottomMargin = 'stretch';
        $header = \View('reportes.header-sinderecho', compact('dpto', 'fecha_inicio'))->render();
        $mpdf->SetFooter('FIRMA CENTRO DE TRABAJO|VO.BO. DELEGADO SINDICAL<br>'.$dpto->description.'|Hoja {PAGENO} de {nb}');
        $html =  \View('reportes.sinderechopdf', compact('incidencias'))->render();
        $pdfFilePath = $dpto->description.'.SIN-DERECHO.pdf';
        $mpdf->setAutoTopMargin = 'stretch';
        $mpdf->setAutoBottomMargin = 'stretch';
        $mpdf->setHTMLHeader($header);
        $mpdf->SetDisplayMode('fullpage');
        $mpdf->WriteHTML($html);

        $mpdf->Output($pdfFilePath, "D");
    }
   public function reporte_pdf($qna_id, $dpto)
    {
        $dpto = Deparment::where('code', '=', $dpto)->first();
        $incidencias = Incidencia::getIncidenciasCentroPDF($qna_id, $dpto->id);

        $qna = Qna::find($qna_id);
        $mpdf = new Mpdf('', 'Letter', 0, '', 12.7, 12.7, 14, 12.7, 8, 8);
        $header = \View('reportes.header', compact('dpto', 'qna'))->render();
        $mpdf->SetFooter($dpto->description.'|Generado el: {DATE j-m-Y} |Hoja {PAGENO} de {nb}');
        $html =  \View('reportes.reportegenerado', compact('incidencias'))->render();
        $pdfFilePath = $qna->qna.'-'.$qna->year.'-'.$dpto->description.'.pdf';
        $mpdf->setAutoTopMargin = 'stretch';
        $mpdf->setAutoBottomMargin = 'stretch';
        $mpdf->setHTMLHeader($header);
        $mpdf->SetDisplayMode('fullpage');
        $mpdf->WriteHTML($html);

        $mpdf->Output($pdfFilePath, "D");
    }
    public function reporte_pdf_diario($qna_id, $dpto)
    {
        $dpto = Deparment::where('code', '=', $dpto)->first();
        $incidencias = Incidencia::getIncidenciasCentroPDF_diario($qna_id, $dpto->id);


        $qna = Qna::find($qna_id);
        $mpdf = new mPDF('', 'Letter', 0, '', 12.7, 12.7, 14, 12.7, 8, 8);
        $header = \View('reportes.header', compact('dpto', 'qna'))->render();
        $mpdf->SetFooter($dpto->description.'|Generado el: {DATE j-m-Y} |Hoja {PAGENO} de {nb}');
        $html =  \View('reportes.reportegenerado', compact('incidencias'))->render();
        $pdfFilePath = $qna->qna.'-'.$qna->year.'-'.$dpto->description.'.pdf';
        $mpdf->setAutoTopMargin = 'stretch';
        $mpdf->setAutoBottomMargin = 'stretch';
        $mpdf->setHTMLHeader($header);
        $mpdf->SetDisplayMode('fullpage');
        $mpdf->WriteHTML($html);

        $mpdf->Output($pdfFilePath, "D");
    }
    public function show_incidenciasEmpleados($fecha_inicio,$fecha_final,$num_empleado)
    {
      $empleado = Employe::where('num_empleado', '=', $num_empleado)->first();
      $incidencias = Incidencia::getIncidenciasEmpleado($fecha_inicio,$fecha_final,$num_empleado);
      $inc2  = ['01','02','03','04','08','09','10','18','19','25','78','86','100','30','40','41','46','47','53','54','55'];
      return view('reportes.showIncidencias')->with('empleado', $empleado)->with('incidencias',$incidencias)->with('inc2', $inc2);
    }
    public function licencias()
    {
      return view('reportes.licencias');
    }
    public function postLicencias(Request $request)
    {
      $fecha_inicio = fecha_ymd($request->fecha_inicio);
      $fecha_final = fecha_ymd($request->fecha_final);

      $dptos = \Auth::user()->centros->pluck('id')->toArray();
      $incidencias = Incidencia::getLicencias($fecha_inicio, $fecha_final, $dptos);

      return view('reportes.licencia_show')
        ->with('incidencias', $incidencias)
        ->with('fecha_inicio', $fecha_inicio)
        ->with('fecha_final', $fecha_final);
    }
    public function reporte_licencias_pdf($fecha_inicio, $fecha_final)
    {
       $dptos = \Auth::user()->centros->pluck('id')->toArray();
       $incidencias = Incidencia::getLicencias($fecha_inicio, $fecha_final, $dptos);

        //$qna = Qna::find($qna_id);
        $mpdf = new mPDF('', 'Letter-L', 0, '', 12.7, 12.7, 14, 12.7, 8, 8);
        //$header = \View('reportes.header', compact('dpto', 'qna'))->render();
        $mpdf->SetFooter('*Expedida Posteriormente|Generado el: {DATE j-m-Y} |Hoja {PAGENO} de {nb}');
        $html =  \View('reportes.reportelicencias', compact('incidencias'))->render();
        $pdfFilePath = 'Relacion de Licencias.pdf';
        $mpdf->setAutoTopMargin = 'stretch';
        $mpdf->setAutoBottomMargin = 'stretch';
        //$mpdf->setHTMLHeader($header);
        $mpdf->SetDisplayMode('fullpage');
        $mpdf->WriteHTML($html);

        $mpdf->Output($pdfFilePath, "D");
    }
    public function ausentismo()
    {
      $dptos = \Auth::user()->centros->pluck('id')->toArray();
      $dptos = Deparment::whereIn('deparments.id', $dptos)->get();
      return view('reportes.ausentismo')->with('deparments', $dptos);
    }
    public function ausentismo_centro(Request $request)
    {
      $fecha_inicial = fecha_ymd($request->fecha_inicio);
      $fecha_final = fecha_ymd($request->fecha_final);

      $dpto_id = $request->deparment_id;
      $dpto = Deparment::where('id','=', $dpto_id)->first();

      //$codigos = ['10','14', '17', '40','41','46','47','48','49','51','53','54','55','60','62','63','94','100'];
      $codigo_01 = Incidencia::getTotalIncidenciasPorCentro($fecha_inicial, $fecha_final,$dpto_id, 1);
      $codigo_02 = Incidencia::getTotalIncidenciasPorCentro($fecha_inicial, $fecha_final,$dpto_id, 2);
      $codigo_03 = Incidencia::getTotalIncidenciasPorCentro($fecha_inicial, $fecha_final,$dpto_id, 3);
      $codigo_04 = Incidencia::getTotalIncidenciasPorCentro($fecha_inicial, $fecha_final,$dpto_id, 4);
      $codigo_08 = Incidencia::getTotalIncidenciasPorCentro($fecha_inicial, $fecha_final,$dpto_id, 8);
      $codigo_09 = Incidencia::getTotalIncidenciasPorCentro($fecha_inicial, $fecha_final,$dpto_id, 9);
      $codigo_10 = Incidencia::getTotalIncidenciasPorCentro($fecha_inicial, $fecha_final,$dpto_id, 10);
      $codigo_14 = Incidencia::getTotalIncidenciasPorCentro($fecha_inicial, $fecha_final,$dpto_id, 14);
      $codigo_17 = Incidencia::getTotalIncidenciasPorCentro($fecha_inicial, $fecha_final,$dpto_id, 17);
      $codigo_15 = Incidencia::getTotalIncidenciasPorCentro($fecha_inicial, $fecha_final,$dpto_id, 15);
      $codigo_30 = Incidencia::getTotalIncidenciasPorCentro($fecha_inicial, $fecha_final,$dpto_id, 30);
      $codigo_40 = Incidencia::getTotalIncidenciasPorCentro($fecha_inicial, $fecha_final,$dpto_id, 40);
      $codigo_41 = Incidencia::getTotalIncidenciasPorCentro($fecha_inicial, $fecha_final,$dpto_id, 41);
      $codigo_42 = Incidencia::getTotalIncidenciasPorCentro($fecha_inicial, $fecha_final,$dpto_id, 42);
      $codigo_46 = Incidencia::getTotalIncidenciasPorCentro($fecha_inicial, $fecha_final,$dpto_id, 46);
      $codigo_47 = Incidencia::getTotalIncidenciasPorCentro($fecha_inicial, $fecha_final,$dpto_id, 47);
      $codigo_48 = Incidencia::getTotalIncidenciasPorCentro($fecha_inicial, $fecha_final,$dpto_id, 48);
      $codigo_49 = Incidencia::getTotalIncidenciasPorCentro($fecha_inicial, $fecha_final,$dpto_id, 49);
      $codigo_51 = Incidencia::getTotalIncidenciasPorCentro($fecha_inicial, $fecha_final,$dpto_id, 51);
      $codigo_53 = Incidencia::getTotalIncidenciasPorCentro($fecha_inicial, $fecha_final,$dpto_id, 53);
      $codigo_54 = Incidencia::getTotalIncidenciasPorCentro($fecha_inicial, $fecha_final,$dpto_id, 54);
      $codigo_55 = Incidencia::getTotalIncidenciasPorCentro($fecha_inicial, $fecha_final,$dpto_id, 55);
      $codigo_60 = Incidencia::getTotalIncidenciasPorCentro($fecha_inicial, $fecha_final,$dpto_id, 60);
      $codigo_61 = Incidencia::getTotalIncidenciasPorCentro($fecha_inicial, $fecha_final,$dpto_id, 61);
      $codigo_62 = Incidencia::getTotalIncidenciasPorCentro($fecha_inicial, $fecha_final,$dpto_id, 62);
      $codigo_63 = Incidencia::getTotalIncidenciasPorCentro($fecha_inicial, $fecha_final,$dpto_id, 63);
      $codigo_94 = Incidencia::getTotalIncidenciasPorCentro($fecha_inicial, $fecha_final,$dpto_id, 94);
      $codigo_100 = Incidencia::getTotalIncidenciasPorCentro($fecha_inicial, $fecha_final,$dpto_id, 100);

      return view('reportes.ausentismo_centro')
        ->with('codigo_01', $codigo_01)
        ->with('codigo_02', $codigo_02)
        ->with('codigo_03', $codigo_03)
        ->with('codigo_04', $codigo_04)
        ->with('codigo_08', $codigo_08)
        ->with('codigo_09', $codigo_09)
        ->with('codigo_10', $codigo_10)
        ->with('codigo_14', $codigo_14)
        ->with('codigo_15', $codigo_15)
        ->with('codigo_17', $codigo_17)
        ->with('codigo_30', $codigo_30)
        ->with('codigo_40', $codigo_40)
        ->with('codigo_41', $codigo_41)
        ->with('codigo_42', $codigo_42)
        ->with('codigo_46', $codigo_46)
        ->with('codigo_47', $codigo_47)
      ->with('codigo_48', $codigo_48)
      ->with('codigo_49', $codigo_49)
      ->with('codigo_51', $codigo_51)
      ->with('codigo_53', $codigo_53)
      ->with('codigo_54', $codigo_54)
      ->with('codigo_55', $codigo_55)
      ->with('codigo_60', $codigo_60)
      ->with('codigo_61', $codigo_61)
      ->with('codigo_62', $codigo_62)
      ->with('codigo_63', $codigo_63)
      ->with('codigo_94', $codigo_94)
      ->with('codigo_100', $codigo_100)
      ->with('fecha_inicial', $fecha_inicial)
      ->with('fecha_final', $fecha_final)
      ->with('dpto_id', $dpto_id)
      ->with('dpto', $dpto);

  }
  public function ausentismo_incidencias($codigo, $fecha_inicial, $fecha_final, $dpto_id)
  {
    $inc_empleados = Incidencia::getIncidenciasByCodeAndDate($codigo, $fecha_inicial, $fecha_final, $dpto_id);
    return view()->with('inc_empleados',$inc_empleados);
  }
  public function reporte_kardex_pdf($num_empleado, $fecha_inicio, $fecha_final)
  {

        $incidencias = Incidencia::getIncidenciasEmpleado($fecha_inicio, $fecha_final, $num_empleado);
        $empleado = Employe::where('num_empleado', '=', $num_empleado)->first();
        $dpto = Deparment::find($empleado->deparment_id);
        $jornada = Jornada::find($empleado->jornada_id);
        $horario = Horario::find($empleado->horario_id);
        $mpdf = new mPDF('', 'Letter', 0, '', 12.7, 12.7, 14, 12.7, 8, 8);
        $header = \View('reportes.header_kardex', compact('empleado', 'fecha_inicio', 'fecha_final', 'dpto', 'jornada','horario'))->render();
        $mpdf->SetFooter($empleado->name.' '.$empleado->father_lastname.' '.$empleado->mother_lastname.'|Generado el: {DATE j-m-Y} |Hoja {PAGENO} de {nb}');
        $html =  \View('reportes.reportegenerado_kardex', compact('incidencias'))->render();
        $pdfFilePath = $empleado->num_empleado.'-'.$empleado->name.'-'.$empleado->father_lastname.'-'.$empleado->mother_lastname.'.pdf';
        $mpdf->setAutoTopMargin = 'stretch';
        $mpdf->setAutoBottomMargin = 'stretch';
        $mpdf->setHTMLHeader($header);
        $mpdf->SetDisplayMode('fullpage');
        $mpdf->WriteHTML($html);

        $mpdf->Output($pdfFilePath, "D");
  }
  public function pendientes()
  {
    $dptos = \Auth::user()->centros->pluck('id')->toArray();
    $dptos = Deparment::whereIn('deparments.id', $dptos)->get();
    //$qnas = Qna::all()->pluck('Qnaa', 'id')->toArray();
    $qnas = Qna::orderby('id', 'desc')->limit(10)->get()->pluck('Qnaa', 'id')->toArray();
    krsort($qnas);
    return view('reportes.pendientes')->with('dptos', $dptos)->with('qnas', $qnas);
  }
  public function pendientes_show(Request $request, $depa)
  {
    $dpto = Deparment::where('code', '=', $depa)->first();
    //$pendientes = null;
    return view('reportes.pendientes_show')->with('dpto', $dpto)->with('qna_id', $request->qna_id);
  }
  public function pendientes_id($qna_id, $dpto, $pendiente_id)
  {
    $dpto = Deparment::where('code', '=', $dpto)->first();

    $pendientes = Incidencia::getPendientes($qna_id, $dpto->code, $pendiente_id);

    return view('reportes.pendientes_show')->with('dpto', $dpto)->with('pendientes', $pendientes)->with('qna_id', $qna_id);
  }

  public function exceso_incapacidades()
  {
   $dptos = \Auth::user()->centros->pluck('id')->toArray();

   $empleados = Employe::getEmpleado($dptos);

   //$empleado = Employe::where('num_empleado','=','159145')->first();
   foreach ($empleados as $empleado) {
     $fecha_inicio = getdateActual($empleado->fecha_ingreso); //"2018-12-01";
     $fecha_final = getdatePosterior($fecha_inicio); //"2019-12-01";

     $incapacidades[] = Incidencia::getIncapacidades($fecha_inicio, $fecha_final,$empleado->num_empleado);
   }
   $data = array_map('array_filter', $incapacidades);
   $data = array_filter($data);

   $return = array();
   foreach ($data as $key => $value) {
       if (is_array($value)){ $return = array_merge($return, array_flatten($value));}
       else {$return[$key] = $value;}
   }

    return view('reportes.excesos_de_incacapacidades.index')->with('data', $return);
  }

 public function estadistica()
  {
    //$dptos = \Auth::user()->centros->pluck('id')->toArray();
    //$dptos = Deparment::whereIn('deparments.id', $dptos)->get();
    //$qnas = Qna::all()->pluck('Qnaa', 'id')->toArray();
    //krsort($qnas);
    //return view('reportes.estadistica_concepto')->with('dptos', $dptos)->with('qnas', $qnas);
    return view('reportes.estadistica_concepto');
  }
  public function estadistica_concepto(Request $request)
  {
    $dptos = \Auth::user()->centros->pluck('id')->toArray();
    $fecha_inicial = fecha_ymd($request->fecha_inicio);
    $fecha_final = fecha_ymd($request->fecha_final);
    $incidencias = Incidencia::getIncidenciasByCode($request->code, $fecha_inicial, $fecha_final, $dptos);
    //dd($incidencias);
    $code_des = Codigo_De_Incidencia::where('code', '=', $request->code)->first();

    return view('reportes.estadistica_concepto_show')->with('incidencias',$incidencias)
                                                     ->with('codigo', $code_des)
                                                     ->with('fecha_inicio', $request->fecha_inicio)
                                                     ->with('fecha_final', $request->fecha_final);

  }
  public function estadistica_por_incidencia(Request $request){
    $dptos = \Auth::user()->centros->pluck('id')->toArray();
    $dptos = Deparment::whereIn('deparments.id', $dptos)->get();

    return view('reportes.reporte_por_incidencia')->with('deparments', $dptos);
  }
  public function estadistica_por_incidencia_show(Request $request){


    $dpto = Deparment::find($request->deparment_id);

    $fecha_inicial = fecha_ymd($request->fecha_inicio);
    $fecha_final = fecha_ymd($request->fecha_final);
    $incidencias = Incidencia::getIncidenciasByCode2($request->code, $fecha_inicial, $fecha_final, $dpto->id);

    $code_des = Codigo_De_Incidencia::where('code', '=', $request->code)->first();

    return view('reportes.estadistica_incidencia_show')->with('incidencias',$incidencias)
                                                     ->with('code_des', $code_des)
                                                     ->with('dpto', $dpto)
                                                     ->with('fecha_inicial', $fecha_inicial)
                                                     ->with('fecha_final', $fecha_final);
  }
  public function estadistica_por_incidencia_pdf($dpto, $fecha_inicial,$fecha_final,$code){

    $incidencias = Incidencia::getIncidenciasByCode2($code, $fecha_inicial, $fecha_final, $dpto);
    $fecha_inicio = fecha_dmy($fecha_inicial);
    $fecha_final = fecha_dmy($fecha_final);
    $mpdf = new mPDF('', 'Letter', 0, '', 12.7, 12.7, 14, 12.7, 8, 8);
        $header = \View('reportes.header_por_incidencias', compact('fecha_inicio', 'fecha_final'))->render();
        //$mpdf->SetFooter($empleado->name.' '.$empleado->father_lastname.' '.$empleado->mother_lastname.'|Generado el: {DATE j-m-Y} |Hoja {PAGENO} de {nb}');
        $html =  \View('reportes.estadistica_por_incidencia_pdf', compact('incidencias'))->render();
        $pdfFilePath = 'reporte_por_incidencia.pdf';
        $mpdf->setAutoTopMargin = 'stretch';
        $mpdf->setAutoBottomMargin = 'stretch';
        $mpdf->setHTMLHeader($header);
        $mpdf->SetDisplayMode('fullpage');
        $mpdf->WriteHTML($html);

        $mpdf->Output($pdfFilePath, "D");
  }
  public function inasistencias(){

    /*
    $dptos = \Auth::user()->centros->pluck('id')->toArray();
    $dptos = Deparment::whereIn('deparments.id', $dptos)->get();
    dd($dptos);
    */
    return view('reportes.inasistencias.index');
  }
  public function inasistencias_get(Request $request){
    $fecha_inicial = fecha_ymd($request->fecha_inicio);
    $fecha_final = fecha_ymd($request->fecha_final);
    $dptos = \Auth::user()->centros->pluck('id')->toArray();
    $codes = ['10','100'];

    $incidencias = Incidencia::getInasistencias($codes,$fecha_inicial, $fecha_final, $dptos);

    return view('reportes.inasistencias.show')->with('incidencias', $incidencias);
  }
  public function val_aguinaldo(Request $request){
    $fecha_inicial = "2022-01-01";
    $fecha_final = "2022-12-31";
    $dptos = \Auth::user()->centros->pluck('id')->toArray();


    $incidencias = Incidencia::valAguinaldo($fecha_inicial, $fecha_final, $dptos);

    return view('reportes.aguinaldo.show')->with('incidencias', $incidencias);
  }
  public function val_aguinaldo_pdf(Request $request){
    $fecha_inicial = "2023-01-01";
    $fecha_final = "2023-12-31";
    $dptos = \Auth::user()->centros->pluck('id')->toArray();
    $incidencias = Incidencia::valAguinaldo($fecha_inicial, $fecha_final, $dptos);
    $mpdf = new mPDF('', 'Letter', 0, '', 12.7, 12.7, 14, 12.7, 8, 8);
        $header = \View('reportes.aguinaldo.header', compact('fecha_inicial', 'fecha_final'))->render();
        //$mpdf->SetFooter($empleado->name.' '.$empleado->father_lastname.' '.$empleado->mother_lastname.'|Generado el: {DATE j-m-Y} |Hoja {PAGENO} de {nb}');
        $html =  \View('reportes.aguinaldo.aguinaldo_pdf', compact('incidencias'))->render();
        $pdfFilePath = 'Reporte_aguinaldo_2022.pdf';
        $mpdf->setAutoTopMargin = 'stretch';
        $mpdf->setAutoBottomMargin = 'stretch';
        $mpdf->setHTMLHeader($header);
        $mpdf->SetDisplayMode('fullpage');
        $mpdf->WriteHTML($html);

        $mpdf->Output($pdfFilePath, "D");




    //return view('reportes.aguinaldo.show')->with('incidencias', $incidencias);
  }
  public function vacaciones()
   {
    $title = "Reporte rapido de vacaciones";
   	return view('reportes.vacaciones.index')->with('title', $title);
   }

}
