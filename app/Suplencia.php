<?php

namespace App;
use App\Employe;
use Carbon\Carbon;
use App\Incidencia;
use App\Suplente;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;


class Suplencia extends Model
{
    protected $fillable = [
                            'rfc',
                            'beneficiario',
                            'puesto',
                            'hodias',
                            'monto',
                            'centro',
                            'num_suplente',
                            'fecha_inicial',
                            'fecha_final',
                            'cvemov',
                            'num_empleado',
                            'fecha_captura',
                            'incidencia',
                            'servicio',
                            'year',
                            'quincena',
                            'nombre_suplente',
                            'updated_at',
                            'created_at'
                        ];
    protected $connection = 'mysql-gys';
    protected $table = 'suplencias';
    public $timestamps = true;
    const ASIGNADO_ANUAL = 5249721;

    protected $mapeoPuestos = [
            'AA00100' => 'AUXILIAR ADMINISTRATIVO ESPECIALIZADO',
            'CF40014' => 'ASISTENTE ADMINISTRATIVO EN SALUD A2',
            'CF40015' => 'ASISTENTE ADMINISTRATIVO EN SALUD A3',
            'CF41020' => 'MEDICO RESP UNIDAD MEDICA FAM',
            'CF41024' => 'JEFE DE ENFERMERAS A',
            'CF41050' => 'SUBJEFE DE ENFERMERAS',
            'CF41079' => 'SUBJEFE DE ENFERMERAS A',
            'CF41080' => 'SUPERVISOR MEDICO A',
            'CH41081' => 'SUPERVISOR MEDICO B',
            'CF41083' => 'SUPERVISOR MEDICO D',
            'CF41084' => 'SUPERVISOR MEDICO E',
            'CF41085' => 'SUPERVISOR MEDICO F',
            'EB00300' => 'TECNICO ESPECIALISTA',
            'ED00200' => 'CHOFER',
            'FADD100' => 'TECNICO MEDIO',
            'HA00300' => 'AUXILIAR ADMINISTRADOR',
            'HP00100' => 'TECNICO MEDIO',
            'HPUUZ00' => 'ESPECIALISTA TECNICO',
            'M01005' => 'MEDICO GENERAL A',
            'M01006' => 'MEDICO GENERAL A',
            'M01007' => 'CIRUJANO DENTISTA A',
            'M01008' => 'MEDICO GENERAL B',
            'M01009' => 'MEDICO GENERAL C',
            'M01010' => 'MEDICO ESPECIALISTA B',
            'M01011' => 'MEDICO ESPECIALISTA C',
            'M01014' => 'CIRUJANO DENTISTA B',
            'M01015' => 'CIRUJANO DENTISTA C',
            'M01016' => 'MEDICO JEFE DE SERVICIOS',
            'M01017' => 'JEFE DE SECCION MEDICA',
            'M01018' => 'MEDICO ODONTOLOGO A',
            'M01019' => 'MEDICO ODONTOLOGO B',
            'M01020' => 'MEDICO ODONTOLOGO C',
            'M01021' => 'MEDICO ODONTOLOGO ESPECIALISTA A',
            'M01022' => 'MEDICO ODONTOLOGO ESPECIALISTA B',
            'M01023' => 'MEDICO ODONTOLOGO ESPECIALISTA C',
            'M02001' => 'QUIMICO A',
            'M02003' => 'AUXILIAR DE LABORATORIO Y 0 BIOTERIO A',
            'M02005' => 'TECNICO RADIOLOGO 0 EN RADIOTERAPIA',
            'M02007' => 'TECNICO EN ELECTRODIAGNOSTICO',
            'M02012' => 'TERAPISTA',
            'M02014' => 'TECNICO EN OPTOMETRIA',
            'M02015' => 'PSICOLOGO CLINICO',
            'M02017' => 'CITOTECNOLOGO A',
            'M02018' => 'TECNICO ANESTESISTA',
            'M02031' => 'ENFERMERA DE SERVICIO',
            'M02034' => 'ENFERMERA ESPECIALISTA A',
            'M02035' => 'ENFERMERA GENERAL TITULADA A',
            'M02036' => 'AUXILIAR DE ENFERMERIA A',
            'M02038' => 'OFICIAL Y 0 PREPARADOR DESPACHADOR DE FARMACIA',
            'M02045' => 'DIETISTA',
            'M02049' => 'NUTRICIONISTA',
            'M02050' => 'TECNICO EN TRABAJO SOCIAL EN AREA MEDICA A',
            'M02074' => 'LABORATORISTA A',
            'M02081' => 'ENFERMERA GENERAL TITULADA B',
            'M02082' => 'AUXILIAR DE ENFERMERIA B',
            'M02083' => 'ENFERMERA GENERAL TECNICA',
            'M02085' => 'TRABAJADORA SOCIAL EN AREA MEDICA B',
            'M02087' => 'ENFERMERA ESPECIALISTA II',
            'M02088' => 'QUIMICO B',
            'M02089' => 'QUIMICO C',
            'M02090' => 'QUIMICO JEFE DE SECCION DE LAB DE ANALISIS CLINICOS',
            'M02091' => 'QUIMICO JEFE DE SECCION DE LAB DE ANALISIS CLINICOS',
            'M02093' => 'TECNICO LABORATORISTA B',
            'M02095' => 'TECNICO LABORATORISTA B',
            'M02101' => 'PASANTE DE LICENCIADO EN TRABAJO SOCIAL',
            'M02102' => 'ASISTENTE DE COCINA EN UNIDAD HOSPITALARIA',
            'M02103' => 'JEFE DE COCINA EN CENTRO HOSPITALARIO',
            'M02104' => 'COCINERO EN CENTRO HOSPITALARIO',
            'M03001' => 'INGENIERO BIOMEDICO',
            'M03005' => 'CAMILLERO',
            'M03012' => 'OPERADOR DE CALDERAS EN HOSPITAL',
            'M03018' => 'APOYO ADMINISTRATIVO EN SALUD AB',
            'M03019' => 'APOYO ADMINISTRATIVO EN SALUD A7',
            'M03020' => 'APOYO ADMINISTRATIVO EN SALUD A6',
            'M03021' => 'APOYO ADMINISTRATIVO EN SALUD A5',
            'M03022' => 'APOYO ADMINISTRATIVO EN SALUD A4',
            'M03023' => 'APOYO ADMINISTRATIVO EN SALUD A3',
            'M03024' => 'APOYO ADMINISTRATIVO EN SALUD A2',
            'M03025' => 'APOYO ADMINISTRATIVO EN SALUD A1',
            'M02075' => 'INHALOTERAPEUTA',
            'CF40021' => 'PROFESIONAL ADMINISTRATIVO "A"',
            'M02086' => 'SUPERVISORA DE TRABAJO SOCIAL EN AREA MEDICA "B"',
            'M02094' => 'LABORATORISTA "B"',
            'HE00200' => 'RECEPTOR PRODUCTOS VENTA',
            'M02077' => 'QUIMICO JEFE DE SECCION DE LAB. DE ANALISIS CLINICOS',
            'TF10003' => 'TECNICO EN ADMINISTRACION',
            'M02105' => 'ENFERMERA GENERAL TITULADA "C"',
            'M02106' => 'PASANTE DE PSICOLOGO',
            'M02019' => 'TECNICO HISTOPATOLOGO',
            'CF41025' => 'JEFE DE ENFERMERAS "B"',
            'M02084' => 'TRABAJADORA SOCIAL EN AREA MEDICA "A"',
            'M02037' => 'SUBJEFE DE FARMACIA',
            'M03013' => 'TECNICO OPERADOR DE CALDERAS EN HOSPITAL',
            'M02011' => 'TERAPISTA ESPECIALIZADO',
            'M01004' => 'MEDICO ESPECIALISTA A',
            'M03011' => 'LAVANDERA EN HOSPITAL',
            'M02006' => 'TECNICO RADIOLOGO O EN RADIOTERAPIA',
            'M03006' => 'CAMILLERO',

        ];

