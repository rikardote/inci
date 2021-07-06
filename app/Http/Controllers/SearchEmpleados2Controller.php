<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Employe;
use App\Qna;

use App\Horario;

use App\Jornada;


class SearchEmpleados2Controller extends Controller
{
	public function index(Request $request)
	{	
		
		$dptos = \Auth::user()->centros->pluck('id')->toArray();
		$query = $request->num;
		$empleado = Employe::getEmpleadoSearch($query, $dptos);  
		$title = "Empleado";   

        if ($empleado==null) {
			$noencontrado = " Empleado no encontrado, o no pertenece a su adscripcion.<br>Informacion en Recursos Humanos";
			$empleado = NULL;
			return view('admin.employees.empleado_show')
				->with('error', $noencontrado)
				->with('title', $title);
		}
		else {
			
			$noencontrado = null;
	        $jornadas = Jornada::all()->pluck('jornada', 'id')->toArray();
	        $horarios = Horario::all()->pluck('horario', 'id')->toArray();
	        asort($horarios);
	        asort($jornadas);
			return view('admin.employees.empleado_show')
				->with('employe', $empleado)	
				->with('title', $title)
				->with('horarios', $horarios)
				->with('jornadas', $jornadas);
			
		}
		
	
	}
	
}
