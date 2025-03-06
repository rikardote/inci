<?php

namespace App\Console\Commands;

use Log;
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
// En la función procesarChecadas:
    private function generarIdentificador($checada, $location) {
        // Formato más preciso para el timestamp
        $timestamp = date("YmdHi", strtotime($checada['timestamp'])); // YYYYMMDDHHMM

        return implode('_', [
            $checada['id'],                                   // ID del empleado
            $timestamp,                                       // Timestamp en minutos
            str_replace([' ', '-'], '', $location)           // Location sin espacios
        ]);
    }

    private function esChecadaDuplicada($checada, $location) {
        // Obtener el rango de +-1 minuto del timestamp actual
        $timestamp = strtotime($checada['timestamp']);
        $minutoAntes = date('Y-m-d H:i:s', $timestamp - 60);
        $minutoDespues = date('Y-m-d H:i:s', $timestamp + 60);

        return Checada::where('num_empleado', $checada['id'])
            ->where('fecha', '>=', $minutoAntes)
            ->where('fecha', '<=', $minutoDespues)
            ->exists();
    }
    private function procesarChecadas($checadas, $location, $progressBar)
    {
        $timeStart = microtime(true);
        if (empty($checadas)) {
            return;
        }

        try {
            $registrosParaInsertar = array();
            $identificadores = array();
            $totalRegistros = 0;

            // Ya no creamos una nueva barra de progreso aquí
            // Usamos la que recibimos como parámetro

            // Fase 1: Verificación de duplicados y preparación
            $this->info("\nVerificando duplicados en {$location}...");
            foreach($checadas as $checada) {
                if (!$this->esChecadaDuplicada($checada, $location)) {
                    $identificador = $this->generarIdentificador($checada, $location);
                    $identificadores[] = $identificador;

                    $registrosParaInsertar[$identificador] = array(
                        'num_empleado' => $checada['id'],
                        'fecha' => date("Y-m-d H:i:s", strtotime($checada['timestamp'])),
                        'identificador' => $identificador,
                        'created_at' => date('Y-m-d H:i:s')
                    );
                }
                $progressBar->advance();
            }

            // Fase 2: Filtrado y procesamiento
            $this->info("\nProcesando registros en {$location}...");
            $existentes = Checada::whereIn('identificador', $identificadores)
                ->pluck('identificador')
                ->toArray();

            $registrosNuevos = array();
            foreach($registrosParaInsertar as $identificador => $registro) {
                if (!in_array($identificador, $existentes)) {
                    $registrosNuevos[] = $registro;
                    $totalRegistros++;
                }
                // Quitamos el segundo advance() aquí
            }

            // Fase 3: Inserción en lotes
            if (!empty($registrosNuevos)) {
                $insertados = 0;
                $totalLotes = ceil(count($registrosNuevos) / 300);

                foreach (array_chunk($registrosNuevos, 300) as $index => $chunk) {
                    try {
                        $inserted = Checada::insert($chunk);
                        if ($inserted) {
                            $insertados += count($chunk);
                            $porcentajeLote = round(($index + 1) / $totalLotes * 100);
                            $this->info("\nProgreso de inserción: {$porcentajeLote}% completado");
                        }
                    } catch (\Exception $e) {
                        log()->error("Error insertando lote en {$location}: " . $e->getMessage());
                    }
                }

                $progressBar->finish();
                $this->info("\nSe insertaron {$insertados} de {$totalRegistros} registros nuevos en {$location}");
            } else {
                $this->info("\nNo hay registros nuevos para insertar en {$location}");
            }

        } catch (\Exception $e) {
            $this->error("\nError al procesar registros en {$location}: " . $e->getMessage());
            log()->error("Error en procesamiento de checadas: " . $e->getMessage());
        }

        $timeEnd = microtime(true);
        $timeElapsed = round($timeEnd - $timeStart, 2);
        $this->info("\nTiempo de procesamiento: {$timeElapsed} segundos");
    }

    public function handle()
    {
        $this->info('Iniciando descarga de checadas espere un momento:' . "\n");
        date_default_timezone_set('America/Tijuana');

        $dispositivos = array(
            array('ip' => '192.160.141.37', 'location' => 'Delegación Principal'),
            array('ip' => '192.160.169.230', 'location' => 'Almacén'),
            array('ip' => '192.165.240.253', 'location' => 'San Felipe'),
            array('ip' => '192.165.232.253', 'location' => 'Los Algodones'),
            array('ip' => '192.165.171.253', 'location' => 'Tecate')
        );

        foreach ($dispositivos as $dispositivo) {
            try {
                $zk = new ZKTeco($dispositivo['ip']);

                if ($zk->connect()) {
                    sleep(1);
                    $this->info("\nDescargando registros de {$dispositivo['location']}...");
                    $checadas = $zk->getAttendance();

                    if (!empty($checadas)) {
                        $progressBar = $this->output->createProgressBar(count($checadas));
                        $progressBar->start();

                        sleep(1);
                        $zk->setTime(date("Y-m-d H:i:s"));
                        sleep(1);
                        $zk->disconnect();

                        $this->procesarChecadas($checadas, $dispositivo['location'], $progressBar);
                        $progressBar->finish();
                        $this->line(''); // Nueva línea después de la barra
                    } else {
                        $this->info("\nNo hay registros nuevos en {$dispositivo['location']}");
                    }
                } else {
                    $this->error("\nNo se pudo conectar al dispositivo en {$dispositivo['location']}");
                }
            } catch (\Exception $e) {
                $this->error("\nError en {$dispositivo['location']}: " . $e->getMessage());
            }

            sleep(1);
        }

        $this->info("\nProceso completado exitosamente");
    }


}
