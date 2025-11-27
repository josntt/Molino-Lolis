<?php
include_once "app/models/ServiceModel.php";

class ServiceController extends BaseController {

    private $model;

    /**
     * Constructor del controlador.
     *
     * @param object $conexion Objeto de conexión a la base de datos.
     */
    public function __construct($conexion) {
        $this->model = new ServiceModel($conexion);
    }

    
    //FUNCIÓN PARA CREAR NUEVO SERVICIO SOLO ADMIN Y TRABAJADOR ACCEDEN
    /**
     * Crea un nuevo servicio.
     * Recibe datos por POST.
     *
     * @return void Redirige con el estado de la creación.
     */
    public function createService() {
        $this->checkAuth(['Administrador', 'Trabajador']);
        
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            
            // Convierte el array de dias_disponibles[] en un string
            $days_string = null;
            if (isset($_POST['dias_disponibles']) && is_array($_POST['dias_disponibles'])) {
                $days_string = implode(',', $_POST['dias_disponibles']);
            }
            // ---------------------------

            // Recolecta los datos del formulario POST
            $data = [
                'tipo' => $_POST['tipo'],
                'nombre_servicio' => $_POST['nombre_servicio'],
                'descripcion' => $_POST['descripcion'] ?? '',
                'horario_inicio' => $_POST['horario_inicio'],
                'horario_fin' => $_POST['horario_fin'],
                'dias_disponibles' => $days_string // Se guarda el string
            ];

            // Llama al modelo para crear el registro
            $success = $this->model->createService($data);

            // REDIRIGIR según el resultado
            if ($success) {
                header("Location: " . BASE_URL . "index.php?accion=manage_services&status=created");
            } else {
                header("Location: " . BASE_URL . "index.php?accion=manage_services&error=create_failed");
            }
            exit;
        }
    }

    
    //FUNCIÓN DE ACTUALIZAR UN SERVICIO EXISTENTE
    /**
     * Actualiza un servicio existente.
     * Recibe datos por POST.
     *
     * @return void Redirige con el estado de la actualización.
     */
    public function updateService() {
        $this->checkAuth(['Administrador', 'Trabajador']);
        
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            
            // Obtiene el ID del campo oculto
            $id = $_POST['edit_id_servicio'];

            // Convierte el array de edit_dias_disponibles[] en un string
            $days_string = null;
            if (isset($_POST['edit_dias_disponibles']) && is_array($_POST['edit_dias_disponibles'])) {
                $days_string = implode(',', $_POST['edit_dias_disponibles']);
            }
            // ---------------------------

            // Recolecta los datos del formulario de edición
            $data = [
                'tipo' => $_POST['edit_tipo'],
                'nombre_servicio' => $_POST['edit_nombre_servicio'],
                'descripcion' => $_POST['edit_descripcion'] ?? '',
                'horario_inicio' => $_POST['edit_horario_inicio'],
                'horario_fin' => $_POST['edit_horario_fin'],
                'dias_disponibles' => $days_string // Se guarda el string
            ];

            // Llama al modelo para actualizar
            $success = $this->model->updateService($id, $data);

            if ($success) {
                header("Location: " . BASE_URL . "index.php?accion=manage_services&status=updated");
            } else {
                header("Location: " . BASE_URL . "index.php?accion=manage_services&error=update_failed");
            }
            exit;
        }
    }


    // FUNCIÓN PARA ELIMINAR SERVICIO
    /**
     * Elimina un servicio.
     *
     * @return void Redirige con el estado de la eliminación.
     */
    public function deleteService() {
        $this->checkAuth(['Administrador', 'Trabajador']);
        
        // El ID se obtiene de la URL (GET)
        if (isset($_GET['id'])) {
            $id = $_GET['id'];
            
            $success = $this->model->deleteService($id);

            if ($success) {
                header("Location: " . BASE_URL . "index.php?accion=manage_services&status=deleted");
            } else {
                header("Location: ". BASE_URL . "index.php?accion=manage_services&error=delete_failed");
            }
            exit;
        } else {
            // Si no hay ID solo redirige
            header("Location: ". BASE_URL . "index.php?accion=manage_services");
            exit;
        }
    }
}
?>