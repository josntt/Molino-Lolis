<?php
    // El controlador nos pasa las variables:
    // $reportTitle, $subTitle, $scheduleDetails, $totalHoras, $topProducts, $logoSrc, $reportDate, $allClients, $chartUrls, $dateColumnTitle
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title><?php echo htmlspecialchars($reportTitle); ?></title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 10px; }
        h1 { color: #006A4E; font-size: 20px; }
        h2 { color: #333; border-bottom: 2px solid #FDCB0A; padding-bottom: 5px; font-size: 16px; }
        h3 { font-size: 14px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 15px; }
        th, td { border: 1px solid #ddd; padding: 6px; text-align: left; }
        th { background-color: #f2f2f2; }
        .total { font-weight: bold; font-size: 1.1rem; }
        
        /* Estilos para el cabecero con logo y fecha */
        .header {
            width: 100%;
            margin-bottom: 20px;
        }
        .header-logo {
            width: 80px;
            height: 80px;
            float: right; /* logo a la derecha */
        }
        .header-info {
            float: left;
        }
        .report-date {
            font-size: 10px;
            color: #555;
        }
        /* Limpiador para floats */
        .clearfix::after {
            content: "";
            clear: both;
            display: table;
        }
        
        /* Estilo para la sección de gráficas */
        .chart-section {
            display: inline-block;
            width: 49%; 
            padding: 0;
            margin-bottom: 20px;
            vertical-align: top;
            box-sizing: border-box;
        }
    </style>
</head>
<body>

    <div class="header clearfix">
        <?php if (!empty($logoSrc)): ?>
            <img src="<?php echo $logoSrc; ?>" alt="Logo" class="header-logo">
        <?php endif; ?>
        
        <div class="header-info">
            <h1><?php echo htmlspecialchars($reportTitle); ?></h1>
            <p><strong><?php echo htmlspecialchars($subTitle); ?></strong></p>
            <p class="report-date">Reporte generado el: <?php echo htmlspecialchars($reportDate); ?></p>
        </div>
    </div>


    <h2>Detalle de horarios molidos</h2>
    <table>
        <thead>
            <tr>
                <th><?php echo htmlspecialchars($dateColumnTitle); ?></th>
                <th>Producto</th>
                <th>Horario</th>
                <th>Total Horas</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($scheduleDetails as $schedule): ?>
            <tr>
                <td><?php echo htmlspecialchars($schedule['fecha']); ?></td>
                <td><?php echo htmlspecialchars($schedule['producto_nombre']); ?></td>
                <td><?php echo htmlspecialchars($schedule['horario']); ?></td>
                <td><?php echo htmlspecialchars($schedule['horas']); ?> hrs</td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <p class='total'>Total de horas molidas: <?php echo htmlspecialchars($totalHoras); ?> hrs</p>

    <?php 
    // Muestra esta sección solo si $topProducts no es null y no está vacío
    if (isset($topProducts) && !empty($topProducts)): 
    ?>
        <h2>Productos más programados</h2>
        <table>
            <thead>
                <tr>
                    <th>Producto</th>
                    <th>Veces Programado</th>
                    <th>Porcentaje</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($topProducts as $product): ?>
                <tr>
                    <td><?php echo htmlspecialchars($product['nombre']); ?></td>
                    <td><?php echo htmlspecialchars($product['total_horarios']); ?></td>
                    <td><?php echo htmlspecialchars($product['porcentaje']); ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
    
    <?php 
    //  sección de Clientes
    if (isset($allClients) && !empty($allClients)): 
    ?>
        <h2>Reporte de Clientes</h2>
        <h3>Total de clientes registrados: <?php echo count($allClients); ?></h3>
        <table>
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Correo</th>
                    <th>Teléfono</th>
                    <th>Fecha de registro</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($allClients as $client): ?>
                <tr>
                    <td><?php echo htmlspecialchars($client['nombre'] . ' ' . $client['apellidos']); ?></td>
                    <td><?php echo htmlspecialchars($client['correo']); ?></td>
                    <td><?php echo htmlspecialchars($client['telefono'] ?: 'N/A'); ?></td>
                    <td><?php echo htmlspecialchars(date('d/m/Y', strtotime($client['fecha_registro']))); ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
    
    <div style="page-break-before: always;">
        <h2>Análisis gráfico</h2>

        <div class="clearfix">
            
            <?php if (!empty($chartUrls['pie'])): ?>
                <div class="chart-section" style="width: 45%;">
                    <h3> Distribución de productos</h3>
                    <img src="<?php echo htmlspecialchars($chartUrls['pie']); ?>" style="width: 100%; height: auto; border: 1px solid #ccc;">
                </div>
            <?php endif; ?>
            
            <?php if (!empty($chartUrls['line'])): ?>
                <div class="chart-section" style="width: 53%; float: right;">
                    <h3> Crecimiento de clientes</h3>
                    <img src="<?php echo htmlspecialchars($chartUrls['line']); ?>" style="width: 100%; height: auto; border: 1px solid #ccc;">
                </div>
            <?php endif; ?>
        </div>

        </div>
    
</body>
</html>