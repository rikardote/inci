<?php

namespace App\Console\Commands;

use DateTime;
use DatePeriod;
use App\Checada;
use DateInterval;
use Carbon\Carbon;
use Rats\Zkteco\Lib\ZKTeco;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class Biometrico extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'biometrico:checadas';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Comando para descargar checadas del biometrico diario';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
	$this->info('Iniciando descarga de checadas espere un momento:' ."\n");
        $t = date("Y-m-d H:i:s");

        //BIOMETRICO 1 DELEGACION
        /*
        $zk = new ZKTeco("192.160.141.37");
        $zk->connect();
        sleep(1);
        $checadas_1 =  $zk->getAttendance();
        sleep(1);
        $zk->setTime($t);
        sleep(1);
        $zk->disconnect();
        sleep(1);

        //BIOMETRICO 2 DELEGACION (COMEDOR)
        $zk2 = new ZKTeco("192.160.141.38");
        //$zk2 = new ZKTeco("192.161.172.42");
        $zk2->connect();

        sleep(1);
        $checadas_2 =  $zk2->getAttendance();
        sleep(1);
        $zk2->setTime($t);
        sleep(1);
        $zk2->disconnect();
*/
        //BIOMETRICO 3 ALGODONES

        $zk3 = new ZKTeco("192.165.232.253");
        $zk3->connect();
        sleep(1);
        $checadas_3 =  $zk3->getAttendance();
        sleep(1);
        $zk3->disconnect();

        //BIOMETRICO 4 SAN FELIPE
        $zk4 = new ZKTeco("192.165.240.253");
        $zk4->connect();
        sleep(1);
        $checadas_4 =  $zk4->getAttendance();
        sleep(1);
        $zk4->disconnect();

        //BIOMETRICO 5 TECATE
        $zk5 = new ZKTeco("192.165.171.253");
        $zk5->connect();
        sleep(1);
        $checadas_5 =  $zk5->getAttendance();
        sleep(1);
        $zk5->disconnect();

        //BIOMETRICO 6 OTAY
        /*$zk6 = new ZKTeco("192.168.201.7");
        $zk6->connect();
        sleep(1);
        $checadas_6 =  $zk6->getAttendance();
        sleep(1);
        $zk6->disconnect();
*/
        //$checadas = array_merge($checadas_1, $checadas_2, $checadas_3, $checadas_4, $checadas_5);
        $checadas = array_merge($checadas_3, $checadas_4, $checadas_5);
        $progressBar = $this->output->createProgressBar(count($checadas));
        $this->info('Iniciando Guardado en base de datos...'."\n");
        $progressBar->start();
        $data = [];
        foreach($checadas as $checada){
            $identificador = md5($checada['id'].date("Y-m-d", strtotime($checada['timestamp'])).date("H:i", strtotime($checada['timestamp'])));

            if(!Checada::where('identificador', $identificador)->exists()){

                   $data[] = array(
                        'num_empleado' => $checada['id'],
                        'fecha'    => date("Y-m-d H:i:s", strtotime($checada['timestamp'])),
                        'identificador' => $identificador
                    );
                /*DB::table('checadas')->insert([
                    'num_empleado' => $checada['id'],
                    'fecha'    => date("Y-m-d H:i:s", strtotime($checada['timestamp'])),
                    'identificador' => $identificador,
                ]);
                */
            }




        }
        $collection = collect($data);   //turn data into collection
        $chunks = $collection->chunk(100); //chunk into smaller pieces
        $chunks->toArray(); //convert chunk to array
        
        foreach($chunks as $chunk)
        {
            Checada::insert($chunk); //insert chunked data
            $progressBar->advance();
        }
	$progressBar->finish();

	$this->info("\n".'Se descargaron y grabaron todas las checadas exitosamente, y se sincronizo la hora y fecha');
    }
}
