<?php

namespace App\Http\Controllers;

use Request;
use Response;
use App\Http\Requests;
use App\Employe;
use App\Deparment;
use App\Qna;
use App\Periodo;
use App\Incidencia;
use App\Codigo_De_Incidencia;
use Carbon\Carbon;

class CapturarController extends Controller
{
    public function index()
    {
    	$title = "Captura de Incidencias";

    	$qnas = Qna::where('active', '=', 1)->get();
    	return view('admin.capturar.index')
    		->with('title', $title)
    		->with('qnas', $qnas);
    }
    public function centro($qna_id)
    {
    	$title = "Captura de Incidencias";
    	$incidencias = Incidencia::getAllIncidenciasCentro($qna_id);
//dd($incidencias);
    	return view('admin.capturar.centros')
    		->with('title', $title)
    		->with('incidencias', $incidencias);
    }
    public function capturar_centro($qna_id, $dpto_code)
    {
    	$dpto = Deparment::where('code', '=' , $dpto_code)->first();
    	$incidencias = Incidencia::getIncidenciasCentroCapturar($qna_id, $dpto->id);

    	$title = "Captura de Incidencias";


    	return view('admin.capturar.all-centros')
    		->with('title', $title)
            ->with('qna_id', $qna_id)
            ->with('dpto_code', $dpto->code)
    		->with('incidencias', $incidencias);

    }
    public function grupo($qna_id, $dpto_code, $grupo)
    {
        $title = "Captura de Incidencias";

        $incidencias = Incidencia::getAllIncidenciasPorGrupo($qna_id, $dpto_code, $grupo);

    	return view('admin.capturar.all-centros')
            ->with('title', $title)
            ->with('qna_id', $qna_id)
            ->with('dpto_code', $dpto_code)
            ->with('incidencias', $incidencias);
    }
    public function capturado($incidencia_id)
    {
       if (Request::ajax()) {

            $incidencia = Incidencia::find($incidencia_id);
            $incidencia->capturada = ($incidencia->capturada) ? FALSE : TRUE;

            $incidencia->save();
             $response = array(
                'success' => 'true'
             );
            return Response::json($response); //redirect()->route('qnas.index');

        }
    }

}
