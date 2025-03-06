<?php
namespace App\Exports;

use App\MatrizGys;
use Maatwebsite\Excel\Classes\LaravelExcelWorksheet;
use PHPExcel_Worksheet;

class SuplenciasExport
{
    protected $suplencias;

    public function __construct($suplencias)
    {
        $this->suplencias = $suplencias;
    }

    public function getExcel()
    {
        \Excel::create('Suplencias', function($excel) {
            $excel->sheet('Suplencias', function($sheet) {
                $sheet->fromArray($this->getData());
                $sheet->row(1, array(
                    'Quincena', 'Año', 'RFC', 'C.T.', 'Puesto', 'Suplente',
                    'Fecha inicial', 'Fecha final', 'Incidencia', 'No.de Empleado', 'Trabajador', 'Monto'
                ));
            });
        })->export('xlsx');
    }

    public function getData()
    {
        $data = [];
        foreach ($this->suplencias as $suplencia) {
            $data[] = array(
                $suplencia->quincena,
                $suplencia->year,
                $suplencia->rfc,
                $suplencia->obtenerDescripcionCentro(),
                $suplencia->obtenerDescripcionPuesto($suplencia->puesto),
                $suplencia->nombre_suplente,
                $suplencia->fecha_inicial,
                $suplencia->fecha_final,
                $suplencia->obtenerDescripcionIncidencia(),
                $suplencia->num_empleado,
                $suplencia->obtenerEmpleado($suplencia->num_empleado),
                $suplencia->monto,
            );
        }
        return $data;
    }
}
