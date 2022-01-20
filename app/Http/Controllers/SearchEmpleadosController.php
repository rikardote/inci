<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Employe;
use App\Qna;
use App\Periodo;
use App\Codigo_De_Incidencia;
use App\Incidencia;
use App\Comentario;

class SearchEmpleadosController extends Controller
{
	public function index(Request $request)
	{	
		$qnas = Qna::where('active', '=', 1)->get();
		
		$dptos = \Auth::user()->centros->pluck('id')->toArray();
		$query = $request->num;

        $empleado = Employe::getEmpleadoSearch($query, $dptos);
        $ids = ['24','25','28','30','56','57','58','59','60','61','62','63','64','65','66','67','68','87','88','101','95','96','97','98'];
       	$medicos = Employe::orderBy('num_empleado', 'asc')->whereIn('puesto_id', $ids)->get();

        if (!$empleado) {
			$noencontrado = "Empleado no encontrado, o no pertenece a su adscripcion.<br>Informacion en Recursos Humanos";
			$empleado = NULL;
			$incidencias = null;
			return view('incidencias.noencontrado')->with('error', $noencontrado);
		}
		else {
			$noencontrado = null;
			$periodos = Periodo::orderBy('year', 'desc')->orderBy('periodo', 'desc')->get();
        	$codigosdeincidencias = Codigo_De_Incidencia::all()->pluck('codigo', 'id')->toArray();
			natcasesort($codigosdeincidencias);
			$incidencias = Incidencia::getIncidencias($empleado->num_empleado);
			$comentario = Comentario::where('employee_id', '=', $empleado->emp_id)->get()->first();

			return view('incidencias.create')
				->with('incidencias', $incidencias)	
				->with('employee', $empleado)	
				->with('noencontrado', $noencontrado)
				->with('qnas', $qnas)
				->with('periodos', $periodos)
	            ->with('codigosdeincidencias', $codigosdeincidencias)
	            ->with('comment', $comentario)
	            ->with('medicos', $medicos);
		
			}
	}
		/*if (isset($empleado) && isset($request->qna_id))  {
			return redirect()->route('admin.incidencias.create',['num_empleado' => $empleado->num_empleado, 'qna_id' => $request->qna_id]);
		}
		if (isset($noencontrado) && isset($request->qna_id))  {
			return view('incidencias.noencontrado')->with('error', $noencontrado)->with('qna_id', $request->qna_id);
		}*/


		
}
