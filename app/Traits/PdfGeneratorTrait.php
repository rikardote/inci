<?php

namespace App\Traits;

use Mpdf\Mpdf;
use Exception;
use Illuminate\Support\Facades\Log;
use Laracasts\Flash\Flash;

trait PdfGeneratorTrait
{
    /**
     * Configuración estándar para mPDF
     */
    protected function getPdfConfig($format = 'Letter', $marginTop = 25)
    {
        return [
            'mode' => 'utf-8',
            'format' => $format,
            'default_font_size' => '',
            'default_font' => '',
            'margin_left' => 12.7,
            'margin_right' => 12.7,
            'margin_top' => $marginTop,
            'margin_bottom' => 15,
            'margin_header' => 5,
            'margin_footer' => 10,
            'tempDir' => storage_path('app/public/tmp'),
        ];
    }

    /**
     * Optimiza PHP para generación de PDF
     */
    protected function optimizeForPdf()
    {
        ini_set('pcre.backtrack_limit', '10000000');
        ini_set('memory_limit', '1024M');
        ini_set('max_execution_time', 300);
    }

    /**
     * Crea una instancia de mPDF con la configuración estándar
     */
    protected function createPdfInstance($config = null, $landscape = false)
    {
        $this->optimizeForPdf();

        if (!$config) {
            $config = $this->getPdfConfig($landscape ? 'Letter-L' : 'Letter');
        }

        return new Mpdf($config);
    }

    /**
     * Establece el encabezado del PDF usando una vista
     */
    protected function setPdfHeader($mpdf, $view, $data = [])
    {
        $header = \View($view, $data)->render();
        $mpdf->setAutoTopMargin = 'stretch';
        $mpdf->SetHTMLHeader($header);
        return $mpdf;
    }

    /**
     * Establece el pie de página del PDF
     */
    protected function setPdfFooter($mpdf, $footer = null)
    {
        if (is_null($footer)) {
            // Pie de página por defecto
            $footer = 'Generado el: {DATE j-m-Y} |Hoja {PAGENO} de {nb}';
        }

        if (strpos($footer, '<table') !== false) {
            // Si es HTML, usar SetHTMLFooter
            $mpdf->SetHTMLFooter($footer);
        } else {
            // Si es texto, usar SetFooter
            $mpdf->SetFooter($footer);
        }

        return $mpdf;
    }

    /**
     * Añade estilos CSS básicos al PDF
     */
    protected function addBasicStyles($mpdf, $additionalStyles = '')
    {
        $baseStyles = '
        <style>
            table {
                width: 100%;
                border-collapse: collapse;
            }
            table, th, td {
                border: 1px solid #ddd;
            }
            thead {
                background-color: #343a40;
                color: white;
            }
            th, td {
                padding: 5px;
                font-size: 11px;
            }
            .text-center {
                text-align: center;
            }
            /* Eliminar fondos alternados y usar fondo blanco para todas las filas */
            tr {
                background-color: #ffffff;
            }
            /* Quitar bordes en el footer */
            #mpdf-footer table, #mpdf-footer td {
                border: none !important;
            }
            /* Estilos específicos para tablas en el footer */
            .footer-table {
                border: none !important;
            }
            .footer-table tr, .footer-table td {
                border: none !important;
            }
            ' . $additionalStyles . '
        </style>';

        $mpdf->WriteHTML($baseStyles);
        return $mpdf;
    }

    /**
     * Renderiza el contenido del PDF usando una vista
     */
    protected function renderPdfContent($mpdf, $view, $data = [])
    {
        $html = \View($view, $data)->render();
        $mpdf->WriteHTML($html);
        return $mpdf;
    }

    /**
     * Genera un PDF completo usando los métodos anteriores
     *
     * @param string $view Vista para el contenido del PDF
     * @param array $data Datos para pasar a la vista
     * @param string $filename Nombre del archivo PDF
     * @param string $headerView Vista para el encabezado (opcional)
     * @param array $headerData Datos para la vista del encabezado (opcional)
     * @param string $footer Texto o HTML para el pie de página (opcional)
     * @param string $outputMode Modo de salida: I (inline), D (download), etc.
     * @param bool $landscape Orientación horizontal
     * @return mixed
     */
    protected function generatePdf(
        $view,
        $data = [],
        $filename = 'report.pdf',
        $headerView = null,
        $headerData = [],
        $footer = null,
        $outputMode = 'I',
        $landscape = false,
        $additionalStyles = ''
    ) {
        try {
            // Crear instancia de PDF
            $mpdf = $this->createPdfInstance(null, $landscape);
            $mpdf->SetDisplayMode('fullpage');

            // Establecer encabezado si se proporciona
            if ($headerView) {
                $this->setPdfHeader($mpdf, $headerView, $headerData);
            }

            // Establecer pie de página
            $this->setPdfFooter($mpdf, $footer);

            // Añadir estilos
            $this->addBasicStyles($mpdf, $additionalStyles);

            // Renderizar contenido
            $this->renderPdfContent($mpdf, $view, $data);

            // Generar y devolver el PDF
            return $mpdf->Output($filename, $outputMode);

        } catch (Exception $e) {
            Log::error('Error generando PDF: ' . $e->getMessage() . ' - ' . $e->getTraceAsString());

            // Si estamos en una solicitud web, mostrar mensaje de error
            if (request()->ajax()) {
                return response()->json(['error' => 'Error al generar el PDF: ' . $e->getMessage()], 500);
            } else {
                Flash::error('Error al generar el PDF: ' . $e->getMessage());
                return back();
            }
        }
    }

    /**
     * Genera un PDF directamente desde contenido HTML (sin usar una vista)
     */
    protected function generateCustomPdf(
        $htmlContent,
        $filename = 'report.pdf',
        $headerView = null,
        $headerData = [],
        $footer = null,
        $outputMode = 'I',
        $landscape = false
    ) {
        try {
            // Optimizar PHP para PDF
            $this->optimizeForPdf();

            // Crear instancia de PDF
            $mpdf = $this->createPdfInstance(null, $landscape);
            $mpdf->SetDisplayMode('fullpage');

            // Establecer encabezado si se proporciona
            if ($headerView) {
                $this->setPdfHeader($mpdf, $headerView, $headerData);
            }

            // Establecer pie de página
            $this->setPdfFooter($mpdf, $footer);

            // Escribir el HTML directamente
            $mpdf->WriteHTML($htmlContent);

            // Generar y devolver el PDF
            return $mpdf->Output($filename, $outputMode);

        } catch (\Exception $e) {
            \Log::error('Error generando PDF: ' . $e->getMessage() . ' - ' . $e->getTraceAsString());

            // Si estamos en una solicitud web, mostrar mensaje de error detallado
            if (request()->ajax()) {
                return response()->json(['error' => 'Error al generar el PDF: ' . $e->getMessage()], 500);
            } else {
                return response('<h1>Error al generar el PDF</h1>
                                <p>Mensaje: ' . $e->getMessage() . '</p>
                                <p>Línea: ' . $e->getLine() . ' en archivo ' . $e->getFile() . '</p>
                                <pre>' . $e->getTraceAsString() . '</pre>', 500);
            }
        }
    }
}
