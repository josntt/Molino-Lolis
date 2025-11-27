<?php

include_once "app/models/UserModel.php";

class UserController extends BaseController {

    private $model;

    // Constructor para inicializar el modelo de usuario
    /**
     * Constructor del controlador.
     *
     * @param object $conexion Objeto de conexión a la base de datos.
     */
    public function __construct($conexion) {
        $this->model = new UserModel($conexion);
    }
    
    
    // Funcion para codigo de error y redigir segun el rol
    /**
     * Redirige a la página adecuada con un código de error.
     * Determina la redirección basada en el rol del usuario.
     *
     * @param string $error_code Código de error para pasar en la URL.
     * @return void
     */
    private function redirectWithError($error_code) {
        $location = "index.php?accion=login"; 
        
        if (isset($_SESSION['usuario_rol'])) {
            if ($_SESSION['usuario_rol'] == 'Cliente') {
                $location = "index.php?accion=profile";
            } else if (in_array($_SESSION['usuario_rol'], ['Administrador', 'Trabajador'])) {
                $location = "index.php?accion=dashboard";
            }
        }
        
        header("Location: " . BASE_URL . $location . "&error=" . $error_code);
        exit;
    }
    
    // Procesa el formulario de Registro (do_register)
    /**
     * Procesa el registro de un nuevo cliente.
     * Recibe datos por POST.
     *
     * @return void Redirige con el estado del registro.
     */
    public function doRegister() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        
        // RECOGER DATOS
        $nombre = $_POST['nombre'] ?? '';
        $apellidos = $_POST['apellidos'] ?? '';
        $genero = $_POST['genero'] ?? '';
        $correo = $_POST['correo'] ?? '';
        $telefono = $_POST['telefono'] ?? '';
        $contrasena = $_POST['contrasena'] ?? '';
        $confirmar_contrasena = $_POST['confirmar_contrasena'] ?? '';

        //  VALIDACIÓN DEL BACKEND
        if (empty($nombre) || empty($apellidos) || empty($genero) || empty($correo) || empty($contrasena)) {
            header("Location: " . BASE_URL . "index.php?accion=register&error=campos_vacios");
            exit;
        }

        // ---  INICIO DE VALIDACIÓN DE FORMATO ---
        // 1. Validar Nombre y Apellidos (solo letras, espacios, tildes y 'ñ')
        // La expresión regular permite letras (mayúsculas y minúsculas), tildes, ñ y espacios.
        if (!preg_match("/^[a-zA-ZáéíóúÁÉÍÓÚñÑ ]+$/", $nombre) || !preg_match("/^[a-zA-ZáéíóúÁÉÍÓÚñÑ ]+$/", $apellidos)) {
            header("Location: " . BASE_URL . "index.php?accion=register&error=nombre_invalido");
            exit;
        }

        // 2. Validar Teléfono (si no está vacío)
        // Debe tener 10 dígitos numéricos exactos.
        if (!empty($telefono) && !preg_match("/^[0-9]{10}$/", $telefono)) {
            header("Location: " . BASE_URL . "index.php?accion=register&error=telefono_invalido");
            exit;
        }
        // ------

        if ($contrasena !== $confirmar_contrasena) {
            header("Location: " . BASE_URL . "index.php?accion=register&error=password_no_coincide");
            exit;
        }
            $usuarioExistente = $this->model->findUserByEmail($correo);
            
            if ($usuarioExistente) {
                header("Location: " . BASE_URL . "index.php?accion=register&error=email_existe");
                exit;
            }
            // SI TODO ESTÁ BIEN REGISTRA
            $pass_hash = password_hash($contrasena, PASSWORD_DEFAULT);
            $registro = $this->model->registerClient($nombre, $apellidos, $genero, $correo, $pass_hash, $telefono);
        
