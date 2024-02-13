<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Puesto;
use App\Http\Requests\PuestoRequest;

use Laracasts\Flash\Flash;

class PuestosController extends Controller
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
        $puestos = Puesto::orderBy('puesto', 'ASC')->paginate(10);

        return view('admin.puestos.index')->with('puestos', $puestos);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Puesto $puesto)
    {

        return view('admin.puestos.create', compact('puesto'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PuestoRequest $request)
    {
        $puesto = new Puesto($request->all());
        $puesto->save();
        //$this->deparment->create($request->all());

        Flash::success('Puesto creado con exito!');
        return redirect()->route('puestos.index');
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
    public function edit($id)
    {
        $puesto = Puesto::find($id);

        return view('admin.puestos.create')->with('puesto', $puesto);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $puesto = Puesto::find($id);
        $puesto->fill($request->all());
        //$this->fill($request->all());
        $puesto->save();
        Flash::success('Puesto editado con exito!');
        return redirect()->route('puestos.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $puesto = Puesto::find($id);
        $puesto->delete();

        Flash::error('Puesto borrado con exito!');
        return redirect()->route('puestos.index');
    }
}
