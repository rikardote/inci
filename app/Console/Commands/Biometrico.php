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

        $progressBar = $this->output->createProgressBar(count($checadas_1));
        $this->info('Iniciando Guardado en base de datos de checador principal delegacion...'."\n");
        $progressBar->start();
            foreach($checadas_1 as $checada){
                $identificador = md5($checada['id'].date("Y-m-d", strtotime($checada['timestamp'])).date("H:i", strtotime($checada['timestamp'])));

                if(!Checada::where('identificador', $identificador)->exists()){
                    DB::table('checadas')->insert([
                        'num_empleado' => $checada['id'],
                        'fecha'    => date("Y-m-d H:i:s", strtotime($checada['timestamp'])),
                        'identificador' => $identificador,
                    ]);
                }
            $progressBar->advance();
            }
	    $progressBar->finish();

        //BIOMETRICO 2 DELEGACION (COMEDOR)
        $zk2 = new ZKTeco("192.160.141.38");
        $zk2->connect();
        sleep(1);
        $checadas_2 =  $zk2->getAttendance();
        sleep(1);
        $zk2->setTime(date("Y-m-d H:i:s"));
        sleep(1);
        $zk2->disconnect();

        $progressBar = $this->output->createProgressBar(count($checadas_2));
        $this->info('Iniciando Guardado en base de datos de checador principal comedor...'."\n");
        $progressBar->start();
            foreach($checadas_2 as $checada2){
                $identificador2 = md5($checada2['id'].date("Y-m-d", strtotime($checada2['timestamp'])).date("H:i", strtotime($checada2['timestamp'])));

                if(!Checada::where('identificador', $identificador2)->exists()){
                    DB::table('checadas')->insert([
                        'num_empleado' => $checada2['id'],
                        'fecha'    => date("Y-m-d H:i:s", strtotime($checada2['timestamp'])),
                        'identificador' => $identificador2,
                    ]);
                }
            $progressBar->advance();
            }
	    $progressBar->finish();

        //BIOMETRICO 3 ALGODONES

        $zk3 = new ZKTeco("192.165.232.253");
        $zk3->connect();
        sleep(1);
        $checadas_3 =  $zk3->getAttendance();
        sleep(1);
        $zk3->setTime(date("Y-m-d H:i:s"));
        sleep(1);
        $zk3->disconnect();

        $progressBar = $this->output->createProgressBar(count($checadas_3));
        $this->info('Iniciando Guardado en base de datos de checador los algodones...'."\n");
        $progressBar->start();
            foreach($checadas_3 as $checada3){
                $identificador3 = md5($checada3['id'].date("Y-m-d", strtotime($checada3['timestamp'])).date("H:i", strtotime($checada3['timestamp'])));

                if(!Checada::where('identificador', $identificador3)->exists()){
                    DB::table('checadas')->insert([
                        'num_empleado' => $checada3['id'],
                        'fecha'    => date("Y-m-d H:i:s", strtotime($checada3['timestamp'])),
                        'identificador' => $identificador3,
                    ]);
                }
            $progressBar->advance();
            }
	    $progressBar->finish();

        //BIOMETRICO 4 SAN FELIPE
        $zk4 = new ZKTeco("192.165.240.253");
        $zk4->connect();
        sleep(1);
        $checadas_4 =  $zk4->getAttendance();
        sleep(1);
        $zk4->setTime(date("Y-m-d H:i:s"));
        sleep(1);
        $zk4->disconnect();

        $progressBar = $this->output->createProgressBar(count($checadas_4));
        $this->info('Iniciando Guardado en base de datos de checador los algodones...'."\n");
        $progressBar->start();
            foreach($checadas_4 as $checada4){
                $identificador4 = md5($checada4['id'].date("Y-m-d", strtotime($checada4['timestamp'])).date("H:i", strtotime($checada4['timestamp'])));

                if(!Checada::where('identificador', $identificador4)->exists()){
                    DB::table('checadas')->insert([
                        'num_empleado' => $checada4['id'],
                        'fecha'    => date("Y-m-d H:i:s", strtotime($checada4['timestamp'])),
                        'identificador' => $identificador4,
                    ]);
                }
            $progressBar->advance();
            }
	    $progressBar->finish();

        //BIOMETRICO 5 TECATE
        $zk5 = new ZKTeco("192.165.171.253");
        $zk5->connect();
        sleep(1);
        $checadas_5 =  $zk5->getAttendance();
        sleep(1);
        $zk5->setTime(date("Y-m-d H:i:s"));
        sleep(1);
        $zk5->disconnect();

        $progressBar = $this->output->createProgressBar(count($checadas_5));
        $this->info('Iniciando Guardado en base de datos de checador los algodones...'."\n");
        $progressBar->start();
            foreach($checadas_5 as $checada5){
                $identificador5 = md5($checada5['id'].date("Y-m-d", strtotime($checada5['timestamp'])).date("H:i", strtotime($checada5['timestamp'])));

                if(!Checada::where('identificador', $identificador5)->exists()){
                    DB::table('checadas')->insert([
                        'num_empleado' => $checada5['id'],
                        'fecha'    => date("Y-m-d H:i:s", strtotime($checada5['timestamp'])),
                        'identificador' => $identificador5,
                    ]);
                }
            $progressBar->advance();
            }
	    $progressBar->finish();

        //BIOMETRICO 6 OTAY
        $zk6 = new ZKTeco("192.168.201.7");
        $zk6->connect();
        sleep(1);
        $checadas_6 =  $zk6->getAttendance();
        sleep(1);
        $zk6->setTime(date("Y-m-d H:i:s"));
        sleep(1);
        $zk6->disconnect();

        $progressBar = $this->output->createProgressBar(count($checadas_6));
        $this->info('Iniciando Guardado en base de datos de checador los algodones...'."\n");
        $progressBar->start();
            foreach($checadas_6 as $checada6){
                $identificador6 = md5($checada6['id'].date("Y-m-d", strtotime($checada6['timestamp'])).date("H:i", strtotime($checada6['timestamp'])));

                if(!Checada::where('identificador', $identificador6)->exists()){
                    DB::table('checadas')->insert([
                        'num_empleado' => $checada6['id'],
                        'fecha'    => date("Y-m-d H:i:s", strtotime($checada6['timestamp'])),
                        'identificador' => $identificador6,
                    ]);
                }
            $progressBar->advance();
            }
	    $progressBar->finish();
/*
        $checadas = array_merge($checadas_1, $checadas_2, $checadas_3, $checadas_4, $checadas_5, $checadas_6);
        //$checadas = array_merge($checadas_1, $checadas_2);
        $progressBar = $this->output->createProgressBar(count($checadas));
        $this->info('Iniciando Guardado en base de datos...'."\n");
        $progressBar->start();
            foreach($checadas as $checada){
                $identificador = md5($checada['id'].date("Y-m-d", strtotime($checada['timestamp'])).date("H:i", strtotime($checada['timestamp'])));

                if(!Checada::where('identificador', $identificador)->exists()){
                    DB::table('checadas')->insert([
                        'num_empleado' => $checada['id'],
                        'fecha'    => date("Y-m-d H:i:s", strtotime($checada['timestamp'])),
                        'identificador' => $identificador,
                    ]);
                }
            $progressBar->advance();
            }
	$progressBar->finish();
*/
	$this->info("\n".'Se descargaron y grabaron todas las checadas exitosamente, y se sincronizo la hora y fecha');
    }
}
