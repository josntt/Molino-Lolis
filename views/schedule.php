<?php 
    // Header común
    include_once "app/views/templates/header.php";

    // Ruta base segura para CSS/JS
    $BASE = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\');
    if ($BASE === '/' || $BASE === '\\') { $BASE = ''; }

    // Helper para formatear fechas y horas
    $fmtFecha = function($f){ 
        return date('d/m/Y', strtotime($f)); 
    };

    $fmtHora  = function($h){ 
        return date('g:i A', strtotime($h)); 
    };

    // Función para convertir día y mes a español
    function getDiaEnEspanol($fecha) {
        $dias = [
            "Sunday"    => "Domingo", 
            "Monday"    => "Lunes", 
            "Tuesday"   => "Martes", 
            "Wednesday" => "Miércoles", 
            "Thursday"  => "Jueves", 
            "Friday"    => "Viernes", 
            "Saturday"  => "Sábado"
        ];
        $meses = [
            "January"   => "Enero", 
            "February"  => "Febrero", 
            "March"     => "Marzo", 
            "April"     => "Abril", 
            "May"       => "Mayo", 
            "June"      => "Junio", 
            "July"      => "Julio", 
            "August"    => "Agosto", 
            "September" => "Septiembre", 
            "October"   => "Octubre", 
            "November"  => "Noviembre", 
            "December"  => "Diciembre"
        ];

        $fechaFormateada = date('l, d/m/Y', strtotime($fecha));
        $fechaEnEspanol = str_replace(array_keys($dias), array_values($dias), $fechaFormateada);
        return str_replace(array_keys($meses), array_values($meses), $fechaEnEspanol);
    }
?>

<section class="horarios-page">

  <!-- Encabezado -->
  <header class="horarios-head">
    <h1>Horarios de la molida</h1>
    <p>Consulta las fechas programadas para nuestros productos.</p>
    <span class="head-underline"></span>
  </header>

  <!-- Contenido -->
  <?php if (isset($error_db)): ?>
    <p class="horarios-msg error"><?= htmlspecialchars($error_db) ?></p>

  <?php elseif (empty($schedules)): ?>
    <p class="horarios-msg">No hay horarios programados próximamente. Por favor, vuelve más tarde.</p>

  <?php else: ?>
    <div class="horarios-viewport">
        <?php 
            // Agrupar horarios por fecha
            $groupedSchedules = [];
            foreach ($schedules as $sch) {
                $fecha = date('Y-m-d', strtotime($sch['fecha']));
                $groupedSchedules[$fecha][] = $sch;
            }
        ?>

        <?php foreach ($groupedSchedules as $fecha => $schedulesForDate): ?>
            <div class="horario-dia">
                <h2 class="fecha-dia"><?= getDiaEnEspanol($fecha) ?></h2>
                <div class="horario-dia-contenido">

                    <?php foreach ($schedulesForDate as $schedule): ?>
                        <?php
                            // Nombre del producto en minúsculas
                            $nombreProd = mb_strtolower($schedule['product_name'], 'UTF-8');

                            // Solo CHILE en rojo, todo lo demás neutro
                            if (strpos($nombreProd, 'chile') !== false) {
                                $productClass = 'prod-chile';
                            } else {
                                $productClass = 'prod-neutral';
                            }
                        ?>

                        <div class="horario-card <?= $productClass ?>">
                            <div class="card-head">
                                <h3 class="card-title"><?= htmlspecialchars($schedule['product_name']) ?></h3>
                            </div>
                            <div class="card-body">
                                <p class="row">
                                    <span class="k">Horario:</span>
                                    <span class="v">
                                        <?= $fmtHora($schedule['hora_inicio']) ?> – <?= $fmtHora($schedule['hora_fin']) ?>
                                    </span>
                                </p>
                                <p class="row">
                                    <span class="k">Tipo:</span>
                                    <span class="v"><?= htmlspecialchars($schedule['tipo_molida']) ?></span>
                                </p>
                            </div>
                        </div>
                    <?php endforeach; ?>

                </div>
            </div>
        <?php endforeach; ?>
    </div>
  <?php endif; ?>

</section>

<!-- Enlaza el JS del carrusel (colócalo en public/js/horarios.js) -->
<script defer src="<?= $BASE ?>/public/js/horarios.js"></script>

<?php 
    // Footer común
    include_once "app/views/templates/footer.php";
?>
