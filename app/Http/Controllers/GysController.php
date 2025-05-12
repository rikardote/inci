<?php

namespace App\Http\Controllers;

use App\Suplencia;
use Carbon\Carbon;
use App\Http\Requests;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
//use App\Exports\SuplenciasExport;
use Symfony\Component\HttpFoundation\StreamedResponse;



class GysController extends Controller
{
    public function index()
    {
      $suplencias = Suplencia::all();
      $montosPorQuincenaCentro = Suplencia::montosPorQuincenaCentro();

      return view('admin.gys.index', compact('suplencias', 'montosPorQuincenaCentro'));

    }

    private function generateCsvResponse($content, $filename) {
        return new Response($content, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0'
        ]);
    }

    public function exportarSuplentesPorQuincena($year, $quincena)
    {
        if (!is_numeric($year) || !is_numeric($quincena)) {
            abort(400, 'Parámetros inválidos');
        }
        $filename = "suplencias_quincena_{$quincena}_{$year}.csv";
        return $this->generateCsvResponse(
            Suplencia::exportarSuplentesPorQuincena($year, $quincena),
            $filename
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
    public function eliminarSuplenciasPorQuincena(Request $request)
    {
        $year = $request->input('year');
        $quincena = $request->input('quincena');

        Suplencia::where('year', $year)
            ->where('quincena', $quincena)
            ->delete();

        $montosActualizados = Suplencia::montosPorQuincenaCentro();

        return response()->json([
            'success' => true,
            'montosPorQuincenaCentro' => $montosActualizados
        ]);
    }
    public function eliminarSuplenciaIndividual($id)
    {
        try {
        $suplencia = Suplencia::findOrFail($id);
        $suplencia->delete();

        return response()->json([
            'success' => true,
            'message' => 'Suplencia eliminada correctamente.'
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Error al eliminar la suplencia: ' . $e->getMessage()
        ], 500);
    }
    }


}
