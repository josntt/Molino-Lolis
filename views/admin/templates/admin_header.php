<?php
    // Parcial: app/views/admin/templates/admin_header.php
    
    // Variables esperadas (definidas en la vista principal antes de incluir)
    
    // $page_title : El título de la página y el h1 del navbar
    if (!isset($page_title)) {
        $page_title = 'Panel de Administración';
    }
    
    // $body_class (Opcional): Clase CSS para el <body>
    if (!isset($body_class)) {
        $body_class = 'bg-warning-subtle'; 
    }

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <title><?php echo htmlspecialchars($page_title); ?> - Molino Lolis</title>
    
    <link rel="icon" href="<?php echo BASE_URL; ?>public/media/MolinoLogo.png" type="image/png">
    
    <script>
        (function() {
            const theme = localStorage.getItem('theme') || 'light';
            document.documentElement.setAttribute('data-bs-theme', theme);
        })();
    </script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>public/css/admin_theme.css">
    <?php
    if (isset($page_custom_css)) {
        echo $page_custom_css;
    }
    ?>
</head>
<body class="<?php echo htmlspecialchars($body_class); ?>">
    
    <nav class="navbar navbar-expand-lg bg-success shadow-sm sticky-top" data-bs-theme="dark">
    <div class="container-fluid">
            
            <a class="navbar-brand d-flex align-items-center" href="<?php echo BASE_URL; ?>index.php?accion=dashboard">
                <img src="<?php echo BASE_URL; ?>public/media/MolinoLogo.png"  alt="Logo" width="70" height="70" class="me-2 rounded-circle">
                <h1 class="h4 mb-0 text-white"><?php echo htmlspecialchars($page_title); ?></h1>
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#adminNavbarCollapse" aria-controls="adminNavbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="adminNavbarCollapse">
                
                <div class="d-lg-flex align-items-center text-white ms-auto mt-3 mt-lg-0">
                    
                    <div class="me-lg-3 text-lg-end mb-3 mb-lg-0">
                         <small>Usuario: <b><?php echo htmlspecialchars($usuario_nombre); ?></b></small><br>
                         <small>Rol: <b><?php echo htmlspecialchars($usuario_rol); ?></b></small>
                    </div>

                    <div class="d-grid gap-2 d-lg-flex align-items-center">
                        <?php 
                        // Lógica inteligente: No mostrar "Volver al Dashboard" si YA estamos en él
                        if ($page_title != 'Dashboard Administrativo'): ?>
                            <a href="<?php echo BASE_URL; ?>index.php?accion=dashboard" class="btn btn-outline-light fw-bold" title="Volver al Dashboard">
                                <i class="bi bi-arrow-left-circle me-1"></i>
                                Dashboard
                            </a>
                        <?php else: ?>
                            <a href="<?php echo BASE_URL; ?>index.php?accion=showEditForm" class="btn btn-info fw-bold">
                                <i class="bi bi-person-fill-gear me-1"></i>
                                Mis datos
                            </a>
                        <?php endif; ?>

                        <button id="theme-toggle-btn" class="btn btn-outline-light" title="Cambiar tema">
                            <i class="bi bi-moon-fill"></i>
                        </button>
                        <a href="<?php echo BASE_URL; ?>index.php" class="btn btn-light fw-bold" title="Ver Sitio Público">
                            <i class="bi bi-house-door-fill me-1"></i>
                            Ver Sitio
                        </a>
                        <a href="<?php echo BASE_URL; ?>index.php?accion=logout" class="btn btn-warning fw-bold" title="Cerrar Sesión">
                            <i class="bi bi-box-arrow-right me-1"></i>
                            Cerrar sesión
                        </a>
                    </div>

                </div>
            </div> </div>
    </nav>

    <div class="container my-4">