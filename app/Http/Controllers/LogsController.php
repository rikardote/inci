<?php

namespace App\Http\Controllers;

use App\Incidencia;
use App\Http\Requests;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log; // Importa la clase Log

class LogsController extends Controller
{
    public function getIncidencias(Request $request)
    {
        try {
            $limit = $request->input('limit', 100);  // Por defecto 100 registros
            $page = $request->input('page', 1);      // Por defecto página 1
            $date = $request->input('date');          // Fecha para filtrar

            $query = Incidencia::withTrashed()->with([
                'employee' => function($query) {
                    $query->select('id', 'deparment_id', 'num_empleado', 'name', 'father_lastname', 'mother_lastname');
                },
                'employee.deparment' => function($query) {
                    $query->select('id', 'code');
                },
                'codigodeincidencia' => function($query) {
                    $query->select('id', 'code');
                },
                'periodo' => function($query) {
                    $query->select('id', 'periodo', 'year');
                }
            ])
            ->select([
                'incidencias.id',
                'incidencias.employee_id',
                'incidencias.codigodeincidencia_id',
                'incidencias.periodo_id',
                'incidencias.fecha_inicio',
                'incidencias.fecha_final',
                'incidencias.total_dias',
                'incidencias.created_at',
                'incidencias.deleted_at'
            ])
            ->orderBy('created_at', 'desc');

            // Filtrar por fecha si se proporciona
            if ($date) {
                $query->whereDate('created_at', $date);
            }

            // Usar paginación de Laravel
            $incidencias = $query->paginate($limit);

            return response()->json($incidencias);

        } catch (\Exception $e) {
            Log::error('Error en LogsController::getIncidencias: ' . $e->getMessage() . "\n" . $e->getTraceAsString());
            return response()->json(['error' => 'Internal Server Error'], 500);
        }
    }
    public function checkForUpdates(Request $request)
    {
        try {
            $lastUpdate = $request->input('last_update');

            if (!$lastUpdate) {
                return response()->json(['has_updates' => true]);
            }

            $lastUpdate = Carbon::parse($lastUpdate);

            $hasUpdates = Incidencia::withTrashed()
                ->where('created_at', '>', $lastUpdate)
                ->orWhere('updated_at', '>', $lastUpdate)
                ->orWhere('deleted_at', '>', $lastUpdate)
                ->exists();

            return response()->json(['has_updates' => $hasUpdates]);

        } catch (\Exception $e) {
            Log::error('Error en LogsController::checkForUpdates: ' . $e->getMessage() . "\n" . $e->getTraceAsString());
            return response()->json(['error' => 'Internal Server Error'], 500);
        }
    }
}
