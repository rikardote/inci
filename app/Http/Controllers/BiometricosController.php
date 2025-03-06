<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Yadakhov\InsertOnDuplicateKey;

use App\Http\Requests;
use App\Employe;
use App\Incidencia;
use App\Puesto;
use App\Horario;
use App\Deparment;
use App\Checada;
use App\Qna;
use Carbon\Carbon;
use App\Attendance;
use Rats\Zkteco\Lib\ZKTeco;
use DateTime;
use DatePeriod;
use DateInterval;
use Laracasts\Flash\Flash;
use \mPDF;
//use DB;
use Illuminate\Support\Facades\DB;

class BiometricosController extends Controller
{
	public function __construct()
    {
        $this->middleware('auth');
    }

    public function index() {

    	if (!\Auth::user()->admin()) {

            return view('home')->with('qna', $qna);
        }
        else {
            return view('biometrico.index');
        }

    }
    public function execute()
    {
            // Obtener la ruta base de Laravel
        $basePath = base_path();

        // Ejecutar comando con ruta completa
        $descriptorspec = array(
            0 => array("pipe", "r"),  // stdin
            1 => array("pipe", "w"),  // stdout
            2 => array("pipe", "w")   // stderr
        );

        $command = 'php "' . $basePath . '/artisan" biometrico:checadas';
        $process = proc_open($command, $descriptorspec, $pipes);

        if (is_resource($process)) {
            // Configurar cabeceras para SSE
            header('Content-Type: text/event-stream');
            header('Cache-Control: no-cache');
            header('Connection: keep-alive');
            header('X-Accel-Buffering: no');

            // Leer la salida
            while (!feof($pipes[1])) {
                $output = fgets($pipes[1]);
                if ($output) {
                    echo "data: " . json_encode(['output' => $output]) . "\n\n";
                    ob_flush();
                    flush();
                }
                usleep(100000); // 0.1 segundos
            }

            // Cerrar los pipes y el proceso
            fclose($pipes[0]);
            fclose($pipes[1]);
            fclose($pipes[2]);
            proc_close($process);
        }

        exit();
    }
    public function get_checadas(){
        $dptos = \Auth::user()->centros->pluck('id')->toArray();
        $dptos = Deparment::whereIn('deparments.id', $dptos)->get();
        $default_year = Carbon::now()->format('Y');
        $qnas = ['1' => '01 - 1RA ENERO','2' => '02 - 2DA ENERO','3' => '03 - 1RA FEBRERO','4' => '04 -	2DA FEBRERO','5' => '05 - 1RA MARZO','6' => '06 - 2DA MARZO','7' => '07 - 1RA ABRIL','8' => '08 - 2DA ABRIL','9' => '09 - 1RA MAYO','10' => '10 - 2DA MAYO','11' => '11 - 1RA JUNIO','12' => '12 - 2DA JUNIO','13' => '13 - 1RA JULIO','14' => '14 - 2DA JULIO'
            ,'15' => '15 - 1RA AGOSTO','16' => '16 - 2DA AGOSTO','17' => '17 - 1RA SEPTIEMBRE','18' => '18 - 2DA SEPTIEMBRE','19' => '19 - 1RA OCTUBRE','20' => '20 - 2DA OCTUBRE','21' => '21 - 1RA NOVIEMBRE','22' => '22 - 2DA NOVIEMBRE','23' => '23 - 1RA DICIEMBRE','24' => '24 - 2DA DICIEMBRE'
        ];
        //$years = ['2024' => '2024','2023' => '2023','2022' => '2022','2021' => '2021','2020' => '2020','2019' => '2019','2018' => '2018'];
        $years = array();
        for($i = 2024; $i<= date("Y"); $i++) {
            $years["$i"] = $i;
        }
        $title = "Reporte Biometrico";
        return view('biometrico.get_checadas')
            ->with('dptos', $dptos)
            ->with('qnas', $qnas)
            ->with('years', $years)
            ->with('default_year', $default_year)
            ->with('title', $title);
    }

