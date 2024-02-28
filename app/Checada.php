<?php

namespace App;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class Checada extends Model
{

    protected $fillable = ['num_empleado','fecha','identificador'];

    protected $connection = 'mysql-biometrico-pruebas';
    protected $table = 'checadas';

    public function employee()
    {
      return $this->belongsTo('App\employe');
    }

    public static function get_Checadas($dpto, $qna, $año)
    {
      $checadas = Checada::getQuery()
                 ->select('*','employees.num_empleado','employees.horario_id')
                 ->leftJoin('employees', 'employees.num_empleado', '=', 'checadas.num_empleado')
                 ->leftjoin('deparments', 'deparments.id', '=', 'employees.deparment_id')
                 ->leftjoin('horarios', 'horarios.id', '=', 'employees.horario_id')
                 ->where('deparment_id', $dpto)
                 ->where('qna', $qna)
                 ->where('ejercicio', $año)
                 ->orderBy('checadas.num_empleado', 'ASC')
                 ->orderBy('fecha', 'ASC')
                 ->orderBy('checadas.num_empleado', 'ASC')
                 ->get();
     return $checadas;
    }
    public static function insertOrUpdate(array $rows){
        //$table = DB::getTablePrefix().with(new self)->getTable();


        $first = reset($rows);

        $columns = implode( ',',
            array_map( function( $value ) { return "$value"; } , array_keys($first) )
        );

        $values = implode( ',', array_map( function( $row ) {
                return '('.implode( ',',
                    array_map( function( $value ) { return '"'.str_replace('"', '""', $value).'"'; } , $row )
                ).')';
            } , $rows )
        );

        $updates = implode( ',',
            array_map( function( $value ) { return "$value = VALUES($value)"; } , array_keys($first) )
        );

        $sql = "INSERT INTO checadas ({$columns}) VALUES {$values} ON DUPLICATE KEY UPDATE {$updates}";

        DB::statement( $sql );
    }
}
