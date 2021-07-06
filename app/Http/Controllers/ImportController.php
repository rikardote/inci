<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Checada;
use Laracasts\Flash\Flash;
use App\Http\Requests;
use Excel;


class ImportController extends Controller
{

    public function import(Request $request)
    {
        //ini_set('memory_limit', '512M');
        set_time_limit(0);
      
            $path = $request->file('imported-file')->getRealPath();
            Excel::load($path, function ($reader) {
                foreach ($reader->get() as $checada) {
                    Checada::create([
                        'num_empleado' => $checada->num_empleado,
                        'fecha' => $checada->fecha,
                        'entrada' => $checada->entrada ? $checada->entrada:null,
                        'salida' => $checada->salida ? $checada->salida:null,
                        'qna' => $checada->qna,
                        'ejercicio' => $checada->ejercicio
                    ]);
                }

            });
        /*
            $get_numero_incorrectos = Checada::where('num_empleado','=','255202')->first();
            $get_numero_incorrectos->num_empleado = "255902";
            $get_numero_incorrectos->save();

            $get_numero_incorrectos = Checada::where('num_empleado','=','800835')->first();
            $get_numero_incorrectos->num_empleado = "389037";
            $get_numero_incorrectos->save();

            $get_numero_incorrectos = Checada::where('num_empleado','=','800814')->first();
            $get_numero_incorrectos->num_empleado = "366385";
            $get_numero_incorrectos->save();

            $get_numero_incorrectos = Checada::where('num_empleado','=','800826')->first();
            $get_numero_incorrectos->num_empleado = "372309";
            $get_numero_incorrectos->save();
        */
            Flash::success('!! CHECADAS IMPORTADAS EXITOSAMENTE !!');

            return redirect()->route('biometrico.get_checadas');



    }

}

