<?php
    // app/views/admin/reports.php
    
    $page_title = 'Reporte de producción';
    $body_class = 'bg-warning-subtle';
    include_once "app/views/admin/templates/admin_header.php";

    // --- LÓGICA DE FECHAS PARA LA VISTA ---
    
    // Arrays para idioma español
    $meses = [
        1 => 'Enero', 2 => 'Febrero', 3 => 'Marzo', 4 => 'Abril', 5 => 'Mayo', 6 => 'Junio',
        7 => 'Julio', 8 => 'Agosto', 9 => 'Septiembre', 10 => 'Octubre', 11 => 'Noviembre', 12 => 'Diciembre'
    ];
    //OJO: N de PHP date() empieza en 1 (Lunes)
    $dias_semana_cortos = [1 => 'Lun', 2 => 'Mar', 3 => 'Mié', 4 => 'Jue', 5 => 'Vie', 6 => 'Sáb', 7 => 'Dom'];
    // -----------------------------------------------------

    // $currentYear, $currentMonth, etc., vienen del PageController
    $monthName = $meses[(int)$currentMonth];

    // Función para generar las semanas del mes
    function getWeeksOfMonth($year, $month, $meses_array) { // Se pasa el array de meses
        $weeks = [];
        $date = new DateTime("$year-$month-01");
        $daysInMonth = $date->format('t');
        $monthNameFunc = $meses_array[(int)$month]; // Se usa el array
        
        for ($day = 1; $day <= $daysInMonth; $day += 7) {
            $start = new DateTime("$year-$month-$day");
            $end = (clone $start)->modify('+6 days');
            
            if ($end->format('m') != $month) {
                $end->modify('last day of this month');
            }
            
            $weeks[] = [
                'start' => $start->format('Y-m-d'),
                'end'   => $end->format('Y-m-d'),
                'label' => $start->format('d') . ' ' . $monthNameFunc . ' - ' . $end->format('d') . ' ' . $monthNameFunc
            ];
        }
        return $weeks;
    }
    
    $weeksOfMonth = getWeeksOfMonth($currentYear, $currentMonth, $meses);
?>

