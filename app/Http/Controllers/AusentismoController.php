<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Deparment;
use App\Employe;
use App\Puesto;
use App\Horario;
use App\Condicion;
use App\Incidencia;
use App\Http\Requests\EmployessRequest;
use Laracasts\Flash\Flash;

class AusentismoController extends Controller
{
	public function index()
	{ 
		$deparments = Deparment::all()->pluck('deparment', 'id')->toArray();
		return view('admin.ausentismo.index')->with('deparments', $deparments);
	}
	public function delegacion(Request $request)
	{
		$fecha_inicial = fecha_ymd($request->fecha_inicio);
		$fecha_final = fecha_ymd($request->fecha_final);
 		
 		//$dptos = \Auth::user()->centros->pluck('id')->toArray();
		 //$codigos = ['10','14', '17', '40','41','46','47','48','49','51','53','54','55','60','62','63','94','100'];
		$codigo_01 = Incidencia::getTotalIncidenciasPorConceptoDel($fecha_inicial, $fecha_final, 1); 
		$codigo_02 = Incidencia::getTotalIncidenciasPorConceptoDel($fecha_inicial, $fecha_final, 2); 
		$codigo_03 = Incidencia::getTotalIncidenciasPorConceptoDel($fecha_inicial, $fecha_final, 3); 
 	    $codigo_10 = Incidencia::getTotalIncidenciasPorConceptoDel($fecha_inicial, $fecha_final, 10);	
 	    $codigo_14 = Incidencia::getTotalIncidenciasPorConceptoDel($fecha_inicial, $fecha_final, 14);
 	    $codigo_17 = Incidencia::getTotalIncidenciasPorConceptoDel($fecha_inicial, $fecha_final, 17);		
 	    $codigo_40 = Incidencia::getTotalIncidenciasPorConceptoDel($fecha_inicial, $fecha_final, 40);	
 	    $codigo_41 = Incidencia::getTotalIncidenciasPorConceptoDel($fecha_inicial, $fecha_final, 41);	
 	    $codigo_42 = Incidencia::getTotalIncidenciasPorConceptoDel($fecha_inicial, $fecha_final, 42);	
 	    $codigo_46 = Incidencia::getTotalIncidenciasPorConceptoDel($fecha_inicial, $fecha_final, 46);
 	    $codigo_47 = Incidencia::getTotalIncidenciasPorConceptoDel($fecha_inicial, $fecha_final, 47);	
 	    $codigo_48 = Incidencia::getTotalIncidenciasPorConceptoDel($fecha_inicial, $fecha_final, 48);
 	    $codigo_49 = Incidencia::getTotalIncidenciasPorConceptoDel($fecha_inicial, $fecha_final, 49);
 	    $codigo_51 = Incidencia::getTotalIncidenciasPorConceptoDel($fecha_inicial, $fecha_final, 51);			
 	    $codigo_53 = Incidencia::getTotalIncidenciasPorConceptoDel($fecha_inicial, $fecha_final, 53);
 	    $codigo_54 = Incidencia::getTotalIncidenciasPorConceptoDel($fecha_inicial, $fecha_final, 54);	
 	    $codigo_55 = Incidencia::getTotalIncidenciasPorConceptoDel($fecha_inicial, $fecha_final, 55);
 	    $codigo_60 = Incidencia::getTotalIncidenciasPorConceptoDel($fecha_inicial, $fecha_final, 60);
 	    $codigo_62 = Incidencia::getTotalIncidenciasPorConceptoDel($fecha_inicial, $fecha_final, 62);
 	    $codigo_63 = Incidencia::getTotalIncidenciasPorConceptoDel($fecha_inicial, $fecha_final, 63);				
 	    $codigo_94 = Incidencia::getTotalIncidenciasPorConceptoDel($fecha_inicial, $fecha_final, 94);	
		$codigo_100 = Incidencia::getTotalIncidenciasPorConceptoDel($fecha_inicial, $fecha_final, 100);
		$codigo_907 = Incidencia::getTotalIncidenciasPorConceptoDel($fecha_inicial, $fecha_final, 907);		
		

		 return view('admin.ausentismo.delegacion')
		 	->with('codigo_01', $codigo_01)
		 	->with('codigo_02', $codigo_02)
		 	->with('codigo_03', $codigo_03)
 	    	->with('codigo_10', $codigo_10)
 	    	->with('codigo_14', $codigo_14)
 	    	->with('codigo_17', $codigo_17)
 	    	->with('codigo_40', $codigo_40)
 	    	->with('codigo_41', $codigo_41)
 	    	->with('codigo_42', $codigo_42)
 	    	->with('codigo_46', $codigo_46)
 	    	->with('codigo_47', $codigo_47)
			->with('codigo_48', $codigo_48)
			->with('codigo_49', $codigo_49)
			->with('codigo_51', $codigo_51)
			->with('codigo_53', $codigo_53)
			->with('codigo_54', $codigo_54)
			->with('codigo_55', $codigo_55)
			->with('codigo_60', $codigo_60)
			->with('codigo_62', $codigo_62)
			->with('codigo_63', $codigo_63)
			->with('codigo_94', $codigo_94)
			->with('codigo_100', $codigo_100)
			->with('codigo_907', $codigo_907);
	
			
	}
	public function empleado(Request $request)
	{
		$fecha_inicial = fecha_ymd($request->fecha_inicio);
		$fecha_final = fecha_ymd($request->fecha_final);
		$num_empleado = $request->num_empleado;
		$empleado = Employe::where('num_empleado', '=', $num_empleado)->first();
		
		

 		$dpto_id = $request->deparment_id;
 		if ($empleado!=null) {
	 			# code...
	 		$title = $empleado->name.' '.$empleado->father_lastname.' '.$empleado->mother_lastname.' / '.$request->fecha_inicio.' - '.$request->fecha_final;	
	 		//$codigos = ['10','14', '17', '40','41','46','47','48','49','51','53','54','55','60','62','63','94','100'];
	 	    $codigo_10 = Incidencia::getTotalIncidenciasPorEmpleado($fecha_inicial, $fecha_final,$dpto_id, $num_empleado, 10);	
	 	    $codigo_14 = Incidencia::getTotalIncidenciasPorEmpleado($fecha_inicial, $fecha_final,$dpto_id,  $num_empleado, 14);
	 	    $codigo_17 = Incidencia::getTotalIncidenciasPorEmpleado($fecha_inicial, $fecha_final,$dpto_id, $num_empleado, 17);		
	 	    $codigo_40 = Incidencia::getTotalIncidenciasPorEmpleado($fecha_inicial, $fecha_final,$dpto_id, $num_empleado, 40);	
	 	    $codigo_41 = Incidencia::getTotalIncidenciasPorEmpleado($fecha_inicial, $fecha_final,$dpto_id, $num_empleado, 41);	
	 	    $codigo_42 = Incidencia::getTotalIncidenciasPorEmpleado($fecha_inicial, $fecha_final,$dpto_id, $num_empleado, 42);	
	 	    $codigo_46 = Incidencia::getTotalIncidenciasPorEmpleado($fecha_inicial, $fecha_final,$dpto_id, $num_empleado, 46);
	 	    $codigo_47 = Incidencia::getTotalIncidenciasPorEmpleado($fecha_inicial, $fecha_final,$dpto_id, $num_empleado, 47);	
	 	    $codigo_48 = Incidencia::getTotalIncidenciasPorEmpleado($fecha_inicial, $fecha_final,$dpto_id, $num_empleado, 48);
	 	    $codigo_49 = Incidencia::getTotalIncidenciasPorEmpleado($fecha_inicial, $fecha_final,$dpto_id, $num_empleado, 49);
	 	    $codigo_51 = Incidencia::getTotalIncidenciasPorEmpleado($fecha_inicial, $fecha_final,$dpto_id, $num_empleado, 51);			
	 	    $codigo_53 = Incidencia::getTotalIncidenciasPorEmpleado($fecha_inicial, $fecha_final,$dpto_id, $num_empleado, 53);
	 	    $codigo_54 = Incidencia::getTotalIncidenciasPorEmpleado($fecha_inicial, $fecha_final,$dpto_id, $num_empleado, 54);	
	 	    $codigo_55 = Incidencia::getTotalIncidenciasPorEmpleado($fecha_inicial, $fecha_final,$dpto_id, $num_empleado, 55);
	 	    $codigo_60 = Incidencia::getTotalIncidenciasPorEmpleado($fecha_inicial, $fecha_final,$dpto_id, $num_empleado, 60);
	 	    $codigo_62 = Incidencia::getTotalIncidenciasPorEmpleado($fecha_inicial, $fecha_final,$dpto_id, $num_empleado, 62);
	 	    $codigo_63 = Incidencia::getTotalIncidenciasPorEmpleado($fecha_inicial, $fecha_final,$dpto_id, $num_empleado, 63);				
	 	    $codigo_94 = Incidencia::getTotalIncidenciasPorEmpleado($fecha_inicial, $fecha_final,$dpto_id, $num_empleado, 94);	
	 	    $codigo_100 = Incidencia::getTotalIncidenciasPorEmpleado($fecha_inicial, $fecha_final,$dpto_id, $num_empleado, 100);	
			
	 	    return view('admin.ausentismo.empleado')
	 	    	->with('codigo_10', $codigo_10)
	 	    	->with('codigo_14', $codigo_14)
	 	    	->with('codigo_17', $codigo_17)
	 	    	->with('codigo_40', $codigo_40)
	 	    	->with('codigo_41', $codigo_41)
	 	    	->with('codigo_42', $codigo_42)
	 	    	->with('codigo_46', $codigo_46)
	 	    	->with('codigo_47', $codigo_47)
				->with('codigo_48', $codigo_48)
				->with('codigo_49', $codigo_49)
				->with('codigo_51', $codigo_51)
				->with('codigo_53', $codigo_53)
				->with('codigo_54', $codigo_54)
				->with('codigo_55', $codigo_55)
				->with('codigo_60', $codigo_60)
				->with('codigo_62', $codigo_62)
				->with('codigo_63', $codigo_63)
				->with('codigo_94', $codigo_94)
				->with('codigo_100', $codigo_100)
				->with('title', $title);
		}	
		else {
			$deparments = Deparment::all()->pluck('deparment', 'id')->toArray();
			$error = "Empleado no encontrado, o no pertenece a su adscripcion";
			return view('admin.ausentismo.index')->with('deparments', $deparments)->with('error', $error);
		}
			
	}
	public function centro(Request $request)
	{
		$fecha_inicial = fecha_ymd($request->fecha_inicio);
		$fecha_final = fecha_ymd($request->fecha_final);
 		
 		$dpto_id = $request->deparment_id;
 		$dpto = Deparment::where('id','=', $dpto_id)->first();
 		
 		//$codigos = ['10','14', '17', '40','41','46','47','48','49','51','53','54','55','60','62','63','94','100'];
 	    $codigo_10 = Incidencia::getTotalIncidenciasPorCentro($fecha_inicial, $fecha_final,$dpto_id, 10);	
 	    $codigo_14 = Incidencia::getTotalIncidenciasPorCentro($fecha_inicial, $fecha_final,$dpto_id, 14);
 	    $codigo_17 = Incidencia::getTotalIncidenciasPorCentro($fecha_inicial, $fecha_final,$dpto_id, 17);		
 	    $codigo_40 = Incidencia::getTotalIncidenciasPorCentro($fecha_inicial, $fecha_final,$dpto_id, 40);	
 	    $codigo_41 = Incidencia::getTotalIncidenciasPorCentro($fecha_inicial, $fecha_final,$dpto_id, 41);	
 	    $codigo_42 = Incidencia::getTotalIncidenciasPorCentro($fecha_inicial, $fecha_final,$dpto_id, 42);	
 	    $codigo_46 = Incidencia::getTotalIncidenciasPorCentro($fecha_inicial, $fecha_final,$dpto_id, 46);
 	    $codigo_47 = Incidencia::getTotalIncidenciasPorCentro($fecha_inicial, $fecha_final,$dpto_id, 47);	
 	    $codigo_48 = Incidencia::getTotalIncidenciasPorCentro($fecha_inicial, $fecha_final,$dpto_id, 48);
 	    $codigo_49 = Incidencia::getTotalIncidenciasPorCentro($fecha_inicial, $fecha_final,$dpto_id, 49);
 	    $codigo_51 = Incidencia::getTotalIncidenciasPorCentro($fecha_inicial, $fecha_final,$dpto_id, 51);			
 	    $codigo_53 = Incidencia::getTotalIncidenciasPorCentro($fecha_inicial, $fecha_final,$dpto_id, 53);
 	    $codigo_54 = Incidencia::getTotalIncidenciasPorCentro($fecha_inicial, $fecha_final,$dpto_id, 54);	
 	    $codigo_55 = Incidencia::getTotalIncidenciasPorCentro($fecha_inicial, $fecha_final,$dpto_id, 55);
 	    $codigo_60 = Incidencia::getTotalIncidenciasPorCentro($fecha_inicial, $fecha_final,$dpto_id, 60);
 	    $codigo_62 = Incidencia::getTotalIncidenciasPorCentro($fecha_inicial, $fecha_final,$dpto_id, 62);
 	    $codigo_63 = Incidencia::getTotalIncidenciasPorCentro($fecha_inicial, $fecha_final,$dpto_id, 63);				
 	    $codigo_94 = Incidencia::getTotalIncidenciasPorCentro($fecha_inicial, $fecha_final,$dpto_id, 94);	
 	    $codigo_100 = Incidencia::getTotalIncidenciasPorCentro($fecha_inicial, $fecha_final,$dpto_id, 100);	
		
 	    return view('admin.ausentismo.centro')
 	    	->with('codigo_10', $codigo_10)
 	    	->with('codigo_14', $codigo_14)
 	    	->with('codigo_17', $codigo_17)
 	    	->with('codigo_40', $codigo_40)
 	    	->with('codigo_41', $codigo_41)
 	    	->with('codigo_42', $codigo_42)
 	    	->with('codigo_46', $codigo_46)
 	    	->with('codigo_47', $codigo_47)
			->with('codigo_48', $codigo_48)
			->with('codigo_49', $codigo_49)
			->with('codigo_51', $codigo_51)
			->with('codigo_53', $codigo_53)
			->with('codigo_54', $codigo_54)
			->with('codigo_55', $codigo_55)
			->with('codigo_60', $codigo_60)
			->with('codigo_62', $codigo_62)
			->with('codigo_63', $codigo_63)
			->with('codigo_94', $codigo_94)
			->with('codigo_100', $codigo_100)
			->with('dpto', $dpto);;
	
		

    
	}

}