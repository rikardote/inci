<?php

namespace App\Http\Controllers;

use Carbon\Carbon;

use App\Configuration;
use App\Http\Requests;
use Illuminate\Http\Request;

class MantenimientoController extends Controller
{
    public function index(){
            return view('admin.mantenimiento.index');
    }
    public function show(){
            $mantenimiento = Configuration::where('name', "mantenimiento")->first();

        // Formatear fecha
        $fecha_actualizacion = 'No disponible';
        if ($mantenimiento->updated_at) {
            $dt = Carbon::parse($mantenimiento->updated_at);
            $mes = $dt->format('F');
            $meses_es = [
                'January' => 'enero',
                'February' => 'febrero',
                /* etc... */
            ];
            $mes = $meses_es[$mes] ?? $mes;
            $fecha_actualizacion = $dt->day . ' de ' . $mes . ' de ' . $dt->year;
        }

        return view('admin.mantenimiento.show', [
            'mantenimiento' => $mantenimiento,
            'fecha_actualizacion' => $fecha_actualizacion
        ]);

    }
    public function state(){
        $mantenimiento = Configuration::where('name', "mantenimiento")->first();
        $mantenimiento->state = ($mantenimiento->state) ? FALSE : TRUE;

        $mantenimiento->save();
        return redirect()->route('dashboard.index');


    }
    public function toggle(Request $request)
    {
        try {
            $mantenimiento = Configuration::where('name', 'mantenimiento')->first();

            if (!$mantenimiento) {
                $mantenimiento = new Configuration();
                $mantenimiento->name = 'mantenimiento';
                $mantenimiento->state = false;
            }

            // Cambiar el estado
            $mantenimiento->state = !$mantenimiento->state;
            $mantenimiento->save();

            // Devolver respuesta correcta
            return response()->json([
                'success' => true,
                'state' => $mantenimiento->state
            ]);
        } catch (\Exception $e) {
            \Log::error('Error en toggle de mantenimiento: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
    public function respaldo()
    {
        try {
            // Ruta personalizada para los respaldos
            $backupDir = '/home/ricardo/respaldo/incidencias_backup'; // Cambia esta ruta a la carpeta deseada
            if (!file_exists($backupDir)) {
                mkdir($backupDir, 0755, true);
            }

            // Listar archivos de respaldo existentes
            $backups = array_diff(scandir($backupDir), ['.', '..']);

            // Si se solicita un respaldo
            if (request()->has('action') && request()->get('action') === 'create') {
                $filename = 'backup_' . date('Y_m_d_H_i_s') . '.sql';
                $filePath = $backupDir . DIRECTORY_SEPARATOR . $filename;

                // Comando para generar el respaldo (asegúrate de configurar correctamente las credenciales)
                $dbHost = env('DB_HOST', '127.0.0.1');
                $dbName = env('DB_DATABASE', '');
                $dbUser = env('DB_USERNAME', '');
                $dbPass = env('DB_PASSWORD', '');

                $command = "mysqldump -h {$dbHost} -u {$dbUser} " .
                           (!empty($dbPass) ? "-p {$dbPass} " : "") .
                           "{$dbName} > \"{$filePath}\"";

                // Ejecutar el comando
                $output = null;
                $resultCode = null;
                exec($command, $output, $resultCode);

                if ($resultCode !== 0) {
                    throw new \Exception('Error al generar el respaldo de la base de datos.');
                }

                // Comprimir el archivo SQL
                $zipFile = $backupDir . DIRECTORY_SEPARATOR . basename($filename, '.sql') . '.zip';
                $zip = new \ZipArchive();
                if ($zip->open($zipFile, \ZipArchive::CREATE) === true) {
                    $zip->addFile($filePath, basename($filePath));
                    $zip->close();

                    // Eliminar el archivo SQL original
                    unlink($filePath);
                } else {
                    throw new \Exception('Error al comprimir el archivo de respaldo.');
                }

                // Actualizar la lista de respaldos
                $backups = array_diff(scandir($backupDir), ['.', '..']);
            }

            return view('admin.mantenimiento.respaldo', [
                'backups' => $backups,
            ]);
        } catch (\Exception $e) {
            \Log::error('Error en respaldo de base de datos: ' . $e->getMessage());
            return redirect()->back()->withErrors(['error' => 'No se pudo realizar el respaldo: ' . $e->getMessage()]);
        }
    }
    public function descargar(Request $request)
    {
        $backupDir = '/home/ricardo/respaldo/incidencias_backup/'; // Ruta personalizada
        $file = $request->get('file');
        $filePath = $backupDir . DIRECTORY_SEPARATOR . $file;

        if (file_exists($filePath)) {
            return response()->download($filePath);
        }

        return redirect()->back()->withErrors(['error' => 'El archivo no existe.']);
    }

}
