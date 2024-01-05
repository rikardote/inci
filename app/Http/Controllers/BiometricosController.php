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
            \Artisan::call('biometrico:checadas');

            $aviso = 'Se descargaron y grabaron todas las checadas exitosamente, y se sincronizo la hora y fecha';

            Flash::success($aviso);

            return redirect('/dashboard')->with($aviso);
        }

    }
    public function get_checadas(){
      $dptos = \Auth::user()->centros->pluck('id')->toArray();
      $dptos = Deparment::whereIn('deparments.id', $dptos)->get();
      $date = Carbon::today();
      $month =  $date->month;
      $day =  $date->day;
      $year = $date->year;
      //$year = "2023";

      $qna = $month * 2;
      if ($day < 16) {
            $qna-=1;
      }

      $qnas = Qna::orderby('id', 'asc')->where('year','=',$year)->limit($qna)->get()->pluck('Qnaa', 'id')->toArray();

      if (\Auth::user()->admin()) {
        $qnas = Qna::orderby('id', 'desc')->get()->pluck('Qnaa', 'id')->toArray();
        }

      //krsort($qnas);
      //

      return view('biometrico.get_checadas')->with('dptos', $dptos)->with('qnas', $qnas);

    }

    /* REPORTE DE CHECADAS DEL BIOMETRICO  */
    public function buscar(Request $request){
    	$dpto = $request->deparment_id;
        $dpto_des = Deparment::find($dpto);

        $qna = Qna::where('id', $request->qna_id)->first();
        $fecha_inicio = getFechaInicioPorQna($qna->id);
        $fecha_final = getFechaFinalPorQna($fecha_inicio);

        $begin = new DateTime( $fecha_inicio );
        $end = new DateTime( $fecha_final );
        $end = $end->modify( '+1 day' );

        $interval = new DateInterval('P1D');
        $daterange = new DatePeriod($begin, $interval ,$end);

    	//$checadas = Checada::get_Checadas($dpto, $qna->qna, $qna->year);
        $emp = Employe::get_empleados($dpto);
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
}
