<?php

namespace App;

use DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Incidencia extends Model
{
    use SoftDeletes;

    protected $dates = ['incidencias.deleted_at'];
    protected $table = 'incidencias';

    protected $fillable = ['qna_id', 'employee_id', 'fecha_inicio', 'fecha_final', 'codigodeincidencia_id', 'periodo_id', 'token', 'diagnostico', 'medico_id', 'fecha_expedida', 'num_licencia', 'otorgado', 'pendientes', 'becas_comments', 'fecha_capturado', 'cobertura_txt', 'horas_otorgadas'];

    public function employee()
    {
      return $this->belongsTo('App\employe');
    }
    public function qna()
    {
      return $this->belongsTo('App\qna');
    }
    public function codigodeincidencia()
    {
        return $this->belongsTo('App\Codigo_De_Incidencia');
    }
     public function periodo()
    {
      return $this->belongsTo('App\periodo');
    }
    public function setPeriodoIdAttribute($value)
    {
      $this->attributes['periodo_id'] = $value ?: null;
    }
      public function getQnaAttribute($value)
    {
        return str_pad($value, 2, '0', STR_PAD_LEFT);
    }
    public function setdiagnosticoAttribute($value)
    {
        $this->attributes['diagnostico'] = strtoupper($value);
    }
    public function setcoberturatxtAttribute($value)
    {
        $this->attributes['cobertura_txt'] = strtoupper($value);
    }
    public function setotorgadoAttribute($value)
    {
        $this->attributes['otorgado'] = strtoupper($value);
    }
    public function setpendientesAttribute($value)
    {
        $this->attributes['pendientes'] = strtoupper($value);
    }
    public function setnumlicenciaAttribute($value)
    {
        $this->attributes['num_licencia'] = strtoupper($value);
    }
    public function sethorasotorgadasAttribute($value)
    {
        $this->attributes['horas_otorgadas'] = strtoupper($value);
    }


    public static function getIncidencias($num_empleado)
    {
      $conteo_total = DB::raw('SUM(total_dias) as total');
      $incidencias = Incidencia::getQuery()
                 ->select('*','employees.id as empleado_id','incidencias.id as inc_id' ,'qnas.year as qna_year','periodos.year as periodo_year','periodos.periodo as periodo_p', DB::raw($conteo_total))
                 ->leftJoin('employees', 'employees.id', '=', 'incidencias.employee_id')
                 ->leftJoin('qnas', 'qnas.id', '=', 'incidencias.qna_id')
                 ->leftJoin('periodos', 'periodos.id', '=', 'incidencias.periodo_id')
                 ->leftJoin('codigos_de_incidencias', 'codigos_de_incidencias.id', '=', 'incidencias.codigodeincidencia_id')
                 ->whereNull('incidencias.deleted_at')
                 ->where('qnas.active', '=', 1)
                 ->where('employees.num_empleado', $num_empleado)
                 ->groupBy('token')
                 ->orderBy('qna_id', 'ASC')
                 ->orderBy('num_empleado', 'ASC')
                 //->orderBy('codigos_de_incidencias.code', 'ASC')
                 ->orderBy('fecha_inicio', 'ASC')
                 ->get();
     return $incidencias;

    }

    public static function getIncidenciasCentro($qna_id, $dpto_id)
    {
      $conteo_total = DB::raw('SUM(total_dias) as total');
      $incidencias = Incidencia::getQuery()
                 ->select('*','employees.id as empleado_id','incidencias.id as inc_id' ,'qnas.year as qna_year','periodos.year as periodo_year','periodos.periodo as periodo_p', DB::raw($conteo_total))
                 ->leftJoin('employees', 'employees.id', '=', 'incidencias.employee_id')
                 ->leftjoin('deparments', 'deparments.id', '=', 'employees.deparment_id')
                 ->leftJoin('qnas', 'qnas.id', '=', 'incidencias.qna_id')
                 ->leftJoin('periodos', 'periodos.id', '=', 'incidencias.periodo_id')
                 ->leftJoin('codigos_de_incidencias', 'codigos_de_incidencias.id', '=', 'incidencias.codigodeincidencia_id')
                 //->whereNotIn('codigos_de_incidencias.code', [900,901, 902, 903, 904, 905])
                 ->whereNull('incidencias.deleted_at')
                 ->whereNotIn('codigos_de_incidencias.code', [900])
                 ->where('qna_id', $qna_id)
                 ->where('deparments.id', '=', $dpto_id)
                 ->groupBy('token')
                 ->orderBy('num_empleado', 'ASC')
                 ->orderBy('codigos_de_incidencias.code', 'ASC')
                 ->orderBy('fecha_inicio', 'ASC')
                 ->get();
     return $incidencias;
    }
    public static function getIncidenciasCentroPDF($qna_id, $dpto_id)
    {
      $conteo_total = DB::raw('SUM(total_dias) as total');
      $incidencias = Incidencia::getQuery()
                 ->select('*','employees.id as empleado_id','incidencias.id as inc_id' ,'qnas.year as qna_year','periodos.year as periodo_year','periodos.periodo as periodo_p', DB::raw($conteo_total))
                 ->leftJoin('employees', 'employees.id', '=', 'incidencias.employee_id')
                 ->leftjoin('deparments', 'deparments.id', '=', 'employees.deparment_id')
                 ->leftJoin('qnas', 'qnas.id', '=', 'incidencias.qna_id')
                 ->leftJoin('periodos', 'periodos.id', '=', 'incidencias.periodo_id')
                 ->leftJoin('codigos_de_incidencias', 'codigos_de_incidencias.id', '=', 'incidencias.codigodeincidencia_id')
                 ->whereNull('incidencias.deleted_at')
                 ->whereNotIn('codigos_de_incidencias.code', [900, 902, 903, 904])
                 ->where('qna_id', $qna_id)
                 ->where('deparments.id', '=', $dpto_id)
                 ->groupBy('token')
                 ->orderBy('num_empleado', 'ASC')
                 ->orderBy('codigos_de_incidencias.code', 'ASC')
                 ->orderBy('fecha_inicio', 'ASC')
                 ->get();
     return $incidencias;
    }
    public static function getIncidenciasCentroPDF_diario($qna_id, $dpto_id)
    {
      $conteo_total = DB::raw('SUM(total_dias) as total');
      $incidencias = Incidencia::getQuery()
                 ->select('*','employees.id as empleado_id','incidencias.id as inc_id' ,'qnas.year as qna_year','periodos.year as periodo_year','periodos.periodo as periodo_p', DB::raw($conteo_total))
                 ->leftJoin('employees', 'employees.id', '=', 'incidencias.employee_id')
                 ->leftjoin('deparments', 'deparments.id', '=', 'employees.deparment_id')
                 ->leftJoin('qnas', 'qnas.id', '=', 'incidencias.qna_id')
                 ->leftJoin('periodos', 'periodos.id', '=', 'incidencias.periodo_id')
                 ->leftJoin('codigos_de_incidencias', 'codigos_de_incidencias.id', '=', 'incidencias.codigodeincidencia_id')
                 ->whereNull('incidencias.deleted_at')
                 ->where('qna_id', $qna_id)
                 ->where('deparments.id', '=', $dpto_id)
                 ->groupBy('token')
                 ->orderBy('num_empleado', 'ASC')
                 ->orderBy('codigos_de_incidencias.code', 'ASC')
                 ->orderBy('fecha_inicio', 'ASC')
                 ->get();
     return $incidencias;
    }
    public static function getIncidenciasCentroCapturar($qna_id, $dpto_id)
    {
      $conteo_total = DB::raw('SUM(total_dias) as total');
      $incidencias = Incidencia::getQuery()
                 ->select('*','employees.id as empleado_id','incidencias.id as inc_id' ,'qnas.year as qna_year','periodos.year as periodo_year','periodos.periodo as periodo_p', DB::raw($conteo_total))
                 ->leftJoin('employees', 'employees.id', '=', 'incidencias.employee_id')
                 ->leftjoin('deparments', 'deparments.id', '=', 'employees.deparment_id')
                 ->leftJoin('qnas', 'qnas.id', '=', 'incidencias.qna_id')
                 ->leftJoin('periodos', 'periodos.id', '=', 'incidencias.periodo_id')
                 ->leftJoin('codigos_de_incidencias', 'codigos_de_incidencias.id', '=', 'incidencias.codigodeincidencia_id')
                 ->whereNull('incidencias.deleted_at')
                 ->whereNotBetween('codigos_de_incidencias.code', [84, 905])
                 ->where('qna_id', $qna_id)
                 ->where('deparments.id', '=', $dpto_id)
                 ->groupBy('token')
                 ->orderBy('num_empleado', 'ASC')
                 ->orderBy('codigos_de_incidencias.code', 'ASC')
                 ->orderBy('fecha_inicio', 'ASC')
                 ->get();
     return $incidencias;
    }
    public static function getIncidenciasPendientesCentroCapturar($qna_id, $dpto_id)
    {
      $conteo_total = DB::raw('SUM(total_dias) as total');
      $incidencias = Incidencia::getQuery()
                 ->select('*','employees.id as empleado_id','incidencias.id as inc_id' ,'qnas.year as qna_year','periodos.year as periodo_year','periodos.periodo as periodo_p', DB::raw($conteo_total))
                 ->leftJoin('employees', 'employees.id', '=', 'incidencias.employee_id')
                 ->leftjoin('deparments', 'deparments.id', '=', 'employees.deparment_id')
                 ->leftJoin('qnas', 'qnas.id', '=', 'incidencias.qna_id')
                 ->leftJoin('periodos', 'periodos.id', '=', 'incidencias.periodo_id')
                 ->leftJoin('codigos_de_incidencias', 'codigos_de_incidencias.id', '=', 'incidencias.codigodeincidencia_id')
                 ->whereNull('incidencias.deleted_at')
                 ->whereNotBetween('codigos_de_incidencias.code', [84, 905])
                 ->where('qna_id', $qna_id)
                 ->where('deparments.id', '=', $dpto_id)
                // ->where('capturada', '!=', 1)
                 ->groupBy('deparment_id')
                 ->orderBy('num_empleado', 'ASC')
                 ->orderBy('codigos_de_incidencias.code', 'ASC')
                 ->orderBy('fecha_inicio', 'ASC')
                 ->first();
     return $incidencias;
    }
    public static function getIncidenciasEmpleado($fecha_inicio, $fecha_final, $num_empleado)
    {
      $conteo_total = DB::raw('SUM(total_dias) as total');
      $incidencias = Incidencia::getQuery()
                 ->select('*','employees.id as empleado_id','incidencias.id as inc_id' ,'qnas.year as qna_year','periodos.year as periodo_year','periodos.periodo as periodo_p', DB::raw($conteo_total))
                 ->leftJoin('employees', 'employees.id', '=', 'incidencias.employee_id')
                 //->leftJoin('jornadas', 'jornadas.id', '=', 'employees.jornada_id')
                 //->leftJoin('horarios', 'horarios.id', '=', 'employees.horario_id')
                 ->leftjoin('deparments', 'deparments.id', '=', 'employees.deparment_id')
                 ->leftJoin('qnas', 'qnas.id', '=', 'incidencias.qna_id')
                 ->leftJoin('periodos', 'periodos.id', '=', 'incidencias.periodo_id')
                 ->leftJoin('codigos_de_incidencias', 'codigos_de_incidencias.id', '=', 'incidencias.codigodeincidencia_id')
                 ->whereNull('incidencias.deleted_at')
                 ->where('employees.num_empleado','=', $num_empleado)
                 ->whereBetween('fecha_inicio',[$fecha_inicio,$fecha_final])
                 ->groupBy('token')
                 ->orderBy('num_empleado', 'ASC')
                 ->orderBy('codigos_de_incidencias.code', 'ASC')
                 ->orderBy('fecha_inicio', 'ASC')
                 ->get();
     return $incidencias;
    }
    public static function getIncidenciasEmpleadoKardex($num_empleado) //kardex todo
    {
      $conteo_total = DB::raw('SUM(total_dias) as total');
      $incidencias = Incidencia::getQuery()
                 ->select('*','employees.id as empleado_id','incidencias.id as inc_id' ,'qnas.year as qna_year','periodos.year as periodo_year','periodos.periodo as periodo_p', DB::raw($conteo_total))
                 ->leftJoin('employees', 'employees.id', '=', 'incidencias.employee_id')
                 ->leftjoin('deparments', 'deparments.id', '=', 'employees.deparment_id')
                 ->leftJoin('qnas', 'qnas.id', '=', 'incidencias.qna_id')
                 ->leftJoin('periodos', 'periodos.id', '=', 'incidencias.periodo_id')
                 ->leftJoin('codigos_de_incidencias', 'codigos_de_incidencias.id', '=', 'incidencias.codigodeincidencia_id')
                 ->whereNull('incidencias.deleted_at')
                 ->where('employees.num_empleado','=', $num_empleado)
                 ->groupBy('token')
                 //->orderBy('num_empleado', 'ASC')
                 //->orderBy('codigos_de_incidencias.code', 'ASC')
                 ->orderBy('fecha_inicio', 'ASC')
                 ->get();
     return $incidencias;
    }
     public static function noDerecho_inc($dpto, $fecha_inicio, $fecha_final, $inc)
    {
      $conteo_total = DB::raw('SUM(total_dias) AS count');

      $incidencias = Incidencia::getQuery()
                 ->select('*','employees.id as empleado_id', DB::raw($conteo_total))
                 //->select('*','employees.id as empleado_id')
                 ->leftJoin('employees', 'employees.id', '=', 'incidencias.employee_id')
                 ->leftjoin('deparments', 'deparments.id', '=', 'employees.deparment_id')
                 ->leftjoin('puestos', 'puestos.id', '=', 'employees.puesto_id')
                 ->leftJoin('qnas', 'qnas.id', '=', 'incidencias.qna_id')
                 ->leftJoin('periodos', 'periodos.id', '=', 'incidencias.periodo_id')
                 ->leftJoin('codigos_de_incidencias', 'codigos_de_incidencias.id', '=', 'incidencias.codigodeincidencia_id')
                 ->whereNull('incidencias.deleted_at')
                 ->where('deparments.code', '=', $dpto)
                 ->where('employees.condicion_id', '=', 1)
                 ->whereIn('codigos_de_incidencias.code', $inc)
                 ->whereBetween('fecha_inicio',[$fecha_inicio,$fecha_final])
                 ->groupBy('num_empleado')
                 ->get();
     return $incidencias;
    }
    public static function noDerecho_lic($dpto, $fecha_inicio, $fecha_final, $lic)
    {
      $conteo_total = DB::raw('SUM(total_dias) AS count');

      $incidencias = Incidencia::getQuery()
                 ->select('*','employees.id as empleado_id', DB::raw($conteo_total))
                 //->select('*','employees.id as empleado_id')
                 ->leftJoin('employees', 'employees.id', '=', 'incidencias.employee_id')
                 ->leftjoin('deparments', 'deparments.id', '=', 'employees.deparment_id')
                 ->leftjoin('puestos', 'puestos.id', '=', 'employees.puesto_id')
                 ->leftJoin('qnas', 'qnas.id', '=', 'incidencias.qna_id')
                 ->leftJoin('periodos', 'periodos.id', '=', 'incidencias.periodo_id')
                 ->leftJoin('codigos_de_incidencias', 'codigos_de_incidencias.id', '=', 'incidencias.codigodeincidencia_id')
                 ->whereNull('incidencias.deleted_at')
                 ->where('deparments.code', '=', $dpto)
                 ->where('employees.condicion_id', '=', 1)
                 ->whereIn('codigos_de_incidencias.code', $lic)
                 ->whereBetween('fecha_inicio',[$fecha_inicio,$fecha_final])
                 ->groupBy('num_empleado')
                 ->having('count', '>', 3)
                 ->get();
     return $incidencias;
    }
    public static function getIncidenciasQna($num_empleado, $qna_id)
    {
      $conteo_total = DB::raw('SUM(total_dias) as total');
      $incidencias = Incidencia::getQuery()
                 ->select('*','employees.id as empleado_id','incidencias.id as inc_id' ,'qnas.year as qna_year','periodos.year as periodo_year','periodos.periodo as periodo_p', DB::raw($conteo_total))
                 ->leftJoin('employees', 'employees.id', '=', 'incidencias.employee_id')
                 ->leftJoin('qnas', 'qnas.id', '=', 'incidencias.qna_id')
                 ->leftJoin('periodos', 'periodos.id', '=', 'incidencias.periodo_id')
                 ->leftJoin('codigos_de_incidencias', 'codigos_de_incidencias.id', '=', 'incidencias.codigodeincidencia_id')
                 ->whereNull('incidencias.deleted_at')
                 ->where('qna_id', '=', $qna_id)
                 ->where('employees.condicion_id', '=', 1)
                 ->where('employees.num_empleado', $num_empleado)
                 ->groupBy('token')
                 ->orderBy('num_empleado', 'ASC')
                 ->orderBy('codigos_de_incidencias.code', 'ASC')
                 ->orderBy('fecha_inicio', 'ASC')

                 ->get();
     return $incidencias;
    }

    public static function getTotalIncidenciasPorConceptoDel($fecha_inicial, $fecha_final, $code)
    {
      $conteo_total = DB::raw('SUM(total_dias) AS count');
      $incidencias = Incidencia::getQuery()
                 ->select(DB::raw($conteo_total))
                 ->leftJoin('codigos_de_incidencias', 'codigos_de_incidencias.id', '=', 'incidencias.codigodeincidencia_id')
                 ->where('codigos_de_incidencias.code', '=', $code)
                 ->whereNull('incidencias.deleted_at')
                 ->whereBetween('fecha_inicio',[$fecha_inicial,$fecha_final])
                 ->first();
     return $incidencias;
    }

    public static function getTotalIncidenciasPorCentro($fecha_inicial, $fecha_final, $dpto_id, $code)
    {
      $conteo_total = DB::raw('SUM(total_dias) AS count');
      $incidencias = Incidencia::getQuery()
                 ->select(DB::raw($conteo_total))
                 ->leftJoin('employees', 'employees.id', '=', 'incidencias.employee_id')
                 ->leftjoin('deparments', 'deparments.id', '=', 'employees.deparment_id')
                 ->leftJoin('codigos_de_incidencias', 'codigos_de_incidencias.id', '=', 'incidencias.codigodeincidencia_id')
                 ->whereNull('incidencias.deleted_at')
                 ->where('deparments.id', '=', $dpto_id)
                 ->where('codigos_de_incidencias.code', '=', $code)
                 ->whereBetween('fecha_inicio',[$fecha_inicial,$fecha_final])
                 ->first();
     return $incidencias;
    }
    public static function getIncidenciasByCodeAndDate($codigo, $fecha_inicial, $fecha_final, $dpto_id)
    {
      $conteo_total = DB::raw('SUM(total_dias) as total');
      $incidencias = Incidencia::getQuery()
                 ->select('*','employees.id as empleado_id','incidencias.id as inc_id' ,'deparments.id as dep_id', 'deparments.code as dep_code','deparments.description as dep_desc', DB::raw($conteo_total))
                 ->leftJoin('employees', 'employees.id', '=', 'incidencias.employee_id')
                 ->leftjoin('deparments', 'deparments.id', '=', 'employees.deparment_id')
                 ->leftJoin('codigos_de_incidencias', 'codigos_de_incidencias.id', '=', 'incidencias.codigodeincidencia_id')
                 ->whereNull('incidencias.deleted_at')
                 ->where('deparments.id', $dpto_id)
                 ->where('codigos_de_incidencias.code', '=', $codigo)
                 ->whereBetween('fecha_inicio',[$fecha_inicial,$fecha_final])
                 ->groupBy('employee_id')
                 ->get();
     return $incidencias;
    }

    public static function getTotalIncidenciasPorEmpleado($fecha_inicial, $fecha_final, $dpto_id, $num_empleado, $code)
    {
      $conteo_total = DB::raw('SUM(total_dias) AS count');
      $incidencias = Incidencia::getQuery()
                 ->select(DB::raw($conteo_total))
                 ->leftJoin('employees', 'employees.id', '=', 'incidencias.employee_id')
                 ->leftjoin('deparments', 'deparments.id', '=', 'employees.deparment_id')
                 ->leftJoin('codigos_de_incidencias', 'codigos_de_incidencias.id', '=', 'incidencias.codigodeincidencia_id')
                 //->where('deparments.id', '=', $dpto_id)
                 ->whereNull('incidencias.deleted_at')
                 ->where('employees.num_empleado', '=', $num_empleado)
                 ->where('codigos_de_incidencias.code', '=', $code)
                 ->whereBetween('fecha_inicio',[$fecha_inicial,$fecha_final])
                 ->first();
     return $incidencias;
    }
    public static function getAllIncidenciasCentro($qna_id)
    {
      $conteo_total = DB::raw('SUM(total_dias) as total');
      $incidencias = Incidencia::getQuery()
                 ->select('*','employees.id as empleado_id','incidencias.id as inc_id' ,'deparments.id as dep_id', 'deparments.code as dep_code','deparments.description as dep_desc','qnas.year as qna_year', DB::raw($conteo_total))
                 ->leftJoin('employees', 'employees.id', '=', 'incidencias.employee_id')
                 ->leftjoin('deparments', 'deparments.id', '=', 'employees.deparment_id')
                 ->leftJoin('qnas', 'qnas.id', '=', 'incidencias.qna_id')
                 ->whereNull('incidencias.deleted_at')
                 ->where('qna_id', $qna_id)
                 ->groupBy('deparments.id')
                 ->get();
     return $incidencias;
    }
    public static function getAllIncidenciasPorGrupo($qna_id, $dpto_code, $grupo)
    {
      $conteo_total = DB::raw('SUM(total_dias) as total');
      $incidencias = Incidencia::getQuery()
                 ->select('*','employees.id as empleado_id','incidencias.id as inc_id', 'periodos.year as periodo_year', 'deparments.id as dep_id', 'deparments.code as dep_code','deparments.description as dep_desc','qnas.year as qna_year',DB::raw($conteo_total))
                 ->leftJoin('employees', 'employees.id', '=', 'incidencias.employee_id')
                 ->leftjoin('deparments', 'deparments.id', '=', 'employees.deparment_id')
                 ->leftJoin('codigos_de_incidencias', 'codigos_de_incidencias.id', '=', 'incidencias.codigodeincidencia_id')
                 ->leftJoin('qnas', 'qnas.id', '=', 'incidencias.qna_id')
                 ->leftJoin('periodos', 'periodos.id', '=', 'incidencias.periodo_id')
                 ->whereNull('incidencias.deleted_at')
                 ->where('qna_id', $qna_id)
                 ->where('deparments.code', $dpto_code)
                 ->where('codigos_de_incidencias.grupo', $grupo)
                 ->where('capturada','!=' ,1)
                 ->whereNotIn('codigos_de_incidencias.code', [900,901, 902, 903, 904, 905])
                 ->groupBy('token')
                 ->orderBy('employees.num_empleado')
                 ->orderBy('codigos_de_incidencias.code')
                 ->orderBy('fecha_inicio')
                 ->get();
     return $incidencias;
    }
    public static function getLicencias($fecha_inicio, $fecha_final, $dptos)
    {
      $conteo_total = DB::raw('sum(total_dias) as total , max(fecha_final) as fecha_fin');

      $incidencias = Incidencia::getQuery()
                 ->select('*','employees.id as empleado_id','incidencias.id as inc_id', 'deparments.id as dep_id', 'deparments.code as dep_code','deparments.description as dep_desc',$conteo_total)
                 ->leftJoin('employees', 'employees.id', '=', 'incidencias.employee_id')
                 ->leftjoin('deparments', 'deparments.id', '=', 'employees.deparment_id')
                 ->leftJoin('codigos_de_incidencias', 'codigos_de_incidencias.id', '=', 'incidencias.codigodeincidencia_id')
                 ->whereNull('incidencias.deleted_at')
                 ->whereIn('deparments.id', $dptos)
                 ->whereIn('codigos_de_incidencias.id', ['31','5','23'])
                 ->whereBetween('fecha_inicio',[$fecha_inicio,$fecha_final])
                 ->groupBy('num_licencia')
                 ->orderBy('employees.num_empleado')
                 ->orderBy('codigos_de_incidencias.code')
                 ->orderBy('fecha_inicio')
                 ->get();

     return $incidencias;
    }
    public static function getPendientes($qna_id, $dpto, $pendiente_id)
    {
      $conteo_total = DB::raw('SUM(total_dias) as total');
      $incidencias = Incidencia::getQuery()
                 ->select('*','employees.id as empleado_id','incidencias.id as inc_id' ,'deparments.id as dep_id', 'deparments.code as dep_code','deparments.description as dep_desc','qnas.year as qna_year', DB::raw($conteo_total))
                 ->leftJoin('employees', 'employees.id', '=', 'incidencias.employee_id')
                 ->leftJoin('codigos_de_incidencias', 'codigos_de_incidencias.id', '=', 'incidencias.codigodeincidencia_id')
                 ->leftjoin('deparments', 'deparments.id', '=', 'employees.deparment_id')
                 ->leftJoin('qnas', 'qnas.id', '=', 'incidencias.qna_id')
                 ->whereNull('incidencias.deleted_at')
                 ->where('qna_id', '=', $qna_id)
                 ->where('deparments.code', '=', $dpto)
                 ->where('codigodeincidencia_id', '=', $pendiente_id)
                 //->where('codigos_de_incidencias.grupo', '=', '500')
                 ->groupBy('token')
                 ->get();
     return $incidencias;
    }

    public static function getIncapacidades($fecha_actual, $fecha_posterior, $empleado_id)
    {
      $conteo_total = DB::raw('sum(total_dias) as total , max(fecha_ingreso) as fecha_fin');
      $incidencias = Incidencia::getQuery()
                 ->select('*',DB::raw($conteo_total))
                 ->leftJoin('employees', 'employees.id', '=', 'incidencias.employee_id')
                 ->leftjoin('deparments', 'deparments.id', '=', 'employees.deparment_id')
                 ->leftJoin('codigos_de_incidencias', 'codigos_de_incidencias.id', '=', 'incidencias.codigodeincidencia_id')
                 ->whereNull('incidencias.deleted_at')
                 ->where('employees.active','=', 1)
                 ->where('codigos_de_incidencias.id', '=', '31')
                 ->where('num_empleado', '=', $empleado_id)
                 ->whereBetween('fecha_inicio',[$fecha_actual,$fecha_posterior])
                 ->groupBy('num_empleado')
                 ->get();
     return $incidencias;
    }
    public static function getTotalLicencias($empleado_id, $fecha_inicio,$fecha_final)
    {
      $conteo_total = DB::raw('sum(total_dias) as total , max(fecha_ingreso) as fecha_fin');
      $incidencias = Incidencia::getQuery()
                 ->select('*',DB::raw($conteo_total))
                 ->leftJoin('employees', 'employees.id', '=', 'incidencias.employee_id')
                 ->leftjoin('deparments', 'deparments.id', '=', 'employees.deparment_id')
                 ->leftJoin('codigos_de_incidencias', 'codigos_de_incidencias.id', '=', 'incidencias.codigodeincidencia_id')
                 ->whereNull('incidencias.deleted_at')
                 ->where('codigos_de_incidencias.id', '=', '3')
                 ->where('num_empleado', '=', $empleado_id)
                 ->whereBetween('fecha_inicio',[$fecha_inicio,$fecha_final])
                 ->groupBy('num_empleado')
                 ->first();

        if ($incidencias) {
            return $incidencias->total;
        }
        else{
            return 0;
        }

    }

    public static function getTotalVacaciones($employee_id, $periodo_id, $tipo_vaca)
    {
      $conteo_total = DB::raw('sum(total_dias) as total');
      $incidencias = Incidencia::getQuery()
                 ->select('*',DB::raw($conteo_total))
                 ->whereNull('incidencias.deleted_at')
                 ->where('employee_id', '=', $employee_id)
                 ->where('codigodeincidencia_id', '=', $tipo_vaca)
                 ->where('periodo_id', '=', $periodo_id)
                 ->groupBy('periodo_id')
                 ->first();

        if ($incidencias) {
            return $incidencias->total;
            //dd($incidencias->total);
        }
        else{
            return 0;
        }


    }
    public static function getIncidenciasByCode($code, $fecha_inicio, $fecha_final, $dptos)
    {
      $conteo_total = DB::raw('SUM(total_dias) as total');
      $incidencias = Incidencia::getQuery()
                 ->select('*','employees.id as empleado_id','jornadas.id as jornada_id','incidencias.id as inc_id', DB::raw($conteo_total))
                 ->leftJoin('employees', 'employees.id', '=', 'incidencias.employee_id')
                 ->leftjoin('deparments', 'deparments.id', '=', 'employees.deparment_id')
                 ->leftJoin('codigos_de_incidencias', 'codigos_de_incidencias.id', '=', 'incidencias.codigodeincidencia_id')
                 ->leftJoin('jornadas', 'jornadas.id', '=', 'employees.jornada_id')
                 ->whereNull('incidencias.deleted_at')
                 ->where('codigos_de_incidencias.code', $code)
                 ->whereIn('deparments.id', $dptos)
                 ->whereBetween('fecha_inicio',[$fecha_inicio,$fecha_final])
                 ->groupBy('employees.jornada_id')
                 ->get();
     return $incidencias;
    }
    public static function getIncidenciasByCode2($code, $fecha_inicio, $fecha_final, $dpto)
    {
      $conteo_total = DB::raw('SUM(total_dias) as total');
      $incidencias = Incidencia::getQuery()
                 ->select('*','employees.id as empleado_id','incidencias.id as inc_id','jornadas.id as jornada_id','puestos.puesto as puesto_p', DB::raw($conteo_total))
                 ->leftJoin('employees', 'employees.id', '=', 'incidencias.employee_id')
                 ->leftjoin('deparments', 'deparments.id', '=', 'employees.deparment_id')
                 ->leftJoin('codigos_de_incidencias', 'codigos_de_incidencias.id', '=', 'incidencias.codigodeincidencia_id')
                 ->leftJoin('puestos', 'puestos.id', '=', 'employees.puesto_id')
                 ->leftJoin('jornadas', 'jornadas.id', '=', 'employees.jornada_id')
                 ->whereNull('incidencias.deleted_at')
                 ->where('deparments.id', '=', $dpto)
                 ->where('codigos_de_incidencias.code', $code)
                 ->whereBetween('fecha_inicio',[$fecha_inicio,$fecha_final])
                 ->groupBy('num_empleado')
                 ->get();
     return $incidencias;
    }
    public static function Gettxtpormes($fecha_inicio, $fecha_final, $num_empleado)
    {
      $conteo_total = DB::raw('sum(total_dias) as total , max(fecha_final) as fecha_final');
      $incidencias = Incidencia::getQuery()
                 ->select('*',DB::raw($conteo_total))
                 ->leftJoin('employees', 'employees.id', '=', 'incidencias.employee_id')
                 ->leftJoin('codigos_de_incidencias', 'codigos_de_incidencias.id', '=', 'incidencias.codigodeincidencia_id')
                 ->whereNull('incidencias.deleted_at')
                 ->where('codigos_de_incidencias.code', '=', '900')
                 ->where('num_empleado', '=', $num_empleado)
                 ->whereBetween('fecha_inicio',[$fecha_inicio,$fecha_final])
                 ->groupBy('num_empleado')
                 ->first();
                 //dd($incidencias);
                 if ($incidencias) {
                     return $incidencias->total;
                 }
                 else return 0;

    }
    public static function GetIncidenciasPorDia($dptos, $fecha_inicio)
    {

        $conteo_total = DB::raw('SUM(total_dias) as total');
          $incidencias = Incidencia::getQuery()
                     ->select('*','employees.id as empleado_id','incidencias.id as inc_id' ,'qnas.year as qna_year','periodos.year as periodo_year','periodos.periodo as periodo_p','puestos.puesto as puesto_p', DB::raw($conteo_total))
                     ->leftJoin('employees', 'employees.id', '=', 'incidencias.employee_id')
                     ->leftjoin('deparments', 'deparments.id', '=', 'employees.deparment_id')
                     ->leftJoin('qnas', 'qnas.id', '=', 'incidencias.qna_id')
                     ->leftJoin('puestos', 'puestos.id', '=', 'employees.puesto_id')
                     ->leftJoin('periodos', 'periodos.id', '=', 'incidencias.periodo_id')
                     ->leftJoin('codigos_de_incidencias', 'codigos_de_incidencias.id', '=', 'incidencias.codigodeincidencia_id')
                     ->whereNull('incidencias.deleted_at')
                     ->whereIn('deparments.id', $dptos)
                     ->where('fecha_capturado', '=', $fecha_inicio)
                     ->groupBy('token')
                     ->orderBy('deparments.code', 'ASC')
                     ->orderBy('num_empleado', 'ASC')
                     ->orderBy('codigos_de_incidencias.code', 'ASC')
                     ->orderBy('fecha_inicio', 'ASC')
                     ->get();
         return $incidencias;
    }
    public static function GetIncidenciasCapturaPorDia($dptos,$fecha_inicial)
    {

        $conteo_total = DB::raw('SUM(total_dias) as total');
          $incidencias = Incidencia::getQuery()
                     ->select('*','employees.id as empleado_id','incidencias.id as inc_id' ,'qnas.year as qna_year','periodos.year as periodo_year','periodos.periodo as periodo_p','puestos.puesto as puesto_p', DB::raw($conteo_total))
                     ->leftJoin('employees', 'employees.id', '=', 'incidencias.employee_id')
                     ->leftjoin('deparments', 'deparments.id', '=', 'employees.deparment_id')
                     ->leftJoin('qnas', 'qnas.id', '=', 'incidencias.qna_id')
                     ->leftJoin('puestos', 'puestos.id', '=', 'employees.puesto_id')
                     ->leftJoin('periodos', 'periodos.id', '=', 'incidencias.periodo_id')
                     ->leftJoin('codigos_de_incidencias', 'codigos_de_incidencias.id', '=', 'incidencias.codigodeincidencia_id')
                     ->whereNull('incidencias.deleted_at')
                     ->whereIn('deparments.id', $dptos)
                     ->whereRaw('? between fecha_inicio and fecha_final', [$fecha_inicial])
                     ->groupBy('token')
                     ->orderBy('deparments.code', 'ASC')
                     ->orderBy('num_empleado', 'ASC')
                     ->orderBy('codigos_de_incidencias.code', 'ASC')
                     ->orderBy('fecha_inicio', 'ASC')
                     ->get();
         return $incidencias;
    }
    public static function GetIncidenciasPorDia_Solo_Medicos($dptos,$medicosIds,$fecha_inicio)
    {

        $conteo_total = DB::raw('SUM(total_dias) as total');
          $incidencias = Incidencia::getQuery()
                     ->select('*','employees.id as empleado_id','incidencias.id as inc_id' ,'qnas.year as qna_year','periodos.year as periodo_year','periodos.periodo as periodo_p','puestos.puesto as puesto_p', DB::raw($conteo_total))
                     ->leftJoin('employees', 'employees.id', '=', 'incidencias.employee_id')
                     ->leftjoin('deparments', 'deparments.id', '=', 'employees.deparment_id')
                     ->leftJoin('qnas', 'qnas.id', '=', 'incidencias.qna_id')
                     ->leftJoin('puestos', 'puestos.id', '=', 'employees.puesto_id')
                     ->leftJoin('periodos', 'periodos.id', '=', 'incidencias.periodo_id')
                     ->leftJoin('codigos_de_incidencias', 'codigos_de_incidencias.id', '=', 'incidencias.codigodeincidencia_id')
                     ->whereNull('incidencias.deleted_at')
                     ->whereIn('deparments.id', $dptos)
                     ->whereIn('puestos.id', $medicosIds)
                     ->where('fecha_capturado', '=', $fecha_inicio)
                     ->groupBy('token')
                     ->orderBy('deparments.code', 'ASC')
                     ->orderBy('num_empleado', 'ASC')
                     ->orderBy('codigos_de_incidencias.code', 'ASC')
                     ->orderBy('fecha_inicio', 'ASC')
                     ->get();
         return $incidencias;

    }
    public static function GetIncidenciasPorDia_Solo_MedicosPorDia($dptos,$medicosIds,$fecha_inicio)
    {

        $conteo_total = DB::raw('SUM(total_dias) as total');
          $incidencias = Incidencia::getQuery()
                     ->select('*','employees.id as empleado_id','incidencias.id as inc_id' ,'qnas.year as qna_year','periodos.year as periodo_year','periodos.periodo as periodo_p','puestos.puesto as puesto_p', DB::raw($conteo_total))
                     ->leftJoin('employees', 'employees.id', '=', 'incidencias.employee_id')
                     ->leftjoin('deparments', 'deparments.id', '=', 'employees.deparment_id')
                     ->leftJoin('qnas', 'qnas.id', '=', 'incidencias.qna_id')
                     ->leftJoin('puestos', 'puestos.id', '=', 'employees.puesto_id')
                     ->leftJoin('periodos', 'periodos.id', '=', 'incidencias.periodo_id')
                     ->leftJoin('codigos_de_incidencias', 'codigos_de_incidencias.id', '=', 'incidencias.codigodeincidencia_id')
                     ->whereNull('incidencias.deleted_at')
                     ->whereIn('deparments.id', $dptos)
                     ->whereIn('puestos.id', $medicosIds)
                     ->whereRaw('? between fecha_inicio and fecha_final', [$fecha_inicio])
                     ->groupBy('token')
                     ->orderBy('deparments.code', 'ASC')
                     ->orderBy('num_empleado', 'ASC')
                     ->orderBy('codigos_de_incidencias.code', 'ASC')
                     ->orderBy('fecha_inicio', 'ASC')
                     ->get();
         return $incidencias;

    }

    public static function getTotalIncidenciasPorEmpleadoValida($fecha_inicial, $fecha_final, $id_empleado)
    {
      $conteo_total = DB::raw('SUM(total_dias) as total');
      $incidencias = Incidencia::getQuery()
                 ->select(DB::raw($conteo_total))
                 ->leftJoin('employees', 'employees.id', '=', 'incidencias.employee_id')
                 //->leftjoin('deparments', 'deparments.id', '=', 'employees.deparment_id')
                 //->leftJoin('codigos_de_incidencias', 'codigos_de_incidencias.id', '=', 'incidencias.codigodeincidencia_id')
                 //->where('deparments.id', '=', $dpto_id)
                 ->whereNull('incidencias.deleted_at')
                 ->where('employees.id', '=', $id_empleado)
                 //->where('codigos_de_incidencias.code', '=', $code)
                 ->whereBetween('fecha_inicio',[$fecha_inicial,$fecha_final])
                 ->groupBy('employee_id')
                 ->get();

     return $incidencias;
    }
    public static function getInasistencias($codes, $fecha_inicio, $fecha_final, $dptos)
    {
      $conteo_total = DB::raw('SUM(total_dias) as total');
      $incidencias = Incidencia::getQuery()
                 ->select('*','employees.id as empleado_id','incidencias.id as inc_id','jornadas.id as jornada_id','puestos.puesto as puesto_p', DB::raw($conteo_total))
                 ->leftJoin('employees', 'employees.id', '=', 'incidencias.employee_id')
                 ->leftjoin('deparments', 'deparments.id', '=', 'employees.deparment_id')
                 ->leftJoin('codigos_de_incidencias', 'codigos_de_incidencias.id', '=', 'incidencias.codigodeincidencia_id')
                 ->leftJoin('puestos', 'puestos.id', '=', 'employees.puesto_id')
                 ->leftJoin('jornadas', 'jornadas.id', '=', 'employees.jornada_id')
                 ->whereNull('incidencias.deleted_at')
                 ->whereIn('deparments.id', $dptos)
                 ->whereIn('codigos_de_incidencias.code', $codes)
                 ->whereBetween('fecha_inicio',[$fecha_inicio,$fecha_final])
                 //>orderBy('fecha_inicio', 'ASC')
                 ->groupBy('num_empleado')
                 ->groupBy('token')
                 ->get();
     return $incidencias;
    }
    public static function valAguinaldo($fecha_inicio, $fecha_final, $dptos)
    {
      $conteo_total = DB::raw('SUM(total_dias) as total');
      $incidencias = Incidencia::getQuery()
                 ->select('*','employees.id as empleado_id','incidencias.id as inc_id','jornadas.id as jornada_id','puestos.puesto as puesto_p', DB::raw($conteo_total))
                 ->leftJoin('employees', 'employees.id', '=', 'incidencias.employee_id')
                 ->leftjoin('deparments', 'deparments.id', '=', 'employees.deparment_id')
                 ->leftJoin('codigos_de_incidencias', 'codigos_de_incidencias.id', '=', 'incidencias.codigodeincidencia_id')
                 ->leftJoin('puestos', 'puestos.id', '=', 'employees.puesto_id')
                 ->leftJoin('jornadas', 'jornadas.id', '=', 'employees.jornada_id')
                 ->whereNull('incidencias.deleted_at')
                 ->whereIn('deparments.id', $dptos)
                 ->whereIn('codigos_de_incidencias.code',['10','100'])
                 ->whereBetween('fecha_inicio',[$fecha_inicio,$fecha_final])
                 ->orderBy('employees.deparment_id', 'ASC')
                 ->orderBy('num_empleado', 'ASC')
                 ->groupBy('num_empleado')
                 ->groupBy('codigos_de_incidencias.code')
                 ->get();
     return $incidencias;
    }
    public static function Getlogs($limit)
    {
      //$conteo_total = DB::raw('SUM(total_dias) as total');
      $incidencias = Incidencia::getQuery()
                 ->select('*','employees.id as empleado_id','incidencias.id as inc_id' ,'qnas.year as qna_year','periodos.year as periodo_year','periodos.periodo as periodo_p','incidencias.created_at as inc_crea','incidencias.deleted_at as inc_deleted')
                 ->leftJoin('employees', 'employees.id', '=', 'incidencias.employee_id')
                 ->leftJoin('qnas', 'qnas.id', '=', 'incidencias.qna_id')
                 ->leftJoin('periodos', 'periodos.id', '=', 'incidencias.periodo_id')
                 ->leftJoin('codigos_de_incidencias', 'codigos_de_incidencias.id', '=', 'incidencias.codigodeincidencia_id')
                 ->orderBy('inc_crea', 'DESC')
                 //->orderBy('employees.deparment_id', 'ASC')
                 //->groupBy('num_empleado')
                 //->groupBy('deparment_id')
                 ->take($limit)
                 ->get();
     return $incidencias;
                 //dd($incidencias);
    }

    public static function getIncidenciasSingles($fecha, $primer_dia, $ultimo_dia, $emp_id){

        $incidencias = Incidencia::getQuery()
                 ->select('*','employees.id as empleado_id','incidencias.id as inc_id','jornadas.id as jornada_id','puestos.puesto as puesto_p', DB::raw($conteo_total))
                 ->leftJoin('employees', 'employees.id', '=', 'incidencias.employee_id')
                 ->whereNull('incidencias.deleted_at')
                 ->whereBetween('fecha_inicio',[$primer_dia,$ultimo_dia])
                 ->first();
        return $incidencias;
    }
    public static function getIncidenciasCovid($qna_id, $dpto_id)
    {
      $conteo_total = DB::raw('SUM(total_dias) as total');
      $incidencias = Incidencia::getQuery()
                 ->select('*','employees.id as empleado_id','incidencias.id as inc_id' ,'qnas.year as qna_year','periodos.year as periodo_year','periodos.periodo as periodo_p','puestos.puesto as puesto_p', DB::raw($conteo_total))
                 ->leftJoin('employees', 'employees.id', '=', 'incidencias.employee_id')
                 ->leftjoin('deparments', 'deparments.id', '=', 'employees.deparment_id')
                 ->leftJoin('puestos', 'puestos.id', '=', 'employees.puesto_id')
                 ->leftJoin('qnas', 'qnas.id', '=', 'incidencias.qna_id')
                 ->leftJoin('periodos', 'periodos.id', '=', 'incidencias.periodo_id')
                 ->leftJoin('codigos_de_incidencias', 'codigos_de_incidencias.id', '=', 'incidencias.codigodeincidencia_id')
                 ->whereNull('incidencias.deleted_at')
                 ->whereNotIn('codigos_de_incidencias.code', [900])
                 ->whereIn('codigos_de_incidencias.code', [908, 909, 912])
                 //->whereIn('codigos_de_incidencias.code', [907, 908, 909])
                 ->where('qna_id', $qna_id)
                 ->where('deparments.id', '=', $dpto_id)
                 ->groupBy('token')
                 ->orderBy('num_empleado', 'ASC')
                 ->orderBy('codigos_de_incidencias.code', 'ASC')
                 ->orderBy('fecha_inicio', 'ASC')
                 ->get();
     return $incidencias;
    }
    public static function getIncidenciasCovid2()
    {
      $conteo_total = DB::raw('SUM(total_dias) as total');
      $incidencias = Incidencia::getQuery()
                 ->select('*','employees.id as empleado_id', 'incidencias.id as inc_id' ,'qnas.year as qna_year','periodos.year as periodo_year','periodos.periodo as periodo_p','puestos.puesto as puesto_p','deparments.code as depa_code','deparments.description as depa_description', DB::raw($conteo_total))
                 ->leftJoin('employees', 'employees.id', '=', 'incidencias.employee_id')
                 ->leftjoin('deparments', 'deparments.id', '=', 'employees.deparment_id')
                 ->leftJoin('puestos', 'puestos.id', '=', 'employees.puesto_id')
                 ->leftJoin('qnas', 'qnas.id', '=', 'incidencias.qna_id')
                 ->leftJoin('periodos', 'periodos.id', '=', 'incidencias.periodo_id')
                 ->leftJoin('codigos_de_incidencias', 'codigos_de_incidencias.id', '=', 'incidencias.codigodeincidencia_id')
                 ->whereNull('incidencias.deleted_at')
                 ->whereIn('codigos_de_incidencias.code', ['908', '909'])
                 ->whereBetween('fecha_inicio',['2021-08-01','2021-12-31'])
                 //->whereRaw('? between fecha_inicio and fecha_final', ['2022-01-03'])
                 ->groupBy('num_empleado')
                 ->orderBy('num_empleado', 'ASC')
                 ->get();
     return $incidencias;
    }
    public static function getTotalLicenciasSinGoce($empleado_id, $fecha_inicio,$fecha_final)
    {
      $conteo_total = DB::raw('sum(total_dias) as total , max(fecha_final) as fecha_fin');
      $incidencias = Incidencia::getQuery()
                 ->select('*',DB::raw($conteo_total))
                 ->leftJoin('employees', 'employees.id', '=', 'incidencias.employee_id')
                 ->leftjoin('deparments', 'deparments.id', '=', 'employees.deparment_id')
                 ->leftJoin('codigos_de_incidencias', 'codigos_de_incidencias.id', '=', 'incidencias.codigodeincidencia_id')
                 ->whereNull('incidencias.deleted_at')
                 ->where('codigos_de_incidencias.code', '=', '100')
                 ->where('num_empleado', '=', $empleado_id)
                 //->whereBetween('fecha_inicio',[$fecha_inicio,$fecha_final])
                 ->groupBy('num_empleado')
                 ->get();

        /*if ($incidencias) {
            return $incidencias->total;
        }
        else{
            return 0;
        }*/
        return $incidencias;
    }
    public static function getEmpleadoSearchVacaciones($empleado_id, $dptos)
    {
      $conteo_total = DB::raw('sum(total_dias) as total , max(fecha_final) as fecha_fin');
      $incidencias = Incidencia::getQuery()
                 ->select('*',DB::raw($conteo_total))
                 ->leftJoin('employees', 'employees.id', '=', 'incidencias.employee_id')
                 ->leftjoin('deparments', 'deparments.id', '=', 'employees.deparment_id')
                 ->leftJoin('periodos', 'periodos.id', '=', 'incidencias.periodo_id')
                 ->leftJoin('codigos_de_incidencias', 'codigos_de_incidencias.id', '=', 'incidencias.codigodeincidencia_id')
                 ->whereNull('incidencias.deleted_at')
                 ->whereIn('deparments.id', $dptos)
                 ->whereIn('codigos_de_incidencias.code', ['60','62','63'])
                 ->where('num_empleado', '=', $empleado_id)
                 ->groupBy('periodo_id')
                 ->orderBy('periodo_id', 'ASC')
                 ->get();

        return $incidencias;
  }
}
