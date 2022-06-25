<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Checada;
use Carbon\Carbon;
use Rats\Zkteco\Lib\ZKTeco;
use DateTime;
use DatePeriod;
use DateInterval;

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
        //$zk2 = new ZKTeco("192.160.141.38");
        $zk2 = new ZKTeco("192.161.172.42");
        $zk2->connect();
        
        sleep(1);
        $checadas_2 =  $zk2->getAttendance();
        sleep(1);
        $zk2->setTime($t);
        sleep(1);
        $zk2->disconnect();

        //BIOMETRICO 3 EBDI34
       
    /* $zk3 = new ZKTeco("192.161.170.253");
        $zk3->connect();
        sleep(1);
        $checadas_3 =  $zk3->getAttendance();
        sleep(1);
        $zk3->disconnect();
    */  
        $checadas = array_merge($checadas_1, $checadas_2);
        
        $progressBar = $this->output->createProgressBar(count($checadas));
        $this->info('Iniciando Guardado en base de datos...'."\n");
        $progressBar->start();


        foreach($checadas as $checada){
            $identificador = md5($checada['id'].date("Y-m-d", strtotime($checada['timestamp'])).date("H:i", strtotime($checada['timestamp'])));

            if(!Checada::where('identificador', $identificador)->exists()){
                Checada::create([
                    'num_empleado' => $checada['id'],
                    'fecha'    => date("Y-m-d H:i:s", strtotime($checada['timestamp'])),
                    //'time'    => date("H:i", strtotime($checada['timestamp'])),
                    'identificador' => $identificador,
                ]);
            }
	    $progressBar->advance();
        }
	$progressBar->finish();

	$this->info("\n".'Se descargaron y grabaron todas las checadas exitosamente, y se sincronizo la hora y fecha');
    }
}
