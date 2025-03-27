<?php

namespace App\Http\Controllers;

use App\Suplencia;
use Carbon\Carbon;
use App\Http\Requests;


use Laracasts\Flash\Flash;
use Illuminate\Http\Request;

class ImportGysController extends Controller
{
  private function formatearDatos($value)
  {
    $fechaActual = Carbon::now();

    return [
        'rfc' => trim(substr($value[0], 0, 13)),
        'beneficiario' => trim(substr($value[0], 13, 7)),
        'puesto' => trim(substr($value[0], 28, 7)),
        'hodias' => trim(substr($value[0], 42, 2)),
        'monto' => floatval(trim(substr($value[0], 46, 8))),
        'centro' => trim(substr($value[0], 69, 10)),
        'num_suplente' => trim(substr($value[0], 79, 6)),
        'fecha_inicial' => substr($value[0], 85, 8),
        'fecha_final' => substr($value[0], 93, 8),
        'cvemov' => trim(substr($value[0], 101, 2)),
        'num_empleado' => trim(substr($value[0], 103, 6)),
        'fecha_captura' => substr($value[0], 109, 8),
        'incidencia' => trim(substr($value[0], 117, 2)),
        'servicio' => trim(substr($value[0], 129, 5)),
        'year' => trim(substr($value[0], 144, 4)),
        'quincena' => trim(substr($value[0], 148, 2)),
        'nombre_suplente' => trim(substr($value[0], 155, 30)),
        'created_at' => $fechaActual,
        'updated_at' => $fechaActual,
    ];
  }

  private function formatearFecha($fecha)
  {
      if (strlen($fecha) !== 8) {
          return null;
      }

      try {
          return Carbon::createFromFormat('dmY', $fecha)->format('Y-m-d');
      } catch (\Exception $e) {
          return null;
      }
  }
  public function import(Request $request)
  {
      try {
          $chunkSize = 25;
          $dataToInsert = [];

          $path = $request->file('csv_file')->getRealPath();
          $file = fopen($path, 'r');

          if (!$file) {
              throw new \Exception('No se pudo abrir el archivo');
          }

          while ($value = fgetcsv($file)) {
              if (empty($value[0])) {
                  continue;
              }

              $dataToInsert[] = $this->formatearDatos($value);

              if (count($dataToInsert) >= $chunkSize) {
                  Suplencia::insert($dataToInsert);
                  $dataToInsert = [];
              }
          }

          if (!empty($dataToInsert)) {
              Suplencia::insert($dataToInsert);
          }

          fclose($file);

          Flash::success('Suplencias procesadas exitosamente!');
          return back();

      } catch (\Exception $e) {
          if (isset($file)) {
              fclose($file);
          }
          return back()
              ->withErrors(['error' => 'Error al importar el archivo: ' . $e->getMessage()])
              ->withInput();
      }
  }
}
