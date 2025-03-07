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
}
