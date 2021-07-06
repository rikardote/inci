<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use \mPDF;
use Carbon\Carbon;

class PdfController extends Controller
{
    public function invoice() 
    {
     $mpdf=new mPDF('c','A4','','' , 0 , 0 , 0 , 0 , 0 , 0);
    $mpdf->SetDisplayMode('fullpage');
    $mpdf->list_indent_first_level = 0;  // 1 or 0 - whether to indent the first level of a list
    $html = "<html><body>This is a pdf file using mdf</body></html>";
    $mpdf->WriteHTML($html);
    $orderPdfName = "order-".$singleOrder[0]['display_name'];
    $mpdf->Output("test.pdf",'I');
    header('Content-type: application/pdf');
    header("Content-Disposition: attachment; filename=test.pdf");
    }
}
