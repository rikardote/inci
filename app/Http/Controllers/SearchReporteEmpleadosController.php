<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Employe;
use App\Qna;
use App\Incidencia;
class SearchReporteEmpleadosController extends Controller
{
	public function index(Request $request)
	{	
		
		$dptos = \Auth::user()->centros->pluck('id')->toArray();
		$query = $request->num;
		$qnas = Qna::all()->pluck('Qnaa', 'id')->toArray();
		krsort($qnas);
        $empleado = Employe::getEmpleadoSearch($query, $dptos);
       
        if (!$empleado) {
			$noencontrado = " <strong><i class='fa fa-exclamation-triangle'></i> Atencion!</strong><br>Empleado no encontrado, o no pertenece a su adscripcion.<br>Informacion en Recursos Humanos";
			$empleado = NULL;
		}
		else {
			$noencontrado = null;
		}
		$title = "Reporte General por empleado";
		return view('reportes.kardex.empleado')
			->with('empleado', $empleado)	
			->with('noencontrado', $noencontrado)
			->with('qnas', $qnas)
			->with('title', $title);
	
	}
	public function vacaciones(Request $request)
	{	
		$qnas = Qna::where('active', '=', 1)->get();
		
		$dptos = \Auth::user()->centros->pluck('id')->toArray();
		$query = $request->num;

		$incidencias = Incidencia::getEmpleadoSearchVacaciones($query, $dptos);
		//dd($incidencias);

		if (!$incidencias) {
			$noencontrado = "Empleado no encontrado, o no pertenece a su adscripcion.<br>Informacion en Recursos Humanos";
			$incidencias = NULL;
			return view('incidencias.noencontrado')->with('error', $noencontrado);
		}
		else {
			$noencontrado = null;

			return view('reportes.vacaciones.show')
				->with('incidencias', $incidencias);
		
			}
	}
}