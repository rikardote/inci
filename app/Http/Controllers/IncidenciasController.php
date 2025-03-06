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
    // Validar mantenimiento
    $mantenimiento = $this->validarMantenimiento();
    if ($mantenimiento !== true) {
        return response()->json($mantenimiento['message'], 500);
    }

    date_default_timezone_set('UTC');

    // Obtener fechas
    list($fecha_inicio, $fecha_final) = $this->obtenerFechas($request);

    // Buscar empleado
    $empleado = Employe::find($request->empleado_id);
    if (!$empleado) {
        return response()->json('Empleado no encontrado', 500);
    }

    // Generar fechas por quincena
    $grupos = $this->generarFechasPorQna($fecha_inicio, $fecha_final);
    $fecha_expedida = $request->datepicker_expedida ? fecha_ymd($request->datepicker_expedida) : null;

    foreach ($grupos as $grupo) {
        // Preparar incidencia
        $incidencia = $this->prepararIncidencia($request, $grupo, $fecha_expedida);

        // Buscar código de incidencia
        $codigo = Codigo_De_Incidencia::where('id', $request->codigo)->first();
        if (!$codigo) {
            return response()->json('Código de incidencia no encontrado', 500);
        }

        // Validar incidencia
        $validacion = $this->validarIncidencia($incidencia, $empleado, $codigo, $request);
        if ($validacion !== true) {
            return response()->json($validacion['message'], 500);
        }

        // Ajustar total_dias
        $incidencia->total_dias = $this->ajustarTotalDias($incidencia, $empleado, $codigo, $request);

        // Guardar incidencia
        $incidencia->save();
    }

    // Obtener incidencias del empleado
    $incidencias = Incidencia::getIncidencias($empleado->num_empleado);

    if ($request->ajax()) {
        return response()->json($incidencias, 200);
    }
}
private function validarMantenimiento()
{
    if (check_manto() && !\Auth::user()->admin()) {
        return ['message' => 'El sistema está en periodo de mantenimiento... intentar más tarde'];
    }
    return true;
}
private function obtenerFechas(Request $request)
{
    if ($request->qna_id != 0) {
        $fecha_inicio = getFechaInicioPorQna($request->qna_id);
        $fecha_final = getFechaFinalPorQna($fecha_inicio);
    } else {
        $fecha_inicio = fecha_ymd($request->datepicker_inicial);
        $fecha_final = fecha_ymd($request->datepicker_final);
    }
    return [$fecha_inicio, $fecha_final];
}
private function generarFechasPorQna($fecha_inicio, $fecha_final) { // Validar que las fechas sean válidas
    $inicioTimestamp = strtotime($fecha_inicio);
    $finTimestamp = strtotime($fecha_final);
    if ($inicioTimestamp === false || $finTimestamp === false) {
        return []; // Retorna vacío si las fechas son inválidas
    }
// Asegurarse de que inicio sea menor o igual a fin
    if ($inicioTimestamp > $finTimestamp) {
        return [];
    }
    $fechas = []; $unDiaEnSegundos = 86400; // Iterar sobre el rango de fechas
    for ($timestamp = $inicioTimestamp; $timestamp <= $finTimestamp; $timestamp += $unDiaEnSegundos) {
        $fecha = date('Y-m-d', $timestamp); $quincena = qna_year($fecha); $fechas[] = [ 'fecha' => $fecha, 'qna' => $quincena ];
    }
    // Agrupar por quincena
    return array_group_by($fechas, 'qna');
}
private function prepararIncidencia(Request $request, $grupoFechas, $fecha_expedida)
{
    $incidencia = new Incidencia($request->all());
    $incidencia->employee_id = $request->empleado_id;
    $incidencia->codigodeincidencia_id = $request->codigo;

    $fecha_inicial = reset($grupoFechas)['fecha'];
    $fecha_final = end($grupoFechas)['fecha'];

    $start = Carbon::parse($fecha_inicial);
    $end = Carbon::parse($fecha_final)->addDay();
    $total_dias = $start->diffInDays($end);

    $incidencia->qna_id = qna_year($start);
    $incidencia->fecha_inicio = $fecha_inicial;
    $incidencia->fecha_final = $fecha_final;
    $incidencia->token = genToken();
    $incidencia->total_dias = $total_dias;
    $incidencia->fecha_expedida = $fecha_expedida;
    $incidencia->capturado_por = capturado_por(\Auth::user()->id);
    $incidencia->fecha_capturado = Carbon::now();

    return $incidencia;
}
private function validarIncidencia($incidencia, $empleado, $codigo, Request $request)
{
    $qna = Qna::find($incidencia->qna_id);
    if (!$qna) {
        return ['message' => 'Quincena no activada'];
    }

    if ($codigo->code == 912) {
        return ['message' => 'Código 912 ya no se encuentra activo, acude a Recursos Humanos'];
    }

    if ($codigo->code == 905) {
        if ($empleado->condicion_id != 1) {
            return ['message' => 'Pases de salida solo válidos para el personal de BASE'];
        }
        if ($this->valPaseDeSalida($qna, $incidencia->employee_id)) {
            return ['message' => "Pase de salida de QNA: {$qna->qna}-{$qna->year} ya gozado"];
        }
    }

    $incidencias_duplicados = [1, 18, 19, 2, 3, 4, 7, 92, 905, 30];
    if ($this->yaCapturado($incidencia->employee_id, $incidencia->fecha_inicio, $incidencia->fecha_final) && !in_array($codigo->code, $incidencias_duplicados)) {
        return ['message' => 'Ya existe una incidencia este día o período'];
    }

    if (in_array($codigo->code, [60, 62, 63])) {
        if (!$request->periodo_id) {
            return ['message' => 'Ingrese Periodo Vacacional'];
        }
        if ($request->saltar_validacion != 'true') {
            $validacionVacaciones = $this->validarVacaciones($incidencia, $empleado, $request);
            if ($validacionVacaciones !== true) {
                return $validacionVacaciones;
            }
        }
    }

    return true;
}

