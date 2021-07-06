<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Qna;
use App\Fechadecierre;
use Laracasts\Flash\Flash;

class FechadecierreController extends Controller
{
    public function create()
    {
    	$qnas = Qna::where('active', '=', 1)->get();
    	return view('admin.fechadecierre.create')->with('qnas', $qnas);
    }
    public function update(Request $request)
    {
    	$qna = Qna::find($request->qna_id);
    	$qna->cierre = fecha_ymd($request->fecha_inicio);
        $qna->save();
        
        Flash::success('Fecha de cierre creado con exito!');
        return redirect()->route('dashboard.index');
    }
}
