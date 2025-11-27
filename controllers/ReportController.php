<?php
// app/controllers/ReportController.php

// Importación de las clases de Dompdf
use Dompdf\Dompdf;
use Dompdf\Options;

include_once "app/models/ReportModel.php";

class ReportController extends BaseController {

    private $model;
    private $dompdf;
    // Arrays para la traducción de fechas
    private $meses = [
        1 => 'Enero', 2 => 'Febrero', 3 => 'Marzo', 4 => 'Abril', 5 => 'Mayo', 6 => 'Junio',
        7 => 'Julio', 8 => 'Agosto', 9 => 'Septiembre', 10 => 'Octubre', 11 => 'Noviembre', 12 => 'Diciembre'
    ];
    private $dias = [1 => 'Lunes', 2 => 'Martes', 3 => 'Miércoles', 4 => 'Jueves', 5 => 'Viernes', 6 => 'Sábado', 7 => 'Domingo'];

    /**
     * Constructor del controlador.
     * Inicializa el modelo y la librería Dompdf.
     *
     * @param object $conexion Objeto de conexión a la base de datos.
     */
    public function __construct($conexion) {
        $this->model = new ReportModel($conexion);
        
        $options = new Options();
        $options->set('defaultFont', 'Arial');
        $options->set('isRemoteEnabled', true); 
        
        $this->dompdf = new Dompdf($options);
    }

    
    // FUNCIÓN PARA AYUDA QUE PERMITE FORMATEAR LAS FECHAS PARA QUE ESTEN EN ESPAÑOL
    /**
     * Formatea una fecha a español.
     *
     * @param DateTime $dateObject Objeto DateTime.
     * @param string $format Formato deseado ('F', 'l', 'D d/m/Y').
     * @return string Fecha formateada.
     */
    private function formatSpanishDate($dateObject, $format) {
        if ($format == 'F') {
            return $this->meses[(int)$dateObject->format('n')];
        }
        if ($format == 'l') { 
            $diaNum = (int)$dateObject->format('N');
            return $this->dias[$diaNum];
        }
        if ($format == 'D d/m/Y') {
            $diaNum = (int)$dateObject->format('N');
            $diaStr = substr($this->dias[$diaNum], 0, 3); // 'Mié'
            return $diaStr . ' ' . $dateObject->format('d/m/Y');
        }
        return $dateObject->format('d/m/Y');
    }

    
    // FUNCIÓN DE AYUDA PARA CARGAR VISTA DE PDF EN UNA VARIABLE
    /**
     * Renderiza una vista PHP y devuelve el HTML como string.
     *
     * @param string $viewName Nombre del archivo de vista.
     * @param array $data Datos para pasar a la vista.
     * @return string HTML renderizado.
     */
    private function renderPdfViewToString($viewName, $data = []) {
        extract($data);
        
        ob_start();
        
        // Se construye la ruta usando DIRECTORY_SEPARATOR
        $path = PROJECT_ROOT . DIRECTORY_SEPARATOR . 
                'app' . DIRECTORY_SEPARATOR . 
                'views' . DIRECTORY_SEPARATOR . 
                'reports' . DIRECTORY_SEPARATOR . 
                $viewName;

        // Se incluye la ruta absoluta y limpia
        include $path;
        
        return ob_get_clean();
    }

    // --- Funcion para obtener logo
    /**
     * Obtiene el logo en base64 para incrustar en el PDF.
     *
     * @return string Cadena base64 de la imagen o vacío.
     */
    private function getLogoSrc() {
        $path = PROJECT_ROOT . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . 'media' . DIRECTORY_SEPARATOR . 'MolinoLogo.png';
        if (file_exists($path)) {
            $imageData = file_get_contents($path);
            $base64Data = base64_encode($imageData);
            return 'data:image/png;base64,' . $base64Data;
        }
        return ''; // Retorna vacío si no encuentra el logo
    }

    
    // --- HELPER PARA GENERAR GRÁFICAS ---
    /**
     * Genera la URL de una gráfica usando QuickChart.io.
     *
     * @param array $chartConfig Configuración de la gráfica (Chart.js).
     * @param int $width Ancho de la imagen.
     * @param int $height Alto de la imagen.
     * @return string URL de la imagen generada.
     */
    private function generateChartUrl($chartConfig, $width = 500, $height = 300) {
        $configJson = json_encode($chartConfig);
        $encodedConfig = urlencode($configJson); 
        return "https://quickchart.io/chart?c={$encodedConfig}&w={$width}&h={$height}";
    }

