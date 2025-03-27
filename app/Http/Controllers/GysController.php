<?php

namespace App\Http\Controllers;

use App\Suplencia;
use Carbon\Carbon;
use App\Http\Requests;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Exports\SuplenciasExport;
use Symfony\Component\HttpFoundation\StreamedResponse;



class GysController extends Controller
{
    public function index()
    {
      $suplencias = Suplencia::all();
      $montosPorQuincenaCentro = Suplencia::montosPorQuincenaCentro();

      return view('admin.gys.index', compact('suplencias', 'montosPorQuincenaCentro'));

    }

    public function exportarSuplentesPorQuincena($year, $quincena)
    {
        $filename = 'suplencias_quincena_' . $quincena . '_' . $year . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0'
        ];

        return new Response(
            Suplencia::exportarSuplentesPorQuincena($year, $quincena),
            200,
            $headers
        );
    }
  public function exportarTodasSuplencias()
  {
      $suplencias = Suplencia::all();

      $filename = 'suplencias_totales.csv';

      $headers = [
          'Content-Type' => 'text/csv',
          'Content-Disposition' => 'attachment; filename="' . $filename . '"',
      ];

      $callback = function() use ($suplencias) {
          $output = fopen('php://output', 'w');

          // Add CSV headers
          fputcsv($output, [
              'Quincena', 'Año', 'RFC', 'C.T.', 'Puesto', 'Suplente',
              'Fecha inicial', 'Fecha final', 'Horas/Dias' ,'Incidencia', 'No.de Empleado', 'Trabajador', 'Monto'
          ]);

          // Add data rows
          foreach ($suplencias as $suplencia) {
              fputcsv($output, [
                  $suplencia->quincena,
                  $suplencia->year,
                  $suplencia->rfc,
                  $suplencia->obtenerDescripcionCentro(),
                  $suplencia->obtenerDescripcionPuesto($suplencia->puesto),
                  $suplencia->nombre_suplente,
                  $suplencia->fecha_inicial,
                  $suplencia->fecha_final,
                  $suplencia->hodias,
                  $suplencia->obtenerDescripcionIncidencia(),
                  $suplencia->num_empleado,
                  $suplencia->obtenerEmpleado($suplencia->num_empleado),
                  $suplencia->monto,
              ]);
          }

          fclose($output);
      };

      return new StreamedResponse($callback, 200, $headers);
  }
  public function exportarReporteMensual()
{
    $filename = 'reporte_mensual_' . date('Y') . '.csv';

    $headers = [
        'Content-Type' => 'text/csv',
        'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        'Pragma' => 'no-cache',
        'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
        'Expires' => '0'
    ];

    $csv = Suplencia::exportarReporteMensual();

    return new Response($csv, 200, $headers);
}
    public function obtenerSuplenciasPorQuincena(Request $request)
    {
        $year = $request->input('year');
        $quincena = $request->input('quincena');

        // Obten solo las suplencias para este período específico
        $suplenciasPeriodo = Suplencia::where('year', $year)
            ->where('quincena', $quincena)
            ->get();

        // Renderiza solo la parte de la tabla con las suplencias
        return view('admin.gys.partials._suplencias_tabla', [
            'suplenciasPeriodo' => $suplenciasPeriodo,
            'year' => $year,
            'quincena' => $quincena
        ]);
    }



}
