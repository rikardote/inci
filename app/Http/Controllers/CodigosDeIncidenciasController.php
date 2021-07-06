<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Http\Requests\CodigosDeIncidenciasRequest;
use App\Codigo_De_Incidencia;
use Laracasts\Flash\Flash;

class CodigosDeIncidenciasController extends Controller
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
        if (!\Auth::user()->admin()) {

            return redirect()->route('home.index');
        }
        $incidencias = Codigo_De_Incidencia::orderBy('code', 'ASC')->paginate(10);
        
        return view('admin.codigosdeincidencias.index')->with('incidencias', $incidencias);
           
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
       
        return view('admin.codigosdeincidencias.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CodigosDeIncidenciasRequest $request)
    {
        $incidencia = new Codigo_De_Incidencia($request->all());
        $incidencia->save();

        Flash::success('Incidencia creada con exito!');
        return redirect()->route('codigosdeincidencias.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($code)
    {
        $incidencia = Codigo_De_Incidencia::where('code', $code)->first();

        return view('admin.codigosdeincidencias.edit')->with('incidencia', $incidencia);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $code)
    {
        $incidencia = Codigo_De_Incidencia::where('code', $code)->first();
        $incidencia->fill($request->all());

        $incidencia->save();
        Flash::success('Incidencia editada con exito!');
        return redirect()->route('codigosdeincidencias.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($code)
    {
        $incidencia = Codigo_De_Incidencia::where('code', $code)->first();
        $incidencia->delete();

        Flash::error('Incidencia borrada con exito!');
        return redirect()->route('codigosdeincidencias.index');
    }
}