    //FUNCIÓN PARA GENERAR LAS GRÁFICAS
    /**
     * Genera las URLs de las gráficas para el reporte.
     *
     * @param array $schedules Datos de horarios.
     * @param array $topProducts Datos de productos más programados.
     * @param array $clientRegistrations Datos de registro de clientes.
     * @return array URLs de las gráficas (pie, line).
     */
    private function generateCharts($schedules, $topProducts, $clientRegistrations) {
        $chartUrls = ['pie' => '', 'line' => ''];
        $colors = ['#006A4E', '#FDCB0A', '#E57373', '#4CAF50', '#888888'];

        // Gráfico de Dona (Distribución de Productos)
        if (!empty($topProducts)) {
            $pieLabels = array_column($topProducts, 'nombre');
            $pieData = array_map(function($p) { return (float)str_replace('%', '', $p['porcentaje']); }, $topProducts);
            if (count($pieData) > 0) {
                $pieConfig = [
                    'type' => 'doughnut',
                    'data' => [
                        'labels' => $pieLabels,
                        'datasets' => [['data' => $pieData, 'backgroundColor' => array_slice($colors, 0, count($pieLabels))]]
                    ],
                    'options' => ['plugins' => ['datalabels' => ['formatter' => '(v) => v + "%"'], 'title' => ['display' => true, 'text' => 'Distribución de Productos']]]
                ];
                $chartUrls['pie'] = $this->generateChartUrl($pieConfig, 480, 320);
            }
        }

        // Gráfico de Línea (Crecimiento de Clientes)
        if (!empty($clientRegistrations)) {
            $lineLabels = [];
            $lineData = [];
            $today = new DateTime();
            $clientDataMap = [];
            foreach ($clientRegistrations as $reg) { $lineKey = $reg['year'] . '-' . $reg['month']; $clientDataMap[$lineKey] = $reg['total']; }
            for ($i = 5; $i >= 0; $i--) {
                $date = (clone $today)->modify("-$i months");
                $monthKey = $date->format('Y-n');
                $lineLabels[] = $this->meses[(int)$date->format('n')];
                $lineData[] = $clientDataMap[$monthKey] ?? 0;
            }
            if (count($lineData) > 0) {
                $lineConfig = [
                    'type' => 'line',
                    'data' => ['labels' => $lineLabels, 'datasets' => [['label' => 'Nuevos Clientes', 'data' => $lineData, 'borderColor' => '#FDCB0A', 'fill' => false]]],
                    'options' => ['scales' => ['y' => ['beginAtZero' => true, 'title' => ['display' => true, 'text' => 'Clientes']]], 'plugins' => ['title' => ['display' => true, 'text' => 'Crecimiento de Clientes (6 Meses)']]]
                ];
                $chartUrls['line'] = $this->generateChartUrl($lineConfig, 550, 350);
            }
        }
        return $chartUrls;
    }
    
    
    // FUNCIÓN PARA GENERAR Y DESCARGAR EL REPORTE MENSUAL COMO PDF
    /**
     * Genera y descarga el reporte mensual en PDF.
     *
     * @return void Descarga el archivo PDF.
     */
    public function downloadMonthlyReport() {
        $this->checkAuth(['Administrador']);

        $year = $_GET['year'] ?? date('Y');
        $month = $_GET['month'] ?? date('m');
        
        // OBTENIENE Y PREPARA DATOS
        $monthName = $this->formatSpanishDate(new DateTime("$year-$month-01"), 'F');
        $schedules = $this->model->getMonthlyScheduleDetails($year, $month);
        $topProducts = $this->model->getMostProgrammedProducts($year, $month);
        $allClients = $this->model->getAllClients();
        $logoSrc = $this->getLogoSrc();
        $reportDate = date('d/m/Y H:i A'); 
        $clientRegistrations = $this->model->getClientRegistrationsByMonth();

        $totalHoras = 0;
        $scheduleDetails = [];
        
        $totalHorariosSum = array_sum(array_column($topProducts, 'total_horarios'));

        foreach ($schedules as $schedule) {
            $inicio = new DateTime($schedule['hora_inicio']);
            $fin = new DateTime($schedule['hora_fin']);
            $diff = $fin->diff($inicio);
            $horas = $diff->h + ($diff->i / 60);
            $totalHoras += $horas;
            
            $dateObj = new DateTime($schedule['fecha']);
            
            $scheduleDetails[] = [
                'fecha' => $this->formatSpanishDate($dateObj, 'D d/m/Y'),
                'producto_nombre' => $schedule['producto_nombre'],
                'horario' => date('g:i A', strtotime($schedule['hora_inicio'])) . " - " . date('g:i A', strtotime($schedule['hora_fin'])),
                'horas' => number_format($horas, 2)
            ];
        }

        foreach ($topProducts as $key => $product) {
            $percentage = ($totalHorariosSum > 0) ? ($product['total_horarios'] / $totalHorariosSum) * 100 : 0;
            $topProducts[$key]['porcentaje'] = number_format($percentage, 2) . '%';
        }
        
        $chartUrls = $this->generateCharts($schedules, $topProducts, $clientRegistrations);

        $dataForView = [
            'reportTitle' => 'Reporte mensual de producción',
            'subTitle' => "mes seleccionado: $monthName $year",
            'scheduleDetails' => $scheduleDetails,
            'dateColumnTitle' => 'Fecha', 
            'totalHoras' => number_format($totalHoras, 2),
            'topProducts' => $topProducts,
            'allClients' => $allClients,
            'logoSrc' => $logoSrc,
            'reportDate' => $reportDate,
            'chartUrls' => $chartUrls
        ];

        // RENDERIZA LA VISTA
        $html = $this->renderPdfViewToString('pdf_report.php', $dataForView);

        // GENERA EL PDF
        $this->dompdf->loadHtml($html);
        $this->dompdf->setPaper('A4', 'portrait');
        $this->dompdf->render();

        // DESCARGA
        $this->dompdf->stream("Reporte_Mensual_$year-$month.pdf", ["Attachment" => true]);
    }
    
    
    // FUNCIÓN PARA GENERAR Y DESCARGAR PDF DE REPORTE SEMANAL
    /**
     * Genera y descarga el reporte semanal en PDF.
     *
     * @return void Descarga el archivo PDF.
     */
    public function downloadWeeklyReport() {
        $this->checkAuth(['Administrador']);

        $startDate = $_GET['start'] ?? '';
        $endDate = $_GET['end'] ?? '';
        
        if (empty($startDate) || empty($endDate)) {
            die("Error: Fechas de semana no proporcionadas.");
        }

        // OBTIENE Y PREPARA LOS DATOS
        $schedules = $this->model->getWeeklyScheduleDetails($startDate, $endDate);
        $allClients = $this->model->getAllClients();
        $logoSrc = $this->getLogoSrc();
        $reportDate = date('d/m/Y H:i A'); 
        $clientRegistrations = $this->model->getClientRegistrationsByMonth();
        
        $totalHoras = 0;
        $scheduleDetails = [];
        foreach ($schedules as $schedule) {
            $inicio = new DateTime($schedule['hora_inicio']);
            $fin = new DateTime($schedule['hora_fin']);
            $diff = $fin->diff($inicio);
            $horas = $diff->h + ($diff->i / 60);
            $totalHoras += $horas;
            
            $dateObj = new DateTime($schedule['fecha']);
            
            $scheduleDetails[] = [
                'fecha' => $this->formatSpanishDate($dateObj, 'l'), 
                'producto_nombre' => $schedule['producto_nombre'],
                'horario' => date('g:i A', strtotime($schedule['hora_inicio'])) . " - " . date('g:i A', strtotime($schedule['hora_fin'])),
                'horas' => number_format($horas, 2)
            ];
        }
        
        $topProducts = []; 
        $chartUrls = $this->generateCharts($schedules, $topProducts, $clientRegistrations);
        
        $dataForView = [
            'reportTitle' => 'Reporte semanal de producción',
            'subTitle' => "Semana: " . date('d/m/Y', strtotime($startDate)) . " - " . date('d/m/Y', strtotime($endDate)),
            'scheduleDetails' => $scheduleDetails,
            'dateColumnTitle' => 'Día',
            'totalHoras' => number_format($totalHoras, 2),
            'topProducts' => null, 
            'allClients' => $allClients,
            'logoSrc' => $logoSrc,
            'reportDate' => $reportDate,
            'chartUrls' => $chartUrls
        ];

        // RENDERIZA LA VISTA
        $html = $this->renderPdfViewToString('pdf_report.php', $dataForView);

        // GENERA EL PDF
        $this->dompdf->loadHtml($html);
        $this->dompdf->setPaper('A4', 'portrait');
        $this->dompdf->render();
        
        // DESCARGA
        $this->dompdf->stream("Reporte_Semanal_$startDate.pdf", ["Attachment" => true]);
    }
}
?>