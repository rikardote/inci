<?php
 
namespace App\Http\Controllers;

use Request;
use App\Http\Controllers\Controller;
use Laracasts\Flash\Flash;
use Response;
use App\Qna;

class QnasController extends Controller
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
        //$qnas = Qna::orderBy('year', 'desc')->orderBy('qna', 'desc')->limit(10)->get();
        
        /*Abrir todas las Qnas */
        
        $qnas = Qna::orderBy('year', 'desc')->orderBy('qna', 'desc')->get();
        return view('admin.qnas.index')->with('qnas', $qnas);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $qna = "";

        return view('admin.qnas.create')->with('qna', $qna);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $qna = new Qna(Request::all());

        $qna->save();

        Flash::success('Qna creada con exito!');
        return redirect()->route('qnas.index');
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
        $qna = Qna::find($id);

        return view('admin.qnas.edit')->with('qna', $qna);
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
        $qna = Qna::find($id);
        $qna->fill($request->all());

        $qna->save();
        Flash::success('Qna editada con exito!');
        return redirect()->route('qnas.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $qna = Qna::find($id);
        $qna->delete();

        Flash::error('Qna borrada con exito!');
        return redirect()->route('qnas.index');
    }
    public function condicion($id)
    {
        if (Request::ajax()) {

            $qna = Qna::find($id);
            $qna->active = ($qna->active) ? FALSE : TRUE;
              
            $qna->save();

            //$qnas = Qna::orderBy('year', 'desc')->orderBy('qna', 'desc')->limit(10)->get();
            /*Abrir todas las Qnas */
        
            $qnas = Qna::orderBy('year', 'desc')->orderBy('qna', 'desc')->get();
            //return Response::json($response); //redirect()->route('qnas.index');
            return response()->json(
                        $qnas,200
                    );
            
        }
        
    }
}