    /* REPORTE DE CHECADAS DEL BIOMETRICO  */
    public function buscar(Request $request){


        $dpto_des = Deparment::where('code', $request->dpto)->first();
        $qna = Qna::where('qna', $request->qna)->where('year',$request->year)->first();

        $fecha_inicio = getFechaInicioPorQna($qna->id);
        $fecha_final = getFechaFinalPorQna($fecha_inicio);

        $begin = new DateTime( $fecha_inicio );
        $end = new DateTime( $fecha_final );
        $end = $end->modify( '+1 day' );

        $interval = new DateInterval('P1D');
        $daterange = new DatePeriod($begin, $interval ,$end);

    	//$checadas = Checada::get_Checadas($dpto, $qna->qna, $qna->year);
        $emp = Employe::get_empleados($dpto_des->id);
        $employees = collect($emp);   //turn data into collection
        $empleados = $employees->chunk(5); //chunk into smaller pieces
        $empleados->toArray(); //convert chunk to array
        //dd($empleados);

        return view('biometrico.show')->with('empleados', $empleados)->with('qna', $qna)->with('dpto', $dpto_des)->with('daterange', $daterange);

    }
    public function biometrico_pdf($qna_id, $dpto_id){

        $dpto = Deparment::find($dpto_id);
        $qna = Qna::where('id', $qna_id)->first();
        $fecha_inicio = getFechaInicioPorQna($qna->id);
        $fecha_final = getFechaFinalPorQna($fecha_inicio);

        $begin = new DateTime( $fecha_inicio );
        $end = new DateTime( $fecha_final );
        $end = $end->modify( '+1 day' );

        $interval = new DateInterval('P1D');
        $daterange = new DatePeriod($begin, $interval ,$end);

    	//$checadas = Checada::get_Checadas($dpto, $qna->qna, $qna->year);
        $empleados = Employe::get_empleados($dpto_id);
        //dd($empleados);
        $mpdf = new Mpdf('', 'Letter', 0, '', 12.7, 12.7, 14, 12.7, 8, 8);
        $header = \View('reportes.header', compact('dpto', 'qna'))->render();
        $mpdf->SetFooter($dpto->description.'|Generado el: {DATE j-m-Y} |Hoja {PAGENO} de {nb}');
        $html =  \View('biometrico.show_pdf', compact('empleados','daterange'))->render();
        $pdfFilePath = $qna->qna.'-'.$qna->year.'-'.$dpto->description.'.pdf';
        $mpdf->setAutoTopMargin = 'stretch';
        $mpdf->setAutoBottomMargin = 'stretch';
        $mpdf->setHTMLHeader($header);
        $mpdf->SetDisplayMode('fullpage');
        $mpdf->WriteHTML($html);
        //$mpdf->Output($pdfFilePath, "D");
        $mpdf->Output();



    }

    public function biometrico_ver_registros(){


        //$checadas = Attendance::whereBetween('fecha',[$fecha_inicio,$fecha_final])->orderby('fecha', 'ASC')->orderby('num_empleado', 'ASC')->orderby('hora', 'ASC')->get();

            $checadas = Attendance::where('qna','5')->orderby('num_empleado','ASC')->groupBy('num_empleado','fecha')->get();

            //dd($checadas);
            //$checadas = Attendance::where('num_empleado','262919')->get();
           //dd($checadas);

          return view('biometrico.ver_checadas')->with('checadas', $checadas);
    }

    public function conectar()
    {
         //BIOMETRICO 1 DELEGACION

        $zk = new ZKTeco("192.160.141.37");
        $zk->connect();
        sleep(1);
        $checadas_1 =  $zk->getAttendance();
        sleep(1);
        $zk->setTime(date("Y-m-d H:i:s"));
        sleep(1);
        $zk->disconnect();
        sleep(1);

        $zk2 = new ZKTeco("192.160.141.38");
        $zk2->connect();
        sleep(1);
        $checadas_2 =  $zk2->getAttendance();
        sleep(1);
        $zk2->setTime(date("Y-m-d H:i:s"));
        sleep(1);
        $zk2->disconnect();

        $checadas = array_merge($checadas_1, $checadas_2);

        $data=[];
            foreach($checadas as $checada){

                    $data[] = [
                        'num_empleado' => $checada['id'],
                        'fecha'    => date("Y-m-d H:i:s", strtotime($checada['timestamp'])),
                        'identificador' => $checada['id'].md5($checada['id'].date("Y-m-d", strtotime($checada['timestamp'])).date("H:i", strtotime($checada['timestamp']))),
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s')
                    ];
            }

        Checada::insertIgnore($data, ['identificador']);


    }
    public function show_checadas()
    {
        //$checadas = Checada::all()->unique();
        //$checadas = collect( $checadas );

        $entrada = Checada::where('num_empleado', '332618')
        ->where('fecha','2020-03-12')
        //->oldest()
        ->get();

        /*$salida = Checada::where('num_empleado', '332618')
        ->where('fecha','2020-03-12')
        ->latest()
        ->first();*/

        dd( $entrada );

        return view('biometrico.show_checadas')->with('checadas', $checadas);

    }
    public function asignar(){

        return view('biometrico.asignar_view');

    }

