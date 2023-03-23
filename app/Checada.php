<?php

namespace App;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
//use Yadakhov\InsertOnDuplicateKey;
use App\Traits\InsertOnDuplicateKey;

class Checada extends Model
{
    use InsertOnDuplicateKey;
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
}
