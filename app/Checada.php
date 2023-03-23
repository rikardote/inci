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

    public static function insertIgnore(array $attributes = [])
    {
        $model = new static($attributes);

        if ($model->usesTimestamps()) {
            $model->updateTimestamps();
        }

        $attributes = $model->getAttributes();

        $query = $model->newBaseQueryBuilder();
        $processor = $query->getProcessor();
        $grammar = $query->getGrammar();

        $table = $grammar->wrapTable($model->getTable());
        $keyName = $model->getKeyName();
        $columns = $grammar->columnize(array_keys($attributes));
        $values = $grammar->parameterize($attributes);

        $sql = "insert ignore into {$table} ({$columns}) values ({$values})";

        $id = $processor->processInsertGetId($query, $sql, array_values($attributes));

        $model->setAttribute($keyName, $id);

        return $model;
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
