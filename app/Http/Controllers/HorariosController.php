<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Horario;
use App\Http\Requests\HorariosRequest;

use Laracasts\Flash\Flash;

class HorariosController extends Controller
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
        $horarios = Horario::orderBy('horario', 'ASC')->paginate(10);
       
        return view('admin.horarios.index')->with('horarios', $horarios);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Horario $horario)
    {
       
        return view('admin.horarios.create', compact('horario'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $horario = new Horario;
        $horario->horario = $request->horario_entrada . ' A ' . $request->horario_salida;

        $horario->create([
                'horario'        => $horario->horario,
                'created_at'    => date("Y-m-d H:i:s"),
                'updated_at'    => date("Y-m-d H:i:s"),
            ]);
       
        Flash::success('Horario creado con exito!');
        return redirect()->route('horarios.index');
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
        $horario = Horario::find($id);

        return view('admin.horarios.create')->with('horario', $horario);
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
        $horario = Horario::find($id);
        $horario->fill($request->all());
        //$this->fill($request->all());  
        $horario->save();
        Flash::success('Horario editado con exito!');
        return redirect()->route('horarios.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $horario = Horario::find($id);
        $horario->delete();

        Flash::error('Horario borrado con exito!');
        return redirect()->route('horarios.index');
    }
}