    protected $mapeoCentros = [
            '0202120100' => '00054',
            '0202120200' => '00055',
            '0202130300' => '00056',
            '0202150500' => '00061',
            '0202140100' => '04872',
            '0204200100' => '00074',
            '0204200200' => '00075',
            '0204200300' => '00076',
            '0204200400' => '00077',
        ];
        protected $montosMensuales = [
            1 => 615348.87,  // Enero
            2 => 355598.75,  // Febrero
            3 => 369214.68,  // Marzo
            4 => 437337.49,  // Abril
            5 => 389603.70,  // Mayo
            6 => 391850.05,  // Junio
            7 => 414868.65,  // Julio
            8 => 435355.02,  // Agosto
            9 => 414648.13,  // Septiembre
            10 => 415992.68, // Octubre
            11 => 423319.50, // Noviembre
            12 => 586583.48  // Diciembre
        ];
    public function obtenerDescripcionCentro()
    {
        $mapeoCentros = [
            '0202120100' => 'HOSPITAL GENERAL 5 DE DICIEMBRE',
            '0202120200' => 'HOSPITAL GENERAL FRAY JUNIPERO SERRA',
            '0202130300' => 'CLINICA HOSPITAL ENSENADA',
            '0202140100' => 'CLINICA DE MEDICINA FAMILIAR MESA DE OTAY',
            '0202150500' => 'UNIDAD DE MEDICINA FAMILIAR A',
            '0204200100' => 'ESTANCIA BIENESTAR INFANTIL No 034',
            '0204200300' => 'ESTANCIA BIENESTAR INFANTIL No 060',
            '0204200200' => 'ESTANCIA BIENESTAR INFANTIL No 059',
            '0204200400' => 'ESTANCIA BIENESTAR INFANTIL No 105',
        ];

        return $mapeoCentros[$this->centro] ?? null;
    }
    public function obtenerDescripcionIncidencia()
    {
        $mapeoIncidencias = [
            'S1' => 'PERMISOS ECONÓMICOS',
            'S2' => 'INCAPACIDAD',
            'S3' => 'VACACIONES',
            'S4' => 'COMISIÓN OFICIAL AUTORIZADA',
            'S5' => 'FALTA JUSTIFICADA',
            'S6' => 'INASISTENCIA',
            'G1' => 'GUARDIA',
            'G2' => 'GUARDIA',
        ];

        return $mapeoIncidencias[$this->incidencia] ?? null;
    }
    public static function montosPorQuincenaCentro()
    {
        $datos = self::select('centro', 'quincena', 'year')
            ->selectRaw('SUM(monto) as monto_total')
            ->groupBy('centro', 'quincena', 'year')
            ->orderBy('year')
            ->orderBy('quincena')
            ->orderBy('centro')
            ->get();

        $periodos = $datos->groupBy(function($item) {
            return $item->year . '.' . $item->quincena;
        })->sortBy(function($item, $key) {
            return $key;
        });

            // Obtenemos los centros y los mapeamos a sus códigos
        $centros = $datos->pluck('centro')
            ->unique()
            ->sort()
            ->values()
            ->map(function($centro) {
                $mapeoCentros = (new self)->mapeoCentros;
                return $mapeoCentros[$centro] ?? $centro;
            });

        return [
            'periodos' => $periodos,
            'centros' => $centros,
            'centrosOriginales' => $datos->pluck('centro')->unique()->sort()->values()
        ];
    }
    public function obtenerDescripcionPuesto($codigoPuesto)
    {
        return $this->mapeoPuestos[$codigoPuesto] ?? null;
    }
    public function validarIncidencia($suplencia)
    {
                $fechaInicio = Carbon::parse($suplencia->fecha_inicial);

                $incidenciaExistente = Incidencia::join('employees', 'employees.id', '=', 'incidencias.employee_id')
                ->where('employees.num_empleado', $suplencia->num_empleado)
                ->where(function ($query) use ($fechaInicio) {
                    $query->where('fecha_inicio', '<=', $fechaInicio)
                          ->where('fecha_final', '>=', $fechaInicio);
                })
                ->exists();

                return ($incidenciaExistente || in_array($suplencia->incidencia, ['G2', 'G1'])) ? 1 : '';

    }
public static function obtenerEmpleado($num_empleado)
    {
                $empleado = Employe::where('num_empleado', $num_empleado)->first();

                if (!$empleado) {
                    return '-';
                }
                return trim(sprintf('%s %s %s',
                    $empleado->name,
                    $empleado->father_lastname,
                    $empleado->mother_lastname
                ));
     }
     public static function obtenerReporteMensual()
      {
          $instance = new static;

          // Obtener todos los registros agrupados por mes
          $montosEjercidos = self::select(
              DB::raw('MONTH(fecha_inicial) as mes'),
              DB::raw('SUM(monto) as monto_ejercido')
          )
          ->groupBy('mes')
          ->get()
          ->pluck('monto_ejercido', 'mes')
          ->toArray();

          $reporte = [];

          foreach ($instance->montosMensuales as $mes => $montoAsignado) {
              $montoEjercido = $montosEjercidos[$mes] ?? 0;
              $disponible = $montoAsignado - $montoEjercido;
              $porcentajeEjercido = ($montoEjercido / $montoAsignado) * 100;

              $reporte[$mes] = [
                  'mes' => $mes,
                  'monto_asignado' => $montoAsignado,
                  'monto_ejercido' => $montoEjercido,
                  'disponible' => $disponible,
                  'porcentaje_ejercido' => $porcentajeEjercido
              ];
          }

          // Calcular totales
          $totales = [
            'monto_asignado' => self::ASIGNADO_ANUAL,
            'monto_ejercido' => array_sum(array_column($reporte, 'monto_ejercido')),
            'disponible' => self::ASIGNADO_ANUAL - array_sum(array_column($reporte, 'monto_ejercido')),
          ];
          $totales['porcentaje_ejercido'] = ($totales['monto_ejercido'] / $totales['monto_asignado']) * 100;

          return [
              'mensual' => $reporte,
              'totales' => $totales
          ];
      }
      public static function exportarSuplentesPorQuincena($year, $quincena)
      {
          $suplencias = self::where('year', $year)
              ->where('quincena', $quincena)
              ->get();

          $output = fopen('php://temp', 'w+');

          // Encabezados del CSV
          fputcsv($output, [
              'Quincena',
              'Año',
              'RFC',
              'C.T.',
              'Puesto',
              'Suplente',
              'Fecha inicial',
              'Fecha final',
              'Dias',
              'Incidencia',
              'No.de Empleado',
              'Trabajador',
              'Monto',
          ]);

          // Datos de suplencias
          foreach ($suplencias as $suplencia) {
              fputcsv($output, [
                  $suplencia->quincena,
                  $suplencia->year,
                  $suplencia->rfc,
                  $suplencia->obtenerDescripcionCentro(),
                  $suplencia->obtenerDescripcionPuesto($suplencia->puesto),
                  $suplencia->nombre_suplente,
                  $suplencia->fecha_inicial,
                  $suplencia->fecha_final,
                  $suplencia->hodias,
                  $suplencia->obtenerDescripcionIncidencia(),
                  $suplencia->num_empleado,
                  $suplencia->obtenerEmpleado($suplencia->num_empleado),
                  number_format($suplencia->monto, 2)
              ]);
          }

          // Agregar total
          fputcsv($output, [
              'Total',
              '',
              '',
              '',
              '',
              '',
              '',
              '',
              '',
              '',
              '',
              '',
              number_format($suplencias->sum('monto'), 2)
          ]);

          rewind($output);
          $csv = stream_get_contents($output);
          fclose($output);

          return $csv;
      }
      public static function exportarReporteMensual()
      {
          $reporte = self::obtenerReporteMensual();
          $meses = [
              1 => 'Enero', 2 => 'Febrero', 3 => 'Marzo',
              4 => 'Abril', 5 => 'Mayo', 6 => 'Junio',
              7 => 'Julio', 8 => 'Agosto', 9 => 'Septiembre',
              10 => 'Octubre', 11 => 'Noviembre', 12 => 'Diciembre'
          ];

          $output = fopen('php://temp', 'w+');

          // Encabezados del CSV
          fputcsv($output, [
              'Mes',
              'Monto Asignado',
              'Monto Ejercido',
              'Disponible',
              'Porcentaje Ejercido'
          ]);

          // Datos mensuales
          foreach ($reporte['mensual'] as $mes => $datos) {
              fputcsv($output, [
                  $meses[$mes],
                  number_format($datos['monto_asignado'], 2),
                  number_format($datos['monto_ejercido'], 2),
                  number_format($datos['disponible'], 2),
                  number_format($datos['porcentaje_ejercido'], 2) . '%'
              ]);
          }

          // Totales
          fputcsv($output, [
              'TOTAL ANUAL',
              number_format($reporte['totales']['monto_asignado'], 2),
              number_format($reporte['totales']['monto_ejercido'], 2),
              number_format($reporte['totales']['disponible'], 2),
              number_format($reporte['totales']['porcentaje_ejercido'], 2) . '%'
          ]);

          rewind($output);
          $csv = stream_get_contents($output);
          fclose($output);

          return $csv;
      }
      public static function validarSuplente($suplente_rfc)
      {
        $suplente = Suplente::where('rfc', $suplente_rfc)->first();

        return $suplente->nombre ?? null;
      }

}