private function validarVacaciones($incidencia, $empleado, Request $request)
{
    $vacaciones = Incidencia::getTotalVacaciones($incidencia->employee_id, $incidencia->periodo_id, $incidencia->codigodeincidencia_id);
    $vacacion = Periodo::find($incidencia->periodo_id);
    $total_dias = $incidencia->total_dias;

    $jornadas = [
        'de2' => [1, 15],
        'de5' => [2, 3, 5, 6, 18, 13, 20, 30, 32],
        'de6' => [4, 7, 8, 9, 10, 11, 19, 31],
        'demas' => [14, 17]
    ];

    if (($vacaciones + $total_dias) > 2 && in_array($empleado->jornada_id, $jornadas['de2'])) {
        return ['message' => "Error {$vacaciones} de 2 días vacacionales período {$vacacion->periodo}-{$vacacion->year}"];
    }
    if (($vacaciones + $total_dias) > 5 && in_array($empleado->jornada_id, $jornadas['de5'])) {
        return ['message' => "Error {$vacaciones} de 5 días vacacionales período {$vacacion->periodo}-{$vacacion->year}"];
    }
    if (($vacaciones + $total_dias) > 6 && in_array($empleado->jornada_id, $jornadas['de6'])) {
        return ['message' => "Error {$vacaciones} de 6 días vacacionales período {$vacacion->periodo}-{$vacacion->year}"];
    }
    if (($vacaciones + $total_dias) > 10 && in_array($empleado->jornada_id, $jornadas['demas'])) {
        return ['message' => "Error {$vacaciones} de 10 días vacacionales período {$vacacion->periodo}-{$vacacion->year}"];
    }

    return true;
}
private function ajustarTotalDias($incidencia, $empleado, $codigo, Request $request)
{
    $guardias = [2, 3, 5, 6, 18, 13, 20, 4, 7, 8, 9, 10, 11, 19, 21, 22, 23, 24, 25, 26, 27, 28, 29, 30, 31, 32];
    $syf_dyf = [1, 15];

    $codigos_ajustables = [10, 55, 40, 41, 47, 48, 49, 29, 92];
    if (in_array($codigo->code, $codigos_ajustables) && $request->saltar_validacion_lic != 'true') {
        if (in_array($empleado->jornada_id, $guardias)) {
            return 2;
        }
        if (in_array($empleado->jornada_id, $syf_dyf)) {
            return 4;
        }
    }
    return $incidencia->total_dias;
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
