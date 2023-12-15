<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Http\Requests\IncidenciasRequest;
use App\Http\Requests;
use Response;
use App\Http\Controllers\Controller;
use App\Employe;
use App\Deparment;
use App\Qna;
use App\Periodo;
use App\Incidencia;
use App\Codigo_De_Incidencia;
use App\Comentario;
use Carbon\Carbon;
use App\Configuration;


use Laracasts\Flash\Flash;


class IncidenciasController extends Controller
{
   public function __construct()
    {
        $this->middleware('auth');
        //$this->middleware('maintenance');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        if (\Auth::user()->admin() || \Auth::user()->member()) {


            return view('incidencias.index');
        }
        return redirect()->route('reports.general');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {


    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    //, $empleado_id, $qna_id
    public function store(Request $request)
    {
        // VALIDANDO MANTENIMIENTO EN TRUE
        if (check_manto() && !\Auth::user()->admin()) {
            return response()->json('El sistema este en periodo de mantenimiento... intentar mas tarde',500);
        }
        date_default_timezone_set('UTC');

        if($request->qna_id != 0){
            $fecha_inicio = getFechaInicioPorQna($request->qna_id);
            $fecha_final = getFechaFinalPorQna($fecha_inicio);
        }
        else {
            $fecha_inicio = fecha_ymd($request->datepicker_inicial);
            $fecha_final = fecha_ymd($request->datepicker_final);
        }

        $empleado = Employe::find($request->empleado_id);



        $fecha_expedida = ($request->datepicker_expedida) ? fecha_ymd($request->datepicker_expedida) : NULL;
        $fecha1 = strtotime($fecha_inicio);
        $fecha2 = strtotime($fecha_final);

        $mat_desp = [14,17];
        $syf_dyf = [1,15];
        $guardias = [2,3,5,6,18,13,20,4,7,8,9,10,11,19,21,22,23,24,25,26,27,28,29,30,31,32];

        $fechas = array();

        for ($i=$fecha1; $i <= $fecha2; $i+=86400) {
            $fecha = date("Y-m-d", $i);
            $qna = qna_year($fecha);
            $fechas[] =  ['fecha' => $fecha,'qna' => $qna];
        }
        $group = array_group_by($fechas,'qna');

        foreach ($group as $key => $value) {
            $incidencia = new Incidencia($request->all());
            $incidencia->employee_id = $request->empleado_id;
            $incidencia->codigodeincidencia_id = $request->codigo;

            $fecha_inicial = reset($value);
            $fecha_final = end($value);

            $start = Carbon::parse($fecha_inicial['fecha']);
            $end = Carbon::parse($fecha_final['fecha'])->addDay();
            $total_dias = $start->diffInDays($end);

            $incidencia->qna_id = qna_year($start);
            $incidencia->fecha_inicio = $fecha_inicial['fecha'];
            $incidencia->fecha_final = $fecha_final['fecha'];
            $incidencia->token = genToken();
            $incidencia->total_dias = $total_dias;
            $incidencia->fecha_expedida = $fecha_expedida;
            $incidencia->capturado_por = capturado_por(\Auth::user()->id);

            $incidencia->fecha_capturado =  Carbon::now();

            $codigo = Codigo_De_Incidencia::where('id', $request->codigo)->first();

            $qna = Qna::find($incidencia->qna_id);




            /* Validando Qna Activa */
                if(!isset($qna->id))
                return response()->json('Quincena no activada',500);

            /* WIP!! Validando 72 horas o tres dias limites de captura  */
            /*
            $now = Carbon::now();
            $diff = $start->diffInDays($now);
            switch ($now->dayOfWeek) {
                case '1':
                     if ($diff >= 3)
                       return response()->json(
                         'Fecha de incidencia Excede limite de 3 dias',500);
                break;
                case '2':
                     if ($diff >= 3)
                       return response()->json(
                         'Fecha de incidencia Excede limite de 3 dias',500);
                break;
                case '3':
                     if ($diff >= 5)
                       return response()->json(
                         'Fecha de incidencia Excede limite de 3 dias',500);
                break;
                case '4':
                     if ($diff >= 5)
                       return response()->json(
                         'Fecha de incidencia Excede limite de 3 dias',500);
                break;
                case '5':
                     if ($diff >= 5)
                       return response()->json(
                         'Fecha de incidencia Excede limite de 3 dias',500);
                break;
                case '6':
                     if ($diff >= 4)
                       return response()->json(
                         'Fecha de incidencia Excede limite de 3 dias',500);
                break;
                case '0':
                     if ($diff >= 3)
                       return response()->json(
                         'Fecha de incidencia Excede limite de 3 dias',500);
                break;

            }

        */
            if ($codigo->code == 912) {
                return response()->json('Codigo 912 ya no se encuentra activo, acude a Recursos Humanos',500);
            }
            /* Validando pases de salida */
            if ($codigo->code == 905 && $empleado->condicion_id != 1 ) {
                return response()->json('Pases de salida solo validos para el personal de BASE',500);
            }

            if ($codigo->code == 905 && $this->valPaseDeSalida($qna, $incidencia->employee_id)) {
                return response()->json('Pase de salida de QNA: '.$qna->qna.'-'.$qna->year.' ya gozado',500);
            }

            /* Validando ya capturado y excepciones*/
            $incidencias_duplicados = [01,18,19,02,03,04,07,92,905,30];
            //if ($this->yaCapturado($incidencia->employee_id, $fecha_inicial['fecha'],$fecha_final['fecha']) && $codigo->code != 01 && $codigo->code != 905  && $codigo->code != 18 && $codigo->code != 19 && $codigo->code != 02  && $codigo->code != 03 && $codigo->code != 04 && $codigo->code != 07 && $codigo->code != 92) {
                if ($this->yaCapturado($incidencia->employee_id, $fecha_inicial['fecha'],$fecha_final['fecha']) && !in_array($codigo->code,$incidencias_duplicados)) {
                return response()->json(
                       'Ya existe una incidencia este dia o periodo',500);
            }

            /* Validando Licencias sin goce de sueldo*/

            /*
             if(validar_licencia_sin_goce($incidencia->employee_id,$fecha_inicial['fecha'],$fecha_final['fecha'])) {
                return response()->json(
                    'Existe licencia sin goce de sueldo',500);
             }
             */

            /* Se cierra licencia sin goce de sueldo */

            /* Validacion que existq periodo de vacaciones 60-63 */

            if ($codigo->code == 60 || $codigo->code==62 || $codigo->code==63) {
                if (!$request->periodo_id) {
                    if ($request->ajax()) {
                        return response()->json(
                            'Ingrese Periodo Vacacional',500);
                    }
                }
             // Validacion de vacaciones saltando validacion

                if ($request->saltar_validacion != 'true') {

                        //if ($this->valVacaciones($incidencia, $total_dias))
                    $vacaciones = Incidencia::getTotalVacaciones($incidencia->employee_id, $incidencia->periodo_id, $incidencia->codigodeincidencia_id);

                    $vacacion = Periodo::find($incidencia->periodo_id);
                    $de2 = [1,15];
                    $de5 = [2,3,5,6,18,13,20,30,32];
                    $de6 = [4,7,8,9,10,11,19,31];
                    $demas = [14,17];
                    //dd($vacaciones+$total_dias);

                    if (($vacaciones+$total_dias) > 2 && in_array($empleado->jornada_id, $de2)) {
                        return response()->json(
                            'Error '.$vacaciones.' de 2 dias vacacionales periodo '.$vacacion->periodo.'-'.$vacacion->year, 500);
                    }
                    if (($vacaciones+$total_dias) > 5 && in_array($empleado->jornada_id, $de5)) {
                        return response()->json(
                            'Error '.$vacaciones.' de 5 dias vacacionales periodo '.$vacacion->periodo.'-'.$vacacion->year, 500);
                    }
                    if (($vacaciones+$total_dias) > 6 && in_array($empleado->jornada_id, $guardias)) {
                        return response()->json(
                            'Error '.$vacaciones.' de 6 dias vacacionales periodo '.$vacacion->periodo.'-'.$vacacion->year, 500);
                    }
                    if (($vacaciones+$total_dias) > 10 && in_array($empleado->jornada_id, $demas)) {
                         return response()->json(
                            'Error '.$vacaciones.' de 10 dias vacacionales periodo '.$vacacion->periodo.'-'.$vacacion->year, 500);
                    }
                }

            } //termina if ($codigo->code == 60 || $codigo->code==62 || $codigo->code==63) {
            if ($request->saltar_validacion_lic != 'true') {

                    ///// Validacion faltas por jornadas
                      if ($codigo->code == 10) {
                          if (in_array($empleado->jornada_id, $guardias)) $incidencia->total_dias = 2;

                          if (in_array($empleado->jornada_id, $syf_dyf))  $incidencia->total_dias = 4;

                      }
                    ///// Validacion Dias de incapacidades
                  if ($request->saltar_validacion_inca != 'true') {
                      if ($codigo->code == 55) {

                        if (in_array($empleado->jornada_id, $guardias))  $incidencia->total_dias = 2;

                        if (in_array($empleado->jornada_id, $syf_dyf)) $incidencia->total_dias = 4;

                      }
                  }
            }  //if ($request->saltar_validacion_lic != 'true') {


              ///// Validacion Dias de licencia con goce
            if ($request->saltar_validacion_lic != 'true') {
                if ($codigo->code == 40 || $codigo->code == 41 || $codigo->code == 47 || $codigo->code == 48 || $codigo->code == 49 || $codigo->code == 29 || $codigo->code == 92) {

                        if (in_array($empleado->jornada_id, $guardias)) $incidencia->total_dias = 2;

                        if (in_array($empleado->jornada_id, $syf_dyf))  $incidencia->total_dias = 4;

                        if ($codigo->code == 41) {
                        //if ($codigo->code == 40 || $codigo->code == 41) {
                            $antiguedad = getAntiguedad($empleado->fecha_ingreso);
                            $a = getExcesodeLicenciasConGoce($start, $antiguedad, $empleado->num_empleado, $incidencia->total_dias);
                            if ($a != 0) {
                                if ($request->ajax()) {
                                return response()->json(
                                'Trabajador ya tomo '.$a.' dias economicos',500);
                                }
                            }
                        }
                }
            }   // termina if ($request->saltar_validacion_lic != 'true') {
                 //VALIDACION DE TXT
           // if ($request->saltar_validacion_txt != 'true') {
                if ($codigo->code == 900) {
                    if($incidencia->cobertura_txt == NULL){
                        return response()->json('Debe especificar el sustituto',500);
                    }

                    $a = getTxtPorMes($empleado->num_empleado, $incidencia->fecha_inicio);
                    $dias = $a + $incidencia->total_dias;

                    //VALIDACION DE MAS DE LOS DIAS QUE TIENE PERMITIDO
                    if ($dias > 5 && in_array($empleado->jornada_id, $mat_desp)) {
                        if ($request->ajax()) {
                                return response()->json('Trabajador no puede gozar mas de 5 dias de T.X.T',500);
                            }
                    }
                    if ($dias > 1 && in_array($empleado->jornada_id, $syf_dyf)) {
                        if ($request->ajax()) {
                                return response()->json('Trabajador no puede gozar mas de 1 dias de T.X.T',500);
                            }
                    }
                    if ($dias > 2 && in_array($empleado->jornada_id, $guardias)) {
                        if ($request->ajax()) {
                                return response()->json('Trabajador no puede gozar mas de 2 dias de T.X.T',500);
                            }
                    }

            //    }
                }   //termina if ($request->saltar_validacion_txt != 'true') {
                if ($codigo->code == 902) {
                    if($incidencia->cobertura_txt == NULL){
                        return response()->json('Debe especificar el sustituto',500);
                    }
                }

          $incidencia->save();
        }

        $incidencias = Incidencia::getIncidencias($empleado->num_empleado);

        if ($request->ajax()) {
                return response()->json(
                        $incidencias,200);
        }

}

    public function show($num_empleado)
    {


      $incidencias = Incidencia::getIncidencias($num_empleado);

      $employee = Employe::where('num_empleado', '=', $num_empleado)->first();

      return view('incidencias.side')
        ->with('incidencias',$incidencias)
        ->with('employee', $employee);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $incidencia = Incidencia::find($id);
        //$incidencias = Incidencia::all();

        $employees = Employe::all()->pluck('num_empleado', 'id')->toArray();
        $qnas = Qna::all()->pluck('qnaa', 'id')->toArray();
        $periodos = Periodo::all()->pluck('periodoo', 'id')->toArray();
        $codigosdeincidencias = Codigo_De_Incidencia::all()->pluck('codigo', 'id')->toArray();


        return view('incidencias.edit')
            ->with('incidencia', $incidencia)
            ->with('qnas', $qnas)
            ->with('employees', $employees)
            ->with('periodos', $periodos)
            ->with('codigosdeincidencias', $codigosdeincidencias);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $incidencia = Incidencia::find($id);

        $incidencia->fill($request->all());

        $incidencia->fecha_inicio = fecha_ymd($request->fecha_inicio);
        $incidencia->fecha_final = fecha_ymd($request->fecha_final);

        $incidencia->token = genToken();

        $incidencia->save();
        Flash::success('Incidencia editada con exito!');
        return redirect()->route('incidencias.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($token, $num_empleado, $qna_id)
    {

        $incidencias = Incidencia::where('token', $token)->get();
        //dd($incidencia);
        foreach ($incidencias as $incidencia) {
            $incidencia->delete();
        }
        $incidencias = Incidencia::getIncidencias($num_empleado);
        return response()->json(
                $incidencias
               );

        //Flash::error('Incidencia borrada con exito!');
        //return redirect()->route('admin.incidencias.show', ['num_empleado' => $num_empleado, 'qna_id' => $qna_id]);
    }
    public function incidencias_del($token, $num_empleado, $qna_id)
    {
      //date_default_timezone_set("UTC");
       $incidencias = Incidencia::where('token', $token)->get();
        //dd($incidencia);
        foreach ($incidencias as $incidencia) {
            $incidencia->delete();
        }


        return response()->json([
                    'mensaje' => 'success'
                    ],200);

    }
    public function comentario_create($empleado_id)
    {
        $comentario = Comentario::where('employee_id', '=', $empleado_id)->get()->first();

        return view('incidencias.comment')
        ->with('empleado_id', $empleado_id)
        ->with('comentario', $comentario);

    }
    public function comentario_update(Request $request, $empleado_id)
    {
        $comentario = Comentario::where('employee_id', '=', $empleado_id)->first();

        $comentario->comment = $request->comment;
        $comentario->employee_id = $empleado_id;
        $comentario->save();

        $empleado = Employe::find($empleado_id);
        $qnas = Qna::where('active', '=', 1)->get();
        $dptos = \Auth::user()->centros->pluck('id')->toArray();
        $query = $empleado->num_empleado;
        $empleado = Employe::getEmpleadoSearch($query, $dptos);
        $ids = ['30','56','57','58','59','60','61','62','63','64','66','67','68', '101'];
        $medicos = Employe::orderBy('father_lastname', 'ASC')->whereIn('deparment_id', $dptos)->whereIn('puesto_id', $ids)->get();
        $periodos = Periodo::orderBy('year', 'desc')->orderBy('periodo', 'desc')->get();
        $codigosdeincidencias = Codigo_De_Incidencia::all()->pluck('codigo', 'id')->toArray();
        asort($codigosdeincidencias);
        $incidencias = Incidencia::getIncidencias($empleado->num_empleado);
        $comentario = Comentario::where('employee_id', '=', $empleado->emp_id)->get()->first();

        return view('incidencias.create')
            ->with('incidencias', $incidencias)
            ->with('employee', $empleado)
            ->with('qnas', $qnas)
            ->with('periodos', $periodos)
            ->with('codigosdeincidencias', $codigosdeincidencias)
            ->with('medicos', $medicos)
            ->with('comment', $comentario);

    }
    public function comentario_store(Request $request, $empleado_id)
    {
        //$comentario = Comentario::where('employee_id', '=', $empleado_id)->get()->first();
        $comentario = new Comentario;
        $comentario->comment = $request->comment;
        $comentario->employee_id = $empleado_id;
        $comentario->save();

        $empleado = Employe::find($empleado_id);
        $qnas = Qna::where('active', '=', 1)->get();
        $dptos = \Auth::user()->centros->pluck('id')->toArray();
        $query = $empleado->num_empleado;
        $empleado = Employe::getEmpleadoSearch($query, $dptos);
        $ids = ['30','56','57','58','59','60','61','62','63','64','66','67','68', '101'];
        $medicos = Employe::orderBy('father_lastname', 'ASC')->whereIn('deparment_id', $dptos)->whereIn('puesto_id', $ids)->get();
        $periodos = Periodo::orderBy('year', 'desc')->orderBy('periodo', 'desc')->get();
        $codigosdeincidencias = Codigo_De_Incidencia::all()->pluck('codigo', 'id')->toArray();
        asort($codigosdeincidencias);
        $incidencias = Incidencia::getIncidencias($empleado->num_empleado);
        $comentario = Comentario::where('employee_id', '=', $empleado->emp_id)->get()->first();

        return view('incidencias.create')
            ->with('incidencias', $incidencias)
            ->with('employee', $empleado)
            ->with('qnas', $qnas)
            ->with('periodos', $periodos)
            ->with('codigosdeincidencias', $codigosdeincidencias)
            ->with('medicos', $medicos)
            ->with('comment', $comentario);

    }

    public function logs(Request $request) {
        $incidencias = Incidencia::Getlogs($limit = 100);
        //dd($incidencias);
        return view('reportes.logs.index')->with('incidencias', $incidencias);
    }


    /*    VALIDACIONES DE INCIDENCIAS   */
    /*                                  */

    /* Validando pases de saldida */

    public function valPaseDeSalida($qna, $employee_id){
        $pase = Incidencia::where('qna_id', '=', $qna->id)
                ->where('codigodeincidencia_id', '=',41)
                ->where('employee_id','=', $employee_id)
                ->count();
        if($pase) return true;
    }

    public function yaCapturado($employee_id, $fecha_inicial, $fecha_final){
        $ya_capturado = Incidencia::where('employee_id', '=', $employee_id)
            ->whereRaw('? between fecha_inicio and fecha_final', [$fecha_inicial])
            ->count();

        $ya_existe = Incidencia::where('employee_id',$employee_id)
            ->whereBetween('fecha_inicio',[$fecha_inicial,$fecha_final])
            ->count();

        if ($ya_capturado || $ya_existe )  return true;



    }

    /*
    use DateTime;
    use DateInterval;
    use DatePeriod;
    function createDateRange($startDate, $endDate, $format = "Y-m-d") {
        $begin = new DateTime($startDate);
        $end = new DateTime($endDate);

        $interval = new DateInterval('P1D'); // 1 Day
        $dateRange = new DatePeriod($begin, $interval, $end);

        $range = [];
        foreach ($dateRange as $date) {
            $range[] = $date->format($format);
        }

        return $range;
    }
    */


}
