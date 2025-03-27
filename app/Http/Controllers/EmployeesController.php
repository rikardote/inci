<?php

namespace App\Http\Controllers;

use Response;

use App\Puesto;
use App\Employe;
use App\Horario;
use App\Jornada;
use Maintenance;
use App\Condicion;
use App\Deparment;
use Carbon\Carbon;
use App\Incidencia;
use Laracasts\Flash\Flash;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use App\Http\Requests\EmployessRequest;

class EmployeesController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
       setlocale(LC_ALL,"es_MX.utf8");
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (\Auth::user()->admin() || \Auth::user()->member()) {
            return view('admin.employees.index');
        }
        /*if (\Maintenance::mantenimiento()->state() {
            return redirect()->route('mantenimiento.index');
        }*/
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Employe $employe)
    {

        //$deparments = Deparment::all()->pluck('deparment', 'id')->toArray();
        $deparments = \Auth::user()->centros->pluck('deparment', 'id')->toArray();
        //$deparments = Deparment::whereIn('deparments.id', $dptos)->get();
        $puestos = Puesto::all()->pluck('puesto', 'id')->toArray();
        $jornadas = Jornada::all()->pluck('jornada', 'id')->toArray();
        $horarios = Horario::all()->pluck('horario', 'id')->toArray();
        $condiciones = Condicion::all()->pluck('condicion', 'id')->toArray();
        //asort($deparments);
        asort($puestos);
        asort($horarios);
        asort($jornadas);

        return view('admin.employees.createorupdate')
        ->with('deparments', $deparments)
        ->with('employe', $employe)
        ->with('puestos', $puestos)
        ->with('jornadas', $jornadas)
        ->with('horarios', $horarios)
        ->with('condiciones', $condiciones);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(EmployessRequest $request)
    {
        $employe = new Employe($request->all());
        $employe->fecha_ingreso = fecha_ymd($request->fecha_ingreso);
        $employe->lactancia_inicio = fecha_ymd($request->lactancia_inicio);
        $employe->lactancia_fin = fecha_ymd($request->lactancia_fin);

        $employe->save();

        Flash::success('Empleado registrado con exito!');
        return redirect()->route('employees.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($num_empleado)
    {

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($num_empleado)
    {
        $employe = Employe::where('num_empleado', $num_empleado)->first();
        $deparments = \Auth::user()->centros->pluck('deparment', 'id')->toArray();
        //$deparments = Deparment::all()->pluck('deparment', 'id')->toArray();
        $jornadas = Jornada::all()->pluck('jornada', 'id')->toArray();
        $puestos = Puesto::all()->pluck('puesto', 'id')->toArray();
        $horarios = Horario::all()->pluck('horario', 'id')->toArray();
        $condiciones = Condicion::all()->pluck('condicion', 'id')->toArray();
        asort($deparments);
        asort($puestos);
        asort($horarios);
        asort($jornadas);

        return view('admin.employees.createorupdate')
        ->with('deparments', $deparments)
        ->with('employe', $employe)
        ->with('jornadas', $jornadas)
        ->with('puestos', $puestos)
        ->with('horarios', $horarios)
        ->with('condiciones', $condiciones);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $num_empleado)
    {
        $employe = Employe::where('num_empleado', $num_empleado)->first();
        $employe->fill($request->all());
        $employe->fecha_ingreso = fecha_ymd($request->fecha_ingreso);
        $employe->estancia = $request->has('estancia') ? 1:0;
        $employe->lactancia = $request->has('lactancia') ? 1:0;
        $employe->comisionado = $request->has('comisionado') ? 1:0;

        if ($employe->lactancia) {
            $employe->lactancia_inicio = fecha_ymd($request->lactancia_inicio);
            $employe->lactancia_fin = fecha_ymd($request->lactancia_fin);
        }else {
            $employe->lactancia_inicio = null;
            $employe->lactancia_fin = null;
        }

        $employe->save();
        Flash::success('Empleado editado con exito!');
        return redirect()->route('employees.index');
    }

    public function capt_update(Request $request, $num_empleado)
    {
        $employe = Employe::where('num_empleado', $num_empleado)->first();
        $employe->jornada_id = $request->jornada_id;
        $employe->horario_id = $request->horario_id;
        //$employe->fecha_ingreso = fecha_ymd($request->fecha_ingreso);
        $employe->save();
        Flash::success('Empleado editado con exito!');
        return redirect()->route('employees.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($num_empleado)
    {
        //if (Request::ajax()) {
            $employe = Employe::where('num_empleado', $num_empleado)->first();
            $employe->delete();
            $response = array(
                'success' => 'true'
            );
            return Response::json($response); //redirect()->route('qnas.index');
        //}
    }
    public function autocomplete()
    {
        $term = Str::upper(Input::get('term'));

        $dptos = \Auth::user()->centros->pluck('id')->toArray();

        $data = Employe::whereIn('deparment_id', $dptos)
            ->where('father_lastname', 'LIKE', $term.'%')
            //->orwhere('name', 'LIKE', $term.'%')
            //->take(40)
            ->get();

        foreach ($data as $v) {
            $return_array[] = array('value'=>$v->num_empleado,'label' => $v->father_lastname.' '.$v->mother_lastname.' '.$v->name);
        }

        return Response::json($return_array);
    }
    public function autocomplete_medicos()
    {
        $ids = ['24','25','28','30','56','57','58','59','60','61','62','63','64','65','66','67','68','87','88','101','95','96','97','98'];
        $term = Str::upper(Input::get('term'));

        $dptos = \Auth::user()->centros->pluck('id')->toArray();

        $data = Employe::whereIn('puesto_id', $ids)
            //->orwhere('name', 'LIKE', $term.'%')
            ->where('father_lastname', 'LIKE', $term.'%')
            //->take(20)
            ->get();

        foreach ($data as $v) {
            $return_array[] = array('value'=>$v->id,'label' => $v->father_lastname.' '.$v->mother_lastname.' '.$v->name);
        }

        return Response::json($return_array);
    }
    public function getLicenciasMedicas($num_empleado)
    {
        $empleado = Employe::where('num_empleado', $num_empleado)->first();
        $fechaInicio = getdateActual($empleado->fecha_ingreso);
        $fechaFinal  = getdatePosterior($fechaInicio);

        // Calcular antigüedad en años
        $fechaIngreso = new Carbon($empleado->fecha_ingreso);
        $hoy = Carbon::now();
        $antiguedad = $fechaIngreso->diffInYears($hoy);

        try {
            $dias_lic = Incidencia::getIncapacidadesEmpleado($empleado->num_empleado, $fechaInicio, $fechaFinal);

            // Verificar si se excede el límite según antigüedad
            if (($dias_lic > 15 && $antiguedad < 1) ||
                ($dias_lic > 30 && $antiguedad >= 1 && $antiguedad <= 4) ||
                ($dias_lic > 45 && $antiguedad >= 5 && $antiguedad <= 9) ||
                ($dias_lic > 60 && $antiguedad >= 10)) {

                return response()->json([
                    'success' => true,
                    'licenciasMedicas' => $dias_lic
                ]);
            }

            // Si no excede el límite, retornar cero
            return response()->json([
                'success' => true,
                'licenciasMedicas' => 0
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
