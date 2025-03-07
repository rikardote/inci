<?php

namespace App;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class Checada extends Model
{

    protected $fillable = ['num_empleado','fecha','identificador'];

    protected $connection = 'mysql-biometrico';
    protected $table = 'checadas';

    public function employee()
    {
      return $this->belongsTo('App\Employe', 'num_empleado', 'num_empleado');

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
        $table = DB::getTablePrefix().with(new self)->getTable();


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

        $sql = "INSERT INTO {$table} ({$columns}) VALUES {$values} ON DUPLICATE KEY UPDATE {$updates}";
        //dd($sql);
        return DB::statement( $sql );
    }


    public static function obtenerRegistros($centro, $fecha_inicio, $fecha_fin)
    {
        try {
            $connection = DB::connection('mysql-biometrico');

            // Limpiar tablas temporales previas
            $connection->unprepared("
                DROP TEMPORARY TABLE IF EXISTS fechas_temp;
                DROP TEMPORARY TABLE IF EXISTS dias_laborables_temp;
                DROP TEMPORARY TABLE IF EXISTS empleados_temp;
            ");

            // Crear tabla temporal de fechas
            $connection->unprepared("
                CREATE TEMPORARY TABLE fechas_temp (fecha DATE);
            ");

            // Insertar fechas - Usando DateTime de forma compatible
            $current = new \DateTime($fecha_inicio);
            $end = new \DateTime($fecha_fin);

            while ($current <= $end) {
                $connection->insert(
                    "INSERT INTO fechas_temp (fecha) VALUES (?)",
                    [$current->format('Y-m-d')]
                );
                $current->modify('+1 day');
            }

            // Crear tabla de días laborables
            $connection->unprepared("
                CREATE TEMPORARY TABLE dias_laborables_temp AS
                SELECT fecha FROM fechas_temp
                WHERE DAYOFWEEK(fecha) NOT IN (1, 7);
            ");

            // Crear tabla de empleados RELACIONANDO CON JORNADAS
            $connection->unprepared("
                CREATE TEMPORARY TABLE empleados_temp AS
                SELECT
                    e.num_empleado,
                    e.name as nombre,
                    e.father_lastname as apellido_paterno,
                    e.mother_lastname as apellido_materno,
                    e.deparment_id,
                    h.horario,
                    SUBSTRING_INDEX(h.horario, ' A ', 1) as horario_entrada,
                    SUBSTRING_INDEX(h.horario, ' A ', -1) as horario_salida,
                    e.id as employee_id,
                    -- Campo calculado para vespertinos
                    CASE
                        WHEN TIME(SUBSTRING_INDEX(h.horario, ' A ', 1)) >= '12:00:00' THEN 1
                        ELSE 0
                    END as es_jornada_vespertina
                FROM sistemas.employees e
                INNER JOIN sistemas.horarios h ON h.id = e.horario_id
                WHERE e.deparment_id = " . (int)$centro . "
                AND e.deleted_at IS NULL
            ");

            // Modificar consulta principal para incluir información de jornada
            $resultados = $connection->select("
                SELECT
                    e.num_empleado,
                    e.nombre,
                    e.apellido_paterno,
                    e.apellido_materno,
                    e.deparment_id,
                    e.horario_entrada,
                    e.horario_salida,
                    e.es_jornada_vespertina,
                    f.fecha,
                    MIN(c.fecha) as primera_checada,
                    MAX(c.fecha) as ultima_checada,
                    TIME(MIN(c.fecha)) as hora_entrada,
                    TIME(MAX(c.fecha)) as hora_salida,
                    COUNT(c.fecha) as num_checadas,
                    IF(MIN(c.fecha) IS NOT NULL,
                        TIME(MIN(c.fecha)) > ADDTIME(e.horario_entrada, '00:11:00'),
                        NULL
                    ) as retardo,
                    (
                        SELECT ci.code
                        FROM sistemas.incidencias i
                        INNER JOIN sistemas.codigos_de_incidencias ci ON ci.id = i.codigodeincidencia_id
                        WHERE i.employee_id = e.employee_id
                        AND f.fecha BETWEEN DATE(i.fecha_inicio) AND DATE(i.fecha_final)
                        AND i.deleted_at IS NULL
                        LIMIT 1
                    ) as incidencia
                FROM dias_laborables_temp f
                CROSS JOIN empleados_temp e
                LEFT JOIN checadas c ON e.num_empleado = c.num_empleado
                    AND DATE(c.fecha) = f.fecha
                GROUP BY
                    e.num_empleado,
                    e.nombre,
                    e.apellido_paterno,
                    e.apellido_materno,
                    e.deparment_id,
                    e.horario_entrada,
                    e.horario_salida,
                    e.es_jornada_vespertina,
                    e.employee_id,
                    f.fecha
                ORDER BY e.num_empleado, f.fecha
            ");

            return new \Illuminate\Support\Collection($resultados);

        } catch (\Exception $e) {
            \Log::error("Error en obtenerRegistros: " . $e->getMessage());
            throw $e;
        } finally {
            // Limpiar tablas temporales
            $connection->unprepared("
                DROP TEMPORARY TABLE IF EXISTS fechas_temp;
                DROP TEMPORARY TABLE IF EXISTS dias_laborables_temp;
                DROP TEMPORARY TABLE IF EXISTS empleados_temp;
            ");
        }
    }
    public static function buscarIncidencias($num_empleado, $fecha)
    {
        $sql = "
            SELECT ci.code as tipo
            FROM sistemas.incidencias i
            INNER JOIN sistemas.employees e ON e.id = i.employee_id
            INNER JOIN sistemas.codigos_de_incidencias ci ON ci.id = i.codigodeincidencia_id
            WHERE e.num_empleado = ?
            AND ? BETWEEN DATE(i.fecha_inicio) AND DATE(i.fecha_final)
            AND i.deleted_at IS NULL  -- Ignorar incidencias eliminadas
            LIMIT 1
        ";

        return DB::connection('mysql')->select($sql, [$num_empleado, $fecha]);
    }
}
