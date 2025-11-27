<?php 
    include_once "app/views/templates/header.php";
?>

<section class="news-page-container">

    <!-- ENCABEZADO -->
    <header class="news-header">
        <h1>Avisos</h1>
        <p>Las últimas noticias al momento del molino.</p>
    </header>

    <div>
        <?php if (isset($error_db)): ?>
            
            <p class="news-error">
                <?php echo htmlspecialchars($error_db); ?>
            </p>

        <?php elseif (empty($news)): ?>
            
            <p class="news-empty">
                No hay avisos publicados en este momento.
            </p>

        <?php else: ?>
            
            <div class="news-list">
                <?php foreach ($news as $notice): ?>
                    <?php
                        $timestamp = strtotime($notice['fecha_publicacion']);
                        $day   = date('d', $timestamp);
                        $month = strftime('%b', $timestamp); // ej. nov, dic
                        $year  = date('Y', $timestamp);
                    ?>
                    
                    <article class="news-card">
                        
                        <!-- columnita de fecha -->
                        <div class="news-date-pill">
                            <span class="news-date-day"><?php echo $day; ?></span>
                            <span class="news-date-month">
                                <?php echo mb_strtoupper($month, 'UTF-8'); ?>
                            </span>
                            <span class="news-date-year"><?php echo $year; ?></span>
                        </div>

                        <!-- contenido -->
                        <div class="news-card-body">
                            
                            <h2 class="news-title">
                                <?php echo htmlspecialchars($notice['titulo']); ?>
                            </h2>
                            <p class="news-meta">
                                Publicado el 
                                <?php echo htmlspecialchars(date('d/m/Y', $timestamp)); ?>
                            </p>
                            <p class="news-content">
                                <?php echo nl2br(htmlspecialchars($notice['contenido'])); ?>
                            </p>
                        </div>

                    </article>
                <?php endforeach; ?>
            </div>

        <?php endif; ?>
    </div>

</section>

<?php 
    include_once "app/views/templates/footer.php";
?>