            if ($registro) {
                header("Location: " . BASE_URL . "index.php?accion=login&registro=exitoso");
                exit;
            } else {
                header("Location: " . BASE_URL . "index.php?accion=register&error=registro_fallido");
                exit;
            }
        }
    }

   
    // FUNCION PARA CREAR USUARIO SIENDO ADMIN
    /**
     * Crea un usuario (Admin/Trabajador/Cliente) desde el panel de administración.
     * Recibe datos por POST.
     *
     * @return void Redirige con el estado de la creación.
     */
    public function adminCreateUser() {
        // Verifica que sea un admin quien hace la petición
        if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_rol'] != 'Administrador') {
            header("Location: " . BASE_URL . "index.php?accion=login&error=acceso_denegado");
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            // Documentación: Recolección de datos del formulario
            $user_type = $_POST['user_type'] ?? '';
            $first_name = $_POST['nombre'] ?? '';
            $last_name = $_POST['apellidos'] ?? '';
            $email = $_POST['correo'] ?? '';
            $password = $_POST['contrasena'] ?? '';
            $confirm_password = $_POST['confirmar_contrasena'] ?? '';
            $gender = $_POST['genero'] ?? '';
            $phone = $_POST['telefono'] ?? '';
            $position = $_POST['puesto'] ?? '';

            // Validación de datos del backend
            if (empty($user_type) || empty($first_name) || empty($last_name) || empty($email) || empty($password) || empty($gender)) {
                header("Location: " . BASE_URL . "index.php?accion=manage_users&error=campos_vacios");
                exit;
            }

            // ---INICIO DE VALIDACIÓN DE FORMATO ---
            // 1. Validar Nombre y Apellidos
            if (!preg_match("/^[a-zA-ZáéíóúÁÉÍÓÚñÑ ]+$/", $first_name) || !preg_match("/^[a-zA-ZáéíóúÁÉÍÓÚñÑ ]+$/", $last_name)) {
                header("Location: " . BASE_URL . "index.php?accion=manage_users&error=nombre_invalido");
                exit;
            }

            // 2. Validar Teléfono (si no está vacío)
            if (!empty($phone) && !preg_match("/^[0-9]{10}$/", $phone)) {
                header("Location: " . BASE_URL . "index.php?accion=manage_users&error=telefono_invalido");
                exit;
            }
            // ------

            //   validación de contraseña
            if ($password !== $confirm_password) {
                header("Location: " . BASE_URL . "index.php?accion=manage_users&error=password_no_coincide");
                exit;
            }
            
            $existing_user = $this->model->findUserByEmail($email);
            if ($existing_user) {
                header("Location: ". BASE_URL ."index.php?accion=manage_users&error=email_existe");
                exit;
            }

            $password_hash = password_hash($password, PASSWORD_DEFAULT);
            $success = false;

            if ($user_type == 'cliente') {
                $success = $this->model->registerClient($first_name, $last_name, $gender, $email, $password_hash, $phone);
            } else if ($user_type == 'trabajador') {
                $success = $this->model->createTrabajador($first_name, $last_name, $gender, $email, $password_hash, $phone, $position);
            }

            if ($success) {
                header("Location: " . BASE_URL . "index.php?accion=manage_users&status=created");
            } else {
                header("Location: " . BASE_URL . "index.php?accion=manage_users&error=creation_failed");
            }
            exit;
        }
    }

    
    // Procesa el formulario de Login (do_login)
    /**
     * Procesa el inicio de sesión.
     * Recibe datos por POST.
     *
     * @return void Redirige según el rol o error.
     */
    public function doLogin() {
       
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $correo = $_POST['correo'] ?? '';
            $contrasena = $_POST['contrasena'] ?? '';
            $usuario = $this->model->findUserByEmail($correo);

            if ($usuario && password_verify($contrasena, $usuario['contrasena'])) {
                $_SESSION['usuario_id'] = $usuario['id'];
                $_SESSION['usuario_nombre'] = $usuario['nombre'];
                $_SESSION['usuario_rol'] = $usuario['rol'];

                if ($usuario['rol'] == 'Administrador' || $usuario['rol'] == 'Trabajador') {
                    header("Location: " . BASE_URL . "index.php?accion=dashboard");
                    exit;
                } else {
                    header("Location: " . BASE_URL . "index.php?accion=profile");
                    exit;
                }
            }

            header("Location: " . BASE_URL . "index.php?accion=login&error=credenciales_invalidas");
            exit;
        }
    }

    
    // FUNCION PARA ACTUALIZAR AL USUARIO
    /**
     * Actualiza la información de un usuario.
     * Puede ser el propio usuario o un administrador editando a otro.
     *
     * @return void Redirige con el estado de la actualización.
     */
    public function updateUser() {
        
        if ($_SERVER['REQUEST_METHOD'] != 'POST' || !isset($_SESSION['usuario_id'])) {
            header("Location: " . BASE_URL . "index.php?accion=login");
            exit;
        }

        $user_id = $_POST['user_id'];
        $user_type = $_POST['user_type'];

        $data = [
            'nombre' => $_POST['nombre'] ?? '',
            'apellidos' => $_POST['apellidos'] ?? '',
            'correo' => $_POST['correo'] ?? '',
            'telefono' => $_POST['telefono'] ?? null,
        ];

        
        if ($user_type == 'cliente' || $user_type == 'trabajador') {
            $data['genero'] = $_POST['genero'] ?? '';
        }
        if ($user_type == 'trabajador') {
            $data['puesto'] = $_POST['puesto'] ?? '';
        }
        
        if ($_SESSION['usuario_rol'] != 'Administrador' && $_SESSION['usuario_id'] != $user_id) {
             $this->redirectWithError('update_permission_denied');
        }

        // --- INICIO DE VALIDACIÓN DE FORMATO 
        // Validar Nombre y Apellidos
        if (!preg_match("/^[a-zA-ZáéíóúÁÉÍÓÚñÑ ]+$/", $data['nombre']) || !preg_match("/^[a-zA-ZáéíóúÁÉÍÓÚñÑ ]+$/", $data['apellidos'])) {
            // COMENTARIO NUEVO: Si es un admin editando, redirige a manage_users
            if ($_SESSION['usuario_rol'] == 'Administrador' && $_SESSION['usuario_id'] != $user_id) {
                header("Location: " . BASE_URL . "index.php?accion=manage_users&error=nombre_invalido");
            } else {
                // COMENTARIO NUEVO: Si es el usuario editando su perfil, usa redirectWithError
                $this->redirectWithError('nombre_invalido');
            }
            exit;
        }
        // Validar Teléfono (si no está vacío)
        if (!empty($data['telefono']) && !preg_match("/^[0-9]{10}$/", $data['telefono'])) {
            if ($_SESSION['usuario_rol'] == 'Administrador' && $_SESSION['usuario_id'] != $user_id) {
                header("Location: " . BASE_URL . "index.php?accion=manage_users&error=telefono_invalido");
            } else {
                $this->redirectWithError('telefono_invalido');
            }
            exit;
        }
        //------
        
        $success = $this->model->updateUser($user_id, $user_type, $data);

        // se determina la URL de redirección en caso de error
        $redirect_on_error = "index.php?accion=dashboard&error=update_failed"; // Por defecto (propio perfil)
        if ($_SESSION['usuario_rol'] == 'Cliente') {
            $redirect_on_error = "index.php?accion=profile&error=update_failed";
        } else if ($_SESSION['usuario_rol'] == 'Administrador' && $_SESSION['usuario_id'] != $user_id) {
            $redirect_on_error = "index.php?accion=manage_users&error=update_failed";
        }
        
        if (!$success) {
            header("Location: " . BASE_URL . $redirect_on_error);
            exit;
        }

        if ($_SESSION['usuario_id'] == $user_id) {
            $_SESSION['usuario_nombre'] = $data['nombre'];
        }

        // Lógica de redirección de éxito
        if ($_SESSION['usuario_rol'] == 'Administrador' && $_SESSION['usuario_id'] != $user_id) {
            // Es un admin editando a otro usuario, vuelve a la lista
            header("Location: " . BASE_URL . "index.php?accion=manage_users&status=updated");
            exit;
        } else {
            //  Es el usuario editando su propio perfil
            if ($_SESSION['usuario_rol'] == 'Cliente') {
                header("Location: " . BASE_URL . "index.php?accion=profile&status=updated");
                exit;
            } else {
                // Admin o Trabajador editando su propio perfil
                header("Location: " . BASE_URL . "index.php?accion=dashboard&status=updated");
                exit;
            }
        }
    }

    // Eliminar usuarios
    /**
     * Elimina un usuario.
     * Puede ser auto-eliminación o eliminación por admin.
     *
     * @return void Redirige o muestra mensaje de despedida.
     */
    public function deleteUser() {
        
        if (!isset($_SESSION['usuario_id'])) {
            header("Location: " . BASE_URL . "index.php?accion=login");
            exit;
        }

        $user_id_to_delete = null;
        $user_type_to_delete = null;
        $is_self_delete = false;

        if (isset($_GET['id']) && isset($_GET['tipo'])) {
            if ($_SESSION['usuario_rol'] != 'Administrador') {
                 $this->redirectWithError('delete_permission_denied');
            }
            $user_id_to_delete = $_GET['id'];
            $user_type_to_delete = $_GET['tipo'];
        } else {
            if ($_SESSION['usuario_rol'] != 'Cliente') {
                $this->redirectWithError('admin_self_delete_not_allowed');
            }
            $user_id_to_delete = $_SESSION['usuario_id'];
            $user_type_to_delete = 'cliente';
            $is_self_delete = true;
        }

        if ($user_type_to_delete == 'administrador') {
            header("Location: " . BASE_URL . "index.php?accion=manage_users&error=admin_not_deletable");
            exit;
        }

        $success = $this->model->deleteUser($user_id_to_delete, $user_type_to_delete);

        if (!$success) {
            header("Location: " . BASE_URL . "index.php?accion=manage_users&error=delete_failed");
            exit;
        }

        if ($is_self_delete) {
            session_unset();
            session_destroy();
            
            echo "<!DOCTYPE html><html lang='es'><head><meta charset='UTF-8'>";
            echo "<title>Cuenta Eliminada</title><link href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css' rel='stylesheet'></head>";
            echo "<body class='bg-light'><div class='container text-center mt-5 p-5 border rounded shadow-sm bg-white'>";
            echo "<h1 class='display-4 text-danger'>Lamentamos que te vayas :(</h1>";
            echo "<p class='lead'>Esperamos verte pronto...</p>";
            echo "<a href='" . BASE_URL . "index.php' class='btn btn-success mt-3'>Volver a la página principal</a>";
            echo "</div></body></html>";
            exit;

        } else {
            header("Location: " . BASE_URL . "index.php?accion=manage_users&status=deleted");
            exit;
        }
    }


    //Funcion para el cierre de sesion del usuario
    /**
     * Cierra la sesión del usuario.
     *
     * @return void Redirige al login.
     */
    public function logout() {
        session_unset();
        session_destroy();
        header("Location: " . BASE_URL . "index.php?accion=login&logout=exitoso");
        exit;
    }
}
?>