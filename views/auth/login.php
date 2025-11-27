<?php
// app/views/login.php
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar sesión - Molino Lolis</title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>public/css/styles.css">
</head>
<body style="background-color: #f0f0f0;"> <div class="login-container">
        
        <form class="login-form" action="<?php echo BASE_URL; ?>index.php?accion=do_login" method="POST">
        
            <h2>Iniciar sesión</h2>
            <div align="center">
                        <img src="<?php echo BASE_URL; ?>public/media/MolinoLogo.png" alt="Logo Molino Lolis" class="logo-img" width="50%">
            </div>
            
            <?php 
            if (isset($error_message)): ?>
                <p class='alert alert-error' style='color:red; text-align:center;'><?php echo $error_message; ?></p>
            <?php endif; ?>
            
            <?php if (isset($success_message)): ?>
                <p class='alert alert-success'><?php echo $success_message; ?></p>
            <?php endif; ?>
            
            <div class="form-group">
                
                <label for="correo">Correo electrónico:</label>
                <input type="email" id="correo" name="correo" placeholder="ejemplo@correo.com" required>
            </div>
            <div class="form-group">
                <label for="contrasena">Contraseña:</label>
                <input type="password" id="contrasena" name="contrasena" placeholder="Tu contraseña" required>
            </div>
            <button type="submit" class="btn-submit">Ingresar</button>
            <div class="register-link">
                <p>¿No tienes una cuenta?</p>
                <a href="<?php echo BASE_URL; ?>index.php?accion=register">Regístrate aquí!!!</a>
            </div>
            
            
        </form>
    </div>

</body>
</html>