<?php

namespace App;
use DB;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;
class Employe extends Model
{
    //use SoftDeletes;
    protected $table = 'employees';

    protected $fillable = ['num_empleado', 'name', 'father_lastname', 'mother_lastname', 'deparment_id', 'condicion_id', 'puesto_id', 'horario_id', 'num_plaza', 'num_seguro','jornada_id', 'lactancia', 'comisionado', 'estancia'];
    
    protected $dates = ['deleted_at'];
    
    public function deparment()
    {
    	return $this->belongsTo('App\Deparment');
    }
    public function condicion()
    {
        return $this->belongsTo('App\Condicion');
    }
    public function incidencia()
    {
        return $this->hasMany('App\Incidencia');
    }
    public function horario()
    {
        return $this->hasMany('App\Horario');
    }
    public function jornada()
    {
        return $this->hasMany('App\Jornada');
    }
    public function checada()
    {
        return $this->hasMany('App\Checada');
    }
    public function getfullnameAttribute($value)
    {
        
       return $this->name . ' ' . $this->father_lastname. ' ' . $this->mother_lastname;
        
    }
    
    public function setnameAttribute($value)
    {
        $this->attributes['name'] = strtoupper($value);
    }
    public function setfatherlastnameAttribute($value)
    {
        $this->attributes['father_lastname'] = strtoupper($value);
    }
    public function setmotherlastnameAttribute($value)
    {
        $this->attributes['mother_lastname'] = strtoupper($value);
    }
    public function getEmployeeAttribute($value)
    {
        return str_pad($value, 5, '0', STR_PAD_LEFT);
    }
    public static function getEmpleado($dptos)
    {
      
        //$empleado = DB::table('employees')
        $empleado = Employe::getQuery()
                 ->select('*','employees.id as emp_id')
                 ->leftjoin('deparments', 'deparments.id', '=', 'employees.deparment_id')
                 ->whereIn('deparments.id', $dptos)
                 ->orderBy('code', 'ASC')
                 ->orderBy('num_empleado', 'ASC')
                 ->whereNull('deleted_at')
                 ->get();
            return $empleado;
    }
    public static function getEmpleadoSearch($query, $dptos)
    {
      
        //$empleado = DB::table('employees')
        $empleado = Employe::getQuery()
                 ->select('*','employees.id as emp_id')
                 ->leftjoin('deparments', 'deparments.id', '=', 'employees.deparment_id')
                 ->leftjoin('horarios', 'horarios.id', '=', 'employees.horario_id')
                 ->leftjoin('puestos', 'puestos.id', '=', 'employees.puesto_id')
                 ->leftjoin('jornadas', 'jornadas.id', '=', 'employees.jornada_id')
                 ->whereIn('deparments.id', $dptos)
                 ->whereNull('deleted_at')
                 ->where('num_empleado', $query)
                 ->first();
            return $empleado;
    }
    public static function get_empleado($query)
    {
      
        //$empleado = DB::table('employees')
        $empleado = Employe::getQuery()
                 ->select('*','employees.id as emp_id')
                 ->leftjoin('deparments', 'deparments.id', '=', 'employees.deparment_id')
                 ->leftjoin('horarios', 'horarios.id', '=', 'employees.horario_id')
                 ->leftjoin('puestos', 'puestos.id', '=', 'employees.puesto_id')
                 ->leftjoin('jornadas', 'jornadas.id', '=', 'employees.jornada_id')
                 ->whereNull('deleted_at')
                 ->where('num_empleado', $query)
                 ->first();
            return $empleado;
    }
    public static function get_empleados($department_id)
    {
      
        //$empleado = DB::table('employees')
        $empleados = Employe::getQuery()
                 ->select('*','employees.id as emp_id')
                 ->leftjoin('deparments', 'deparments.id', '=', 'employees.deparment_id')
                 ->leftjoin('horarios', 'horarios.id', '=', 'employees.horario_id')
                 ->leftjoin('puestos', 'puestos.id', '=', 'employees.puesto_id')
                 ->leftjoin('jornadas', 'jornadas.id', '=', 'employees.jornada_id')
                 ->whereNull('deleted_at')
                 ->where('deparment_id', $department_id)
                 ->orderBy('num_empleado', 'ASC')
                 ->get();
            return $empleados;
    }


}
