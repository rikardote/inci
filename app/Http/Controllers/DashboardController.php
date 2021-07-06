<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Qna;
use Carbon\Carbon;

class DashboardController extends Controller
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

            return view('home')->with('qna', $qna);
        }
        else {
            
        	return view('dashboard');	
        }
        
    }
}
