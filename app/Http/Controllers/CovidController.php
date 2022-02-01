<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Deparment;
use App\Qna;
use App\Incidencia;
use \mPDF;
use Carbon\Carbon;

class CovidController extends Controller
{
    public function index()
    {

        $dptos = \Auth::user()->centros->pluck('id')->toArray();
		$dptos = Deparment::whereIn('deparments.id', $dptos)->get();
		$qnas = Qna::orderby('id', 'desc')->limit(45)->get()->pluck('Qnaa', 'id')->toArray();
        krsort($qnas);
        $title = "Reporte Covid-19";
        return view('admin.covid.index')
             ->with('dptos', $dptos)
            ->with('qnas', $qnas)
            ->with('title', $title);
            
    }
    public function show(Request $request, $dpto){
        $dpto = Deparment::where('code', '=', $dpto)->first();
        
        $incidencias = Incidencia::getIncidenciasCovid($request->qna_id, $dpto->id);
       
        
        //$incidencias = Incidencia::getIncidenciasCovid2();
        
        
        //dd($incidencias);
        $qna = Qna::find($request->qna_id);
        $title = "Reporte General Qna: " . $qna->qna . "/" . $qna->year . " - " . $qna->description;

        return view('admin.covid.show')
            ->with('incidencias', $incidencias)
            ->with('title', $title)
            ->with('qna',$qna)
            ->with('dpto',$dpto);

    }
    public function reporte_pdf($qna_id, $dpto){
        $dpto = Deparment::where('code', '=', $dpto)->first();

        $incidencias = Incidencia::getIncidenciasCovid($qna_id, $dpto->id);
        $qna = Qna::find($qna_id);
        
        if ($incidencias == null) {
            Flash::warning('No hay datos para esta fecha: '.fecha_dmy($fecha));
            return redirect()->route('reports.diario');
          }
          else {
            $mpdf = new mPDF('', 'Letter', 0, '', 12.7, 12.7, 14, 12.7, 8, 8);
              $header = \View('admin.covid.header', compact('dpto', 'qna'))->render();
              $mpdf->SetFooter('Generado el: {DATE j-m-Y} |Hoja {PAGENO} de {nb}');
              $html =  \View('admin.covid.pdf', compact('incidencias'))->render();
              $pdfFilePath = 'REPORTE_COVID_DEL_'.Carbon::now().'.pdf';
              $mpdf->setAutoTopMargin = 'stretch';
              $mpdf->setAutoBottomMargin = 'stretch';
              $mpdf->setHTMLHeader($header);
              $mpdf->SetDisplayMode('fullpage');
              $mpdf->WriteHTML($html);
         
              $mpdf->Output($pdfFilePath, "D");
          }
    }
    public function todos(){
        
        $incidencias = Incidencia::getIncidenciasCovid2();
        
        //dd($incidencias);
        return view('admin.covid.todos')
            ->with('incidencias', $incidencias);

    }
}
