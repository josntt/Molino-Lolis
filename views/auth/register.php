<?php
// app/views/register.php
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro - Molino Lolis</title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>public/css/styles.css">
</head>
<body style="background-color: #f0f0f0;">

    <div class="login-container">
        <form id="registro-form" class="login-form" action="<?php echo BASE_URL; ?>index.php?accion=do_register" method="POST" novalidate>
            <h2>Crear cuenta</h2>

            <div class="progress-bar">
                <div class="progress-step active">1</div>
                <div class="progress-step">2</div>
                <div class="progress-step">3</div>
            </div>

            <?php 
            if (isset($error_message)): ?>
                <p class='alert alert-error' style='color:red; text-align:center;'><?php echo $error_message; ?></p>
            <?php endif; ?>

            <div class="form-step active">
                <div class="form-group">
                    <label for="nombre">Nombre(s):</label>
                    <input type="text" id="nombre" name="nombre" placeholder="Tu nombre" required>
                    <div class="error-message"></div>
                </div>
                <div class="form-group">
                    <label for="apellidos">Apellidos:</label>
                    <input type="text" id="apellidos" name="apellidos" placeholder="Tus apellidos" required>
                    <div class="error-message"></div>
                </div>
                <div class="form-group">
                    <label for="genero">Género:</label>
                    <select id="genero" name="genero" required>
                        <option value="">Selecciona tu género</option>
                        <option value="Femenino">Femenino</option>
                        <option value="Masculino">Masculino</option>
                        <option value="Otro">Otro</option>
                    </select>
                    <div class="error-message"></div>
                </div>
            </div>

            <div class="form-step">
                <div class="form-group">
                    <label for="correo">Correo electrónico:</label>
                    <input type="email" id="correo" name="correo" placeholder="ejemplo@correo.com" required>
                    <div class="error-message"></div>
                </div>
                <div class="form-group">
                    <label for="telefono">Teléfono:</label>
                    <input type="tel" id="telefono" name="telefono" placeholder="Tu número de teléfono" required>
                    <div class="error-message"></div>
                </div>
            </div>

            <div class="form-step">
                <div class="form-group">
                    <label for="contrasena">Contraseña:</label>
                    <input type="password" id="contrasena" name="contrasena" placeholder="Crea una contraseña" required>
                    <div class="error-message"></div>
                </div>
                <div class="form-group">
                    <label for="confirmar_contrasena">Confirmar contraseña:</label>
                    <input type="password" id="confirmar_contrasena" name="confirmar_contrasena" placeholder="Confirma tu contraseña" required>
                    <div class="error-message"></div>
                </div>
            </div>

            <div class="step-buttons">
                <button type="button" class="btn-secondary" id="btn-prev" style="display: none;">Anterior</button>
                <button type="button" class="btn-submit" id="btn-next">Siguiente</button>
            </div>

            <div class="register-link">
                <p>¿Ya tienes una cuenta?</p>
                <a href="<?php echo BASE_URL; ?>index.php?accion=login">Inicia sesión</a>
            </div>
        </form>
    </div>

    <script src="<?php echo BASE_URL; ?>public/js/UserForms.js" defer></script>
</body>
</html>