<?php 
    // Detecta la página actual para el estilo "activo"
    $current_page = $_GET['accion'] ?? 'main'; 
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Molino "Lolis" - Inicio</title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>public/css/styles.css">
    <link rel="icon" href="<?php echo BASE_URL; ?>public/media/LOGO.png" type="image/png">
</head>
<body>
    <header class="main-header">
        <nav class="main-nav">
            
            <div class="nav-logo-container">
                <a href="<?php echo BASE_URL; ?>index.php?accion=main">
                    <img src="<?php echo BASE_URL; ?>public/media/LOGO.png" alt="Logo Molino Lolis" class="nav-logo-img">
                </a>
            </div>

            <ul class="nav-links">
                <li><a href="<?php echo BASE_URL; ?>index.php?accion=main" class="<?php echo ($current_page == 'main') ? 'active' : ''; ?>">Inicio</a></li>
                <li><a href="<?php echo BASE_URL; ?>index.php?accion=products" class="<?php echo ($current_page == 'products') ? 'active' : ''; ?>">Productos</a></li>
                <li><a href="<?php echo BASE_URL; ?>index.php?accion=services" class="<?php echo ($current_page == 'services') ? 'active' : ''; ?>">Servicios</a></li>
                <li><a href="<?php echo BASE_URL; ?>index.php?accion=schedule" class="<?php echo ($current_page == 'schedule') ? 'active' : ''; ?>">Horarios</a></li>
                <li><a href="<?php echo BASE_URL; ?>index.php?accion=anuncios" class="<?php echo ($current_page == 'anuncios') ? 'active' : ''; ?>">Anuncios</a></li>
                <li><a href="<?php echo BASE_URL; ?>index.php?accion=about" class="<?php echo ($current_page == 'about') ? 'active' : ''; ?>">Nosotros</a></li>
                <li><a href="<?php echo BASE_URL; ?>index.php?accion=contact" class="<?php echo ($current_page == 'contact') ? 'active' : ''; ?>">Contacto</a></li>
                <li><a href="<?php echo BASE_URL; ?>index.php?accion=dudas" class="<?php echo ($current_page == 'dudas') ? 'active' : ''; ?>">Dudas</a></li>
            </ul>

            <div class="auth-buttons">
                <?php 
                if (isset($_SESSION['usuario_id'])) {
                    $url_perfil = $_SESSION['usuario_rol'] == 'Cliente' 
                        ? BASE_URL . 'index.php?accion=profile' 
                        : BASE_URL . 'index.php?accion=dashboard';
                    echo '<a href="' . $url_perfil . '" class="btn-nav btn-account">Mi Cuenta</a>';
                    echo '<a href="' . BASE_URL . 'index.php?accion=logout' . '" class="btn-nav btn-logout">Salir</a>';
                } else {
                    echo '<a href="' . BASE_URL . 'index.php?accion=login' . '" class="btn-nav btn-login">Iniciar sesión</a>';
                }
                ?>
            </div>

        </nav>
    </header>
    <main>