<div class="container-fluid fade-in">
    <div class="row g-4">

        <div class="col-lg-4">
            
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-success-subtle text-success fw-bold">
                    <i class="bi bi-calendar-month me-2"></i>
                    Seleccione un rango
                </div>
                <div class="card-body text-center">
                    <form action="<?php echo BASE_URL; ?>index.php" method="GET" class="d-flex justify-content-center gap-2">
                        <input type="hidden" name="accion" value="manage_reports">
                        
                        <select name="year" class="form-select" style="width: auto;">
                            <?php for ($y = date('Y'); $y >= 2024; $y--): ?>
                                <option value="<?php echo $y; ?>" <?php if ($y == $currentYear) echo 'selected'; ?>>
                                    <?php echo $y; ?>
                                </option>
                            <?php endfor; ?>
                        </select>
                        
                        <select name="month" class="form-select" style="width: auto;">
                            <?php foreach ($meses as $m => $nombreMes): ?>
                                <option value="<?php echo $m; ?>" <?php if ($m == $currentMonth) echo 'selected'; ?>>
                                    <?php echo $nombreMes; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <button type="submit" class="btn btn-warning fw-bold">Aceptar</button>
                    </form>
                </div>
            </div>

            <div class="card shadow-sm">
                <div class="card-header bg-success-subtle text-success fw-bold">
                    <i class="bi bi-calendar-week me-2"></i>
                    Seleccione la semana
                </div>
                <div class="list-group list-group-flush text-center">
                    <?php foreach ($weeksOfMonth as $week): ?>
                        <a href="<?php echo BASE_URL; ?>index.php?accion=manage_reports&year=<?php echo $currentYear; ?>&month=<?php echo $currentMonth; ?>&week_start=<?php echo $week['start']; ?>&week_end=<?php echo $week['end']; ?>"
                           class="list-group-item list-group-item-action <?php if ($week['start'] == $weekStartDate) echo 'active bg-warning border-warning text-dark'; ?>">
                            <?php echo $week['label']; ?>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>

        </div> <div class="col-lg-8">

            <?php if ($weeklyReport !== null): ?>
            <div class="card shadow-sm mb-4 report-card">
                <div class="card-header bg-warning text-success fw-bold">
                    <i class="bi bi-calendar-week-fill me-2"></i>
                    REPORTE SEMANAL
                </div>
                <div class="card-body">
                    <h5 class="card-title">Semana: <?php echo htmlspecialchars(date('d/m/Y', strtotime($weekStartDate)) . ' - ' . date('d/m/Y', strtotime($weekEndDate))); ?></h5>
                    
                    <p class="fw-bold text-success mb-1">Horarios en que se molió:</p>
                    <ul class="list-unstyled" style="max-height: 150px; overflow-y: auto;">
                        <?php 
                        $totalHorasSemana = 0;
                        if (empty($weeklyReport)): ?>
                            <li class="text-muted">No hay registros esta semana.</li>
                        <?php else:
                            foreach ($weeklyReport as $schedule):
                                $inicio = new DateTime($schedule['hora_inicio']);
                                $fin = new DateTime($schedule['hora_fin']);
                                $diff = $fin->diff($inicio);
                                $totalHorasSemana += $diff->h + ($diff->i / 60);
                                $dateObj = new DateTime($schedule['fecha']);
                                $diaSemana = $dias_semana_cortos[$dateObj->format('N')];
                        ?>
                            <li>
                                <strong><?php echo htmlspecialchars($schedule['producto_nombre']); ?>:</strong>
                                <?php echo htmlspecialchars($diaSemana . ' ' . $dateObj->format('d/m')); ?> |
                                <?php echo htmlspecialchars(date('g:i A', $inicio->getTimestamp())); ?> - 
                                <?php echo htmlspecialchars(date('g:i A', $fin->getTimestamp())); ?>
                            </li>
                        <?php 
                            endforeach;
                        endif; ?>
                    </ul>
                    <hr>
                    <p class="fw-bold fs-5">Total de horas molidas: <?php echo number_format($totalHorasSemana, 2); ?> hrs</p>
                    
                    <a href="<?php echo BASE_URL; ?>index.php?accion=download_weekly_report&start=<?php echo $weekStartDate; ?>&end=<?php echo $weekEndDate; ?>" class="btn btn-warning fw-bold">Descargar PDF</a>
                </div>
            </div>
            <?php endif; ?>

            <div class="card shadow-sm mb-4 report-card">
                <div class="card-header bg-warning text-success fw-bold">
                    <i class="bi bi-calendar-month-fill me-2"></i>
                    REPORTE MENSUAL
                </div>
                <div class="card-body">
                    <h5 class="card-title">Mes seleccionado: <?php echo $monthName . ' ' . $currentYear; ?></h5>
                    <p><strong>Total de horarios creados este mes:</strong> <?php echo $currentMonthScheduleCount; ?></p>
                    
                    <p class="fw-bold text-success mb-1">Días en que se molió:</p>
                    <ul class="list-unstyled" style="max-height: 150px; overflow-y: auto;">
                        <?php 
                        $totalHorasMes = 0;
                        if (empty($monthlyReport)): ?>
                            <li class="text-muted">No hay registros este mes.</li>
                        <?php else:
                            foreach ($monthlyReport as $schedule):
                                $inicio = new DateTime($schedule['hora_inicio']);
                                $fin = new DateTime($schedule['hora_fin']);
                                $diff = $fin->diff($inicio);
                                $totalHorasMes += $diff->h + ($diff->i / 60);
                                $dateObj = new DateTime($schedule['fecha']);
                                $diaSemana = $dias_semana_cortos[$dateObj->format('N')];
                        ?>
                            <li>
                                <strong><?php echo htmlspecialchars($schedule['producto_nombre']); ?>:</strong>
                                <?php echo htmlspecialchars($diaSemana . ' ' . $dateObj->format('d/m/Y')); ?> |
                                <?php echo htmlspecialchars(date('g:i A', $inicio->getTimestamp())); ?> - 
                                <?php echo htmlspecialchars(date('g:i A', $fin->getTimestamp())); ?>
                            </li>
                        <?php 
                            endforeach;
                        endif; ?>
                    </ul>
                    <hr>
                    <p class="fw-bold fs-5">Total de horas molidas: <?php echo number_format($totalHorasMes, 2); ?> hrs</p>
                    
                    <a href="<?php echo BASE_URL; ?>index.php?accion=download_monthly_report&year=<?php echo $currentYear; ?>&month=<?php echo $currentMonth; ?>" class="btn btn-warning fw-bold">Descargar PDF</a>
                </div>
            </div>
            
            <div class="card shadow-sm report-card mb-4">
                <div class="card-header bg-success-subtle text-success fw-bold">
                    <i class="bi bi-star-fill me-2"></i>
                    Productos más programados (<?php echo $monthName; ?>)
                </div>
                <div class="card-body">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Producto</th>
                                <th>Veces programado</th>
                                <th>Porcentaje</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($topProducts)): ?>
                                <tr><td colspan="3" class="text-muted">No hay datos.</td></tr>
                            <?php else: ?>
                                <?php foreach ($topProducts as $product): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($product['nombre']); ?></td>
                                    <td><?php echo htmlspecialchars($product['total_horarios']); ?></td>
                                    <td><?php echo htmlspecialchars($product['porcentaje']); ?></td>
                                </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            
            <div class="card shadow-sm report-card">
                <div class="card-header bg-success-subtle text-success fw-bold">
                    <i class="bi bi-people-fill me-2"></i>
                    Reporte de clientes
                </div>
                <div class="card-body">
                    <h5 class="card-title">Total de clientes registrados: <?php echo count($allClients); ?></h5>
                    <div class="table-responsive" style="max-height: 250px; overflow-y: auto;">
                        <table class="table table-striped table-sm">
                            <thead>
                                <tr>
                                    <th>Nombre</th>
                                    <th>Correo</th>
                                    <th>Teléfono</th>
                                    <th>Fecha de Registro</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($allClients)): ?>
                                    <tr><td colspan="4" class="text-muted">No hay clientes registrados.</td></tr>
                                <?php else: ?>
                                    <?php foreach ($allClients as $client): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($client['nombre'] . ' ' . $client['apellidos']); ?></td>
                                        <td><?php echo htmlspecialchars($client['correo']); ?></td>
                                        <td><?php echo htmlspecialchars($client['telefono'] ?: 'N/A'); ?></td>
                                        <td><?php echo htmlspecialchars(date('d/m/Y', strtotime($client['fecha_registro']))); ?></td>
                                    </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div> 
    </div> 
</div> 

<?php 
    //  JS personalizado
    $page_custom_js = <<<JS
    <style>
        .fade-in {
            animation: fadeInAnimation 0.5s ease-in-out;
        }
        .report-card {
            animation: popInAnimation 0.4s ease-out;
        }
        @keyframes fadeInAnimation {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        @keyframes popInAnimation {
            from { opacity: 0; transform: scale(0.95); }
            to { opacity: 1; transform: scale(1); }
        }
    </style>
JS;
    $page_custom_js .= '<script src="' . BASE_URL . 'public/js/admin_reports.js" defer></script>';
    include_once "app/views/admin/templates/admin_footer.php";
?>