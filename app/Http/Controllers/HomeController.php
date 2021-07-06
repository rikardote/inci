<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;
use App\Qna;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        setlocale(LC_TIME, 'es_MX.UTF-8');
        $qna = Qna::where('active', '=', 1)->orderBy('active', 'desc')->first();
        $hoy = date("Y-m-d"); 
            if ($qna->cierre != null) {
                if ($hoy > $qna->cierre) {
                   // $qna->active = 0;
                    $qna->save();
                }
            }
        if (!\Auth::user()->admin()) {

            return view('home')->with('qna', $qna);
        }
        else{
            return view('dashboard');
        }
        
    }
}
