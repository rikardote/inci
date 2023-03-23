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

class Biometrico5dic extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'biometrico5dic:checadas';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Comando para descargar checadas del 5 de diciembre biometrico diario';

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
        $progressBar = $this->output->createProgressBar(count($checadas));
        $this->info('Iniciando Guardado en base de datos...'."\n");
        $progressBar->start();
            foreach($checadas as $checada){
                $identificador = md5($checada['id'].date("Y-m-d", strtotime($checada['timestamp'])).date("H:i", strtotime($checada['timestamp'])));

                if(!Checada::where('identificador', $identificador)->exists()){
                    $checada->fill([
                        'num_empleado' => $checada['id'],
                        'fecha'    => date("Y-m-d H:i:s", strtotime($checada['timestamp'])),
                        'identificador' => $identificador,
                        'created_at' => date('Y-m-d H:i:s')
                    ]);
                }
            $progressBar->advance();
            }
            $checada->save();
	$progressBar->finish();

	$this->info("\n".'Se descargaron y grabaron todas las checadas exitosamente, y se sincronizo la hora y fecha');
    }
}
