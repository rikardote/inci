<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Deparment;
use App\Http\Requests\DeparmentRequest;

use Laracasts\Flash\Flash;

class DeparmentsController extends Controller
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
        $deparments = Deparment::orderBy('code', 'ASC')->paginate(10);
       
        return view('admin.deparments.index')->with('deparments', $deparments);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Deparment $deparment)
    {
       
        return view('admin.deparments.create', compact('deparment'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(DeparmentRequest $request)
    {
        $deparment = new Deparment($request->all());
        $deparment->save();
        //$this->deparment->create($request->all());

        Flash::success('Departamento creado con exito!');
        return redirect()->route('deparments.index');
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
        $deparment = Deparment::where('code', $code)->first();

        return view('admin.deparments.create')->with('deparment', $deparment);
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
        $deparment = Deparment::where('code', $code)->first();
        $deparment->fill($request->all());
        //$this->fill($request->all());  
        $deparment->save();
        Flash::success('Departamento editado con exito!');
        return redirect()->route('deparments.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($code)
    {
        $deparment = Deparment::where('code', $code)->first();
        $deparment->delete();

        Flash::error('Departamento borrado con exito!');
        return redirect()->route('deparments.index');
    }
}
