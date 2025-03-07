<?php

namespace App\Http\Controllers;

use App\Qna;
use Carbon\Carbon;
use App\Incidencia;
use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Configuration;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Calcular incidencias de hoy considerando el desfase horario
        $hoy = Carbon::now()->startOfDay();
        $count = Incidencia::whereRaw('DATE(created_at) = ?', [$hoy->format('Y-m-d')])->count();

        // Obtener configuración de mantenimiento si existe
        $mantenimiento = Configuration::first();

        // Pasar las variables a la vista
        return view('dashboard', compact('count', 'mantenimiento'));
    }

    public function getIncidenciasHoy()
    {
        $hoy = Carbon::now()->startOfDay();
        $count = Incidencia::whereRaw('DATE(created_at) = ?', [$hoy->format('Y-m-d')])->count();

        return response()->json([
            'count' => $count,
            'time' => Carbon::now()->format('H:i:s')
        ]);
    }
}
