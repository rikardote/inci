<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Configuration;

class MantenimientoController extends Controller
{
    public function index(){
        return view('admin.mantenimiento.index');
    }
    public function show(){
        $mantenimiento = Configuration::where('name', "mantenimiento")->first();
        return view('admin.mantenimiento.show')->with('mantenimiento', $mantenimiento);
    }
    
    public function state(){
        $mantenimiento = Configuration::where('name', "mantenimiento")->first();
        $mantenimiento->state = ($mantenimiento->state) ? FALSE : TRUE;
              
        $mantenimiento->save();
        return redirect()->route('dashboard.index');

     
    }
}
