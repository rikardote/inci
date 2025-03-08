<?php

namespace App\Http\Controllers;

use App\Incidencia;

use App\Http\Requests;
use Illuminate\Http\Request;

class LogsController extends Controller
{
    public function getIncidencias(Request $request)
    {
        $limit = $request->input('limit', 100);  // Por defecto 100 registros
        $page = $request->input('page', 1);      // Por defecto página 1

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

        // Usar paginación de Laravel
        $incidencias = $query->paginate($limit);

        return response()->json($incidencias);
    }
}
