<?php
// Controlador para funciones comunes de controladores(Solo tiene una)
class BaseController {

    
     // Logica de autentificacion centralizada que verifica si se ha iniciado sesión si tiene un 
     // rol permitido y redirige si no es asi, usa variables roles_permitidos como array de roles
    /**
     * Verifica la autenticación y autorización del usuario.
     * Redirige al login si no hay sesión, o al dashboard si no tiene el rol adecuado.
     *
     * @param array $roles_permitidos Lista de roles permitidos para acceder. Si está vacío, solo verifica sesión.
     * @return void Redirige si falla la verificación.
     */
    protected function checkAuth($roles_permitidos = []) {
        if (!isset($_SESSION['usuario_id'])) {
            // Si no ha iniciado sesión redirigimos al login
            header("Location: " . BASE_URL . "index.php?accion=login&error=acceso_denegado");
            exit;
        }
        
        // Si se especificaron roles verificam que el usuario tenga uno de ellos
        if (!empty($roles_permitidos)) {
            if (!in_array($_SESSION['usuario_rol'], $roles_permitidos)) {
                // Tiene sesión, pero no el rol. Redirigimos al dashboard (no al login).
                header("Location: " . BASE_URL . "index.php?accion=dashboard&error=acceso_denegado");
                exit;
            }
        }
    }

}
?>
