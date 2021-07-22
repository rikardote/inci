<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Rats\Zkteco\Lib\ZKTeco;

class BiometricoTime extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'biometrico:time';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Comando para actualizar horario del biometrico';

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
        $t = date("Y-m-d H:i:s");

        $zk = new ZKTeco("192.160.141.37");
        
        $zk->connect();

        sleep(1);
        $zk->setTime($t);
        $time = $zk->getTime();

        sleep(1);
        $zk->disconnect();

        $zk2 = new ZKTeco("192.160.141.38");

        $zk2->connect();
        sleep(1);

        $zk2->setTime($t);
        $time2 = $zk2->getTime();
        

        sleep(1);
        $zk2->disconnect();

        $this->info("\n".'La hora y fecha actual del biometrico es: '.$time.' - '.$time2);
    }
}