    public function asignar_post(Request $request){

            $identificador = md5($request->num_empleado.date("Y-m-d", strtotime($request->fecha)).date("H:i", strtotime($request->fecha)));

            if(!Checada::where('identificador', $identificador)->exists()){
                Checada::create([
                    'num_empleado' => $request->num_empleado,
                    'fecha'    => date("Y-m-d H:i:s", strtotime($request->fecha)),
                    'identificador' => $identificador,
                ]);
                $aviso = 'Capturado correctamente';
                Flash::success($aviso);

            }
            else {
                $aviso = 'ERROR REGISTRO REPETIDO';
                Flash::error($aviso);
            }

            return redirect('/dashboard')->with($aviso);

    }
public function verRegistrosBiometricos(Request $request)
{
    try {
        // Obtener solo los centros asignados al usuario
        $centrosUsuario = \Auth::user()->centros->pluck('id')->toArray();
        $centros = Deparment::whereIn('id', $centrosUsuario)
                         ->pluck('description', 'id');

        // Valores por defecto
        $año_seleccionado = $request->get('año', date('Y'));
        $quincena_actual = (date('d') <= 15) ? ((date('n') * 2) - 1) : (date('n') * 2);
        $quincena_seleccionada = $request->get('quincena', $quincena_actual);
        $centro_seleccionado = $request->get('centro');

        // Años disponibles
        $años = range(2024, (int)date('Y'));

        // Definir las 24 quincenas del año
        setlocale(LC_TIME, 'es_ES.UTF-8');
        $mesesEspanol = [
            1 => 'ENERO',
            2 => 'FEBRERO',
            3 => 'MARZO',
            4 => 'ABRIL',
            5 => 'MAYO',
            6 => 'JUNIO',
            7 => 'JULIO',
            8 => 'AGOSTO',
            9 => 'SEPTIEMBRE',
            10 => 'OCTUBRE',
            11 => 'NOVIEMBRE',
            12 => 'DICIEMBRE'
        ];

        $quincenas = [];
        for ($mes = 1; $mes <= 12; $mes++) {
            $ultimoDia = date('t', mktime(0, 0, 0, $mes, 1));

            $quincenas[] = [
                'value' => ($mes * 2 - 1),
                'label' => "1RA QUINCENA DE {$mesesEspanol[$mes]}"
            ];

            $quincenas[] = [
                'value' => ($mes * 2),
                'label' => "2DA QUINCENA DE {$mesesEspanol[$mes]}"
            ];
        }

        // Calcular fechas basado en la quincena
        $mes = ceil($quincena_seleccionada / 2);
        $es_primera_quincena = ($quincena_seleccionada % 2) != 0;

        $fecha_inicio = $es_primera_quincena
            ? "{$año_seleccionado}-" . str_pad($mes, 2, '0', STR_PAD_LEFT) . "-01"
            : "{$año_seleccionado}-" . str_pad($mes, 2, '0', STR_PAD_LEFT) . "-16";

        $fecha_fin = $es_primera_quincena
            ? "{$año_seleccionado}-" . str_pad($mes, 2, '0', STR_PAD_LEFT) . "-15"
            : "{$año_seleccionado}-" . str_pad($mes, 2, '0', STR_PAD_LEFT) . "-" . date('t', strtotime("{$año_seleccionado}-{$mes}-01"));

        // Obtener registros si hay centro seleccionado
        $registros = collect(); // Inicializar como Collection vacía
        $empleados = collect(); // Inicializar la variable empleados
        $incidenciasSinColor = ['7','17','40','41','42','46','49','51','53','54','55','60','61','62','63','77','94','901'];

        if ($centro_seleccionado) {
            if (!in_array($centro_seleccionado, $centrosUsuario)) {
                Flash::warning('No tiene acceso a este centro de trabajo');
                return redirect()->route('biometrico.registros', [
                    'año' => $año_seleccionado,
                    'quincena' => $quincena_seleccionada
                    // No incluimos centro para evitar el bucle de redirección
                ]);
            }

            $checada = new Checada();
            $resultados = $checada->obtenerRegistros($centro_seleccionado, $fecha_inicio, $fecha_fin);
            $registros = $resultados instanceof \Illuminate\Support\Collection ? $resultados : collect($resultados);

            // Agrupar por número de empleado y ordenar
            $empleados = $registros->groupBy('num_empleado')->sortBy(function($grupo) {
                return intval($grupo->first()->num_empleado);
            });
        }

        return view('biometrico.registros', compact(
            'centros',
            'registros',
            'empleados', // Añadimos la variable procesada
            'incidenciasSinColor', // Añadimos el array de incidencias sin color
            'años',
            'quincenas',
            'año_seleccionado',
            'quincena_seleccionada',
            'centro_seleccionado',
            'fecha_inicio',
            'fecha_fin'
        ));

    } catch (\Exception $e) {
        \Log::error("Error en verRegistrosBiometricos: " . $e->getMessage());
        Flash::error('Error al cargar los registros: ' . $e->getMessage());
        return redirect()->route('biometrico.registros');
    }
}
/*
private function tieneParametrosValidos(Request $request)
{
    return $request->has('centro');
}

        private function obtenerRegistros(Request $request)
    {
        $registros = Checada::obtenerRegistros(
            $request->input('centro'),
            $request->input('fecha_inicio'),
            $request->input('fecha_fin')
        );

        return collect($registros)->map(function($registro) {
            $incidencia = Checada::buscarIncidencias(
                $registro->num_empleado,
                $registro->fecha
            );

            $registro->tiene_incidencia = !empty($incidencia);
            $registro->tipo_incidencia = !empty($incidencia) ? $incidencia[0]->tipo : null;
            return $registro;
        });
    }
*/

}
