<?php

namespace App\Http\Controllers;

use Mpdf\Mpdf;
use App\Traits\PdfGeneratorTrait;

use App\Qna;
use App\Employe;
use App\Horario;
use App\Jornada;
use App\Deparment;
use Carbon\Carbon;
use App\Incidencia;
use App\Http\Requests;
use Laracasts\Flash\Flash;
use Illuminate\Http\Request;
use App\Codigo_De_Incidencia;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;

class ReportsController extends Controller
{
    use PdfGeneratorTrait;

   public function general()
   {
   	    $dptos = \Auth::user()->centros->pluck('id')->toArray();
		$dptos = Deparment::whereIn('deparments.id', $dptos)->get();
        $default_year = Carbon::now()->format('Y');
        $qnas = ['1' => '01 - 1RA ENERO','2' => '02 - 2DA ENERO','3' => '03 - 1RA FEBRERO','4' => '04 -	2DA FEBRERO','5' => '05 - 1RA MARZO','6' => '06 - 2DA MARZO','7' => '07 - 1RA ABRIL','8' => '08 - 2DA ABRIL','9' => '09 - 1RA MAYO','10' => '10 - 2DA MAYO','11' => '11 - 1RA JUNIO','12' => '12 - 2DA JUNIO','13' => '13 - 1RA JULIO','14' => '14 - 2DA JULIO'
            ,'15' => '15 - 1RA AGOSTO','16' => '16 - 2DA AGOSTO','17' => '17 - 1RA SEPTIEMBRE','18' => '18 - 2DA SEPTIEMBRE','19' => '19 - 1RA OCTUBRE','20' => '20 - 2DA OCTUBRE','21' => '21 - 1RA NOVIEMBRE','22' => '22 - 2DA NOVIEMBRE','23' => '23 - 1RA DICIEMBRE','24' => '24 - 2DA DICIEMBRE'
        ];
        //$years = ['2024' => '2024','2023' => '2023','2022' => '2022','2021' => '2021','2020' => '2020','2019' => '2019','2018' => '2018'];
        $years = array();
        for($i = 2018; $i<= date("Y"); $i++) {
            $years["$i"] = $i;
        }


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
        // Obtener el departamento
        $dpto = Deparment::where('code', '=', $dpto)->first();

        // Obtener la quincena
        $qna = Qna::where('qna', $request->qna)
                ->where('year', $request->year)
                ->first();

        // Obtener las incidencias del centro
        $incidencias = Incidencia::getIncidenciasCentro($qna->id, $dpto->id);

        // Agrupar las incidencias por empleado
        $groupedIncidencias = [];
        foreach ($incidencias as $incidencia) {
            $groupedIncidencias[$incidencia->num_empleado]['empleado'] = $incidencia->father_lastname . ' ' . $incidencia->mother_lastname . ' ' . $incidencia->name;
            $groupedIncidencias[$incidencia->num_empleado]['incidencias'][] = [
                'codigo' => str_pad($incidencia->code, 2, '0', STR_PAD_LEFT),
                'fecha_inicio' => fecha_dmy($incidencia->fecha_inicio),
                'fecha_final' => fecha_dmy($incidencia->fecha_final),
                'periodo' => isset($incidencia->periodo) ? $incidencia->periodo . '/' . $incidencia->periodo_year : '-',
                'total_dias' => $incidencia->total_dias,
            ];
        }

        // Título del reporte
        $title = "Reporte General Qna: " . $qna->qna . "/" . $qna->year . " - " . $qna->description;

        // Retornar la vista con los datos agrupados
        return view('reportes.show')
            ->with('groupedIncidencias', $groupedIncidencias)
            ->with('title', $title)
            ->with('qna', $qna)
            ->with('dpto', $dpto);
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

       try {
           // Obtener incidencias según filtros
           if ($request->solo_medicos == true) {
               $medicosIds = ['24','25','55','56','57','58','59','60','61','62','63','64','65','66','67','68','69'];
               $incidencias = Incidencia::GetIncidenciasPorDia_Solo_Medicos($dptos, $medicosIds, fecha_ymd($fecha));
           } else {
               $incidencias = Incidencia::GetIncidenciasPorDia($dptos, fecha_ymd($fecha));
           }

           // Verificación básica de datos
           if (empty($incidencias)) {
               Flash::warning('No hay datos para esta fecha: '.fecha_dmy($fecha));
               return redirect()->route('reports.diario');
           }


           $pdfFilePath = 'REPORTE_DE_INCIDENCIAS_DIARIO_DEL_'.Carbon::now()->format('d-m-Y').'.pdf';

           // Generar PDF usando el trait con la misma estructura que antes
           return $this->generatePdf(
               'reportes.reporte_diario_generado',
               ['incidencias' => $incidencias],
               $pdfFilePath,
               'reportes.header_diario',
               ['fecha' => $fecha],
               'ISSSTE|Generado el: {DATE j-m-Y}|Hoja {PAGENO} de {nb}',
               'I'
           );
       } catch (\Exception $e) {
           \Log::error('Error en diario_post: ' . $e->getMessage() . ' - ' . $e->getTraceAsString());
           Flash::error('Error al generar el PDF: ' . $e->getMessage());
           return redirect()->route('reports.diario');
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
   public function getsinDerechoPDF($dpto, $fecha_inicio, $fecha_final)
   {
       $lic = ['40','41','46','47','53','54','55'];
       $inc = ['01','02','03','04','08','09','10','18','19','25','78','86','100','30','31'];

       $incidencias_inc = Incidencia::noDerecho_inc($dpto, $fecha_inicio, $fecha_final, $inc);
       $incidencias_lic = Incidencia::noDerecho_lic($dpto, $fecha_inicio, $fecha_final, $lic);

       $incidencias = array_merge($incidencias_inc, $incidencias_lic);
       $incidencias = collect($incidencias)->unique('num_empleado')->sortBy('num_empleado')->toArray();

       $dpto = Deparment::where('code', '=', $dpto)->first();
       $pdfFilePath = $dpto->description.'.SIN-DERECHO.pdf';

       return $this->generatePdf(
           'reportes.sinderechopdf',            // Vista para contenido
           ['incidencias' => $incidencias],     // Datos para la vista
           $pdfFilePath,                        // Nombre del archivo
           'reportes.header-sinderecho',        // Vista para el encabezado
           ['dpto' => $dpto, 'fecha_inicio' => $fecha_inicio],  // Datos para el encabezado
           'FIRMA CENTRO DE TRABAJO|VO.BO. DELEGADO SINDICAL<br>'.$dpto->description.'|Hoja {PAGENO} de {nb}', // Pie
           'D',                                 // Modo de salida (descarga)
           true                                 // Orientación horizontal
       );
   }
    //RH5
   public function reporte_pdf($qna_id, $dpto)
    {
        // Obtener datos
        $dpto = Deparment::where('code', '=', $dpto)->first();
        if (!$dpto) {
            Flash::error('Departamento no encontrado');
            return redirect()->back();
        }

        $qna = Qna::find($qna_id);
        if (!$qna) {
            Flash::error('Quincena no encontrada');
            return redirect()->back();
        }

        $incidencias = Incidencia::getIncidenciasCentroPDF($qna_id, $dpto->id);

        // Nombre del archivo de salida
        $pdfFilePath = $qna->qna.'-'.$qna->year.'-'.$dpto->description.'.pdf';

        // Pie de página personalizado
        $footer = '<table class="footer-table" width="100%" style="border: none;">
            <tr style="border: none;">
                <td width="33%" style="border: none; text-align: left;">'.$dpto->description.'</td>
                <td width="33%" style="border: none; text-align: center;">Generado el: {DATE j-m-Y}</td>
                <td width="33%" style="border: none; text-align: right;">Hoja {PAGENO} de {nb}</td>
            </tr>
        </table>';

        // Generar el PDF con un solo método
        return $this->generatePdf(
            'reportes.reportegenerado',   // Vista de contenido
            ['incidencias' => $incidencias], // Datos para la vista
            $pdfFilePath,                // Nombre de archivo
            'reportes.header',           // Vista de encabezado
            ['dpto' => $dpto, 'qna' => $qna], // Datos para el encabezado
            $footer,                     // Pie de página personalizado
            'I'                          // Modo de salida: I (mostrar en navegador)
        );
    }

    public function reporte_pdf_diario($qna_id, $dpto)
    {
        $dpto = Deparment::where('code', '=', $dpto)->first();
        $incidencias = Incidencia::getIncidenciasCentroPDF_diario($qna_id, $dpto->id);
        $qna = Qna::find($qna_id);

        $pdfFilePath = $qna->qna.'-'.$qna->year.'-'.$dpto->description.'.pdf';

        // Usar el método reutilizable
        return $this->generatePdf(
            'reportes.reportegenerado',
            ['incidencias' => $incidencias],
            $pdfFilePath,
            'reportes.header',
            ['dpto' => $dpto, 'qna' => $qna],
            $dpto->description.'|Generado el: {DATE j-m-Y}|Hoja {PAGENO} de {nb}',
            'D'  // Modo de salida: D (descargar)
        );
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

        $pdfFilePath = 'Relacion de Licencias.pdf';

        return $this->generatePdf(
            'reportes.reportelicencias',         // Vista para contenido
            ['incidencias' => $incidencias],     // Datos para la vista
            $pdfFilePath,                        // Nombre del archivo
            null,                                // Sin vista de encabezado
            [],                                  // Sin datos para el encabezado
            '*Expedida Posteriormente|Generado el: {DATE j-m-Y} |Hoja {PAGENO} de {nb}',  // Pie
            'D',                                 // Modo de salida (descarga)
            true                                 // Orientación horizontal
        );
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
        $dpto = Deparment::where('id', $dpto_id)->first();

        $codigos = [1, 2, 3, 4, 8, 9, 10, 14, 15, 17, 30, 40, 41, 42, 46, 47, 48, 49, 51, 53, 54, 55, 60, 61, 62, 63, 94, 100];

        $incidencias = Incidencia::select('codigos_de_incidencias.code', DB::raw('SUM(total_dias) AS count'))
            ->leftJoin('employees', 'employees.id', '=', 'incidencias.employee_id')
            ->leftjoin('deparments', 'deparments.id', '=', 'employees.deparment_id')
            ->leftJoin('codigos_de_incidencias', 'codigos_de_incidencias.id', '=', 'incidencias.codigodeincidencia_id')
            ->whereNull('incidencias.deleted_at')
            ->where('deparments.id', '=', $dpto_id)
            ->whereIn('codigos_de_incidencias.code', $codigos)
            ->whereBetween('fecha_inicio', [$fecha_inicial, $fecha_final])
            ->groupBy('codigos_de_incidencias.code')
            ->get()
            ->pluck('count', 'code')
            ->toArray();

        $results = [];
        foreach ($codigos as $codigo) {
            $codigoStr = sprintf("%02d", $codigo);
            $results['codigo_' . $codigoStr] = $incidencias[$codigo] ?? 0;
        }

        return view('reportes.ausentismo_centro', [
            'fecha_inicial' => $fecha_inicial,
            'fecha_final' => $fecha_final,
            'dpto_id' => $dpto_id,
            'dpto' => $dpto,
            'incidencias' => $results, // Pasar $results como $incidencias
        ]);
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

      $pdfFilePath = $empleado->num_empleado.'-'.$empleado->name.'-'.$empleado->father_lastname.'-'.$empleado->mother_lastname.'.pdf';

      return $this->generatePdf(
          'reportes.reportegenerado_kardex',   // Vista para contenido
          ['incidencias' => $incidencias],     // Datos para la vista
          $pdfFilePath,                        // Nombre del archivo
          'reportes.header_kardex',            // Vista para el encabezado
          [                                    // Datos para el encabezado
              'empleado' => $empleado,
              'fecha_inicio' => $fecha_inicio,
              'fecha_final' => $fecha_final,
              'dpto' => $dpto,
              'jornada' => $jornada,
              'horario' => $horario
          ],
          $empleado->name.' '.$empleado->father_lastname.' '.$empleado->mother_lastname.'|Generado el: {DATE j-m-Y} |Hoja {PAGENO} de {nb}',  // Pie
          'D'                                  // Modo de salida (descarga)
      );
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

    // Obtener todos los empleados de una vez
    $empleados = Employe::getEmpleado($dptos);

    // Convertir a colección si es un array o preparar directamente los IDs
    $empleadosIds = is_array($empleados) ?
                   array_map(function($empleado) { return $empleado->num_empleado; }, $empleados) :
                   $empleados->pluck('num_empleado')->toArray();

    // Obtener fechas límite para calcular incapacidades
    $fechasLimite = [];
    foreach ($empleados as $empleado) {
        $fechaInicio = getdateActual($empleado->fecha_ingreso);
        $fechaFinal = getdatePosterior($fechaInicio);
        $fechasLimite[$empleado->num_empleado] = [
            'fecha_inicio' => $fechaInicio,
            'fecha_final' => $fechaFinal
        ];
    }

    // Obtener todas las incapacidades en una sola consulta
    $todasIncapacidades = Incidencia::getIncapacidadesMultiples($empleadosIds, $fechasLimite);

    // Filtrar resultados vacíos y aplicar regla de exceso de incapacidades
    $data = [];
    foreach ($todasIncapacidades as $numEmpleado => $incapacidadesInfo) {
        if (empty($incapacidadesInfo) || empty($incapacidadesInfo['incapacidades'])) {
            continue;
        }

        $totalDias = $incapacidadesInfo['total_dias'];
        $incapacidades = $incapacidadesInfo['incapacidades'];

        if (empty($incapacidades)) {
            continue;
        }

        // Calcular antigüedad en años
        $fechaIngreso = new Carbon($incapacidades[0]->fecha_ingreso);
        $hoy = Carbon::now();
        $antiguedad = $fechaIngreso->diffInYears($hoy);

        // Verificar si excede el límite según antigüedad
        if ($this->getExcesodeIncapacidad($totalDias, $antiguedad)) {
            $data[$numEmpleado] = $incapacidadesInfo;
        }
    }

    return view('reportes.excesos_de_incacapacidades.index')->with('data', $data);
  }

  private function getExcesodeIncapacidad($dias_lic, $antiguedad)
  {
      // Pequeño ajuste para manejar casos límite exactos (>=)
      if (($dias_lic > 15 && $antiguedad < 1) ||
          ($dias_lic > 30 && $antiguedad >= 1 && $antiguedad <= 4) ||  // Cambiado a >= 1
          ($dias_lic > 45 && $antiguedad >= 5 && $antiguedad <= 9) ||
          ($dias_lic > 60 && $antiguedad >= 10)) {
          return 1;
      }
      return 0;
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
  public function estadistica_por_incidencia_pdf($dpto, $fecha_inicial, $fecha_final, $code)
  {
      $incidencias = Incidencia::getIncidenciasByCode2($code, $fecha_inicial, $fecha_final, $dpto);
      $fecha_inicio = fecha_dmy($fecha_inicial);
      $fecha_final = fecha_dmy($fecha_final);

      $pdfFilePath = 'reporte_por_incidencia.pdf';

      return $this->generatePdf(
          'reportes.estadistica_por_incidencia_pdf',  // Vista para contenido
          ['incidencias' => $incidencias],           // Datos para la vista
          $pdfFilePath,                              // Nombre del archivo
          'reportes.header_por_incidencias',         // Vista para el encabezado
          ['fecha_inicio' => $fecha_inicio, 'fecha_final' => $fecha_final],  // Datos para encabezado
          'Generado el: {DATE j-m-Y} |Hoja {PAGENO} de {nb}',  // Pie de página
          'D'                                        // Modo de salida (descarga)
      );
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
    $fecha_inicial = "2024-01-01";
    $fecha_final = "2024-12-31";
    $dptos = \Auth::user()->centros->pluck('id')->toArray();


    $incidencias = Incidencia::valAguinaldo($fecha_inicial, $fecha_final, $dptos);

    return view('reportes.aguinaldo.show')->with('incidencias', $incidencias);
  }
  public function val_aguinaldo_pdf(Request $request)
  {
      $fecha_inicial = "2024-01-01";
      $fecha_final = "2024-12-31";
      $dptos = \Auth::user()->centros->pluck('id')->toArray();
      $incidencias = Incidencia::valAguinaldo($fecha_inicial, $fecha_final, $dptos);

      $pdfFilePath = 'Reporte_aguinaldo_2023.pdf';

      return $this->generatePdf(
          'reportes.aguinaldo.aguinaldo_pdf',  // Vista para contenido
          ['incidencias' => $incidencias],     // Datos para la vista
          $pdfFilePath,                        // Nombre del archivo
          'reportes.aguinaldo.header',         // Vista para el encabezado
          ['fecha_inicial' => $fecha_inicial, 'fecha_final' => $fecha_final],  // Datos para encabezado
          'Generado el: {DATE j-m-Y} |Hoja {PAGENO} de {nb}',  // Pie de página
          'D'                                  // Modo de salida (descarga)
      );
  }
  public function vacaciones()
   {
    $title = "Reporte rapido de vacaciones";
   	return view('reportes.vacaciones.index')->with('title', $title);
   }

   public function captura_diaria()
   {
      $title = "Reporte de captura por dia";
      return view('reportes.captura_diaria')->with('title', $title);
   }

   public function captura_diaria_post(Request $request)
   {
      $dptos = \Auth::user()->centros->pluck('id')->toArray();
      $fecha = $request->fecha_inicio;
      $fecha_inicial = fecha_ymd($request->fecha_inicio);

      if ($request->solo_medicos == true) {
          $medicosIds = ['24','25','55','56','57','58','59','60','61','62','63','64','65','66','67','68','69'];
          $incidencias = Incidencia::GetIncidenciasPorDia_Solo_MedicosPorDia($dptos, $medicosIds, $fecha_inicial);
      } else {
          $incidencias = Incidencia::GetIncidenciasCapturaPorDia($dptos, $fecha_inicial);
      }

      if (!$incidencias) {
          Flash::warning('No hay datos para esta fecha: '.fecha_dmy($fecha));
          return redirect()->route('reports.captura_por_dia');
      } else {
          $pdfFilePath = 'REPORTE_DE_INCIDENCIAS_DEL_DIA_'.Carbon::now()->format('d-m-Y').'.pdf';

          return $this->generatePdf(
              'reportes.reporte_diario_generado',  // Vista para contenido
              ['incidencias' => $incidencias],     // Datos para la vista
              $pdfFilePath,                        // Nombre del archivo
              'reportes.header_diario2',           // Vista para el encabezado
              ['fecha' => $fecha],                 // Datos para el encabezado
              'Generado el: {DATE j-m-Y} |Hoja {PAGENO} de {nb}',  // Pie de página
              'D'                                  // Modo de salida (descarga)
          );
      }
   }
   public function otorgados(Request $request){


    //$dpto = Deparment::find($request->deparment_id);
    $fecha_inicial = "2024-01-01";
    $fecha_final = "2024-09-30";
    //$fecha_inicial = fecha_ymd($request->fecha_inicio);
    //$fecha_final = fecha_ymd($request->fecha_final);
    $incidencias = Incidencia::getPorOtorgado($request->code, $fecha_inicial, $fecha_final);


   return view('reportes.otorgado_incidencia_show')->with('incidencias',$incidencias)
                                                     ->with('fecha_inicial', $fecha_inicial)
                                                     ->with('fecha_final', $fecha_final);

  }




}
