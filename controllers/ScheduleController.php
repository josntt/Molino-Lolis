<?php

include_once "app/models/ScheduleModel.php";

class ScheduleController extends BaseController {

    private $model;

    /**
     * Constructor del controlador.
     *
     * @param object $conexion Objeto de conexión a la base de datos.
     */
    public function __construct($conexion) {
        $this->model = new ScheduleModel($conexion);
    }

    // FUNCIÓN PARA CREAR UN HORARIO
    /**
     * Crea un nuevo horario de molida.
     * Recibe datos por POST.
     *
     * @return void Redirige con el estado de la creación.
     */
    public function createSchedule() {
        $this->checkAuth(['Administrador', 'Trabajador']);
        
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            
            // Recolecta los datos del formulario
            $data = [
                'fecha' => $_POST['fecha'],
                'hora_inicio' => $_POST['hora_inicio'],
                'hora_fin' => $_POST['hora_fin'],
                'tipo_molida' => $_POST['tipo_molida'],
                'id_producto' => $_POST['id_producto'],
                'observaciones' => $_POST['observaciones'] ?? null,
                'id_admin_creador' => null,
                'id_trabajador_creador' => null
            ];

            // Asigna el ID del creador basado en el rol de la sesión
            if ($_SESSION['usuario_rol'] == 'Administrador') {
                $data['id_admin_creador'] = $_SESSION['usuario_id'];
            } else if ($_SESSION['usuario_rol'] == 'Trabajador') {
                $data['id_trabajador_creador'] = $_SESSION['usuario_id'];
            }

            // Llama al modelo para crear
            $success = $this->model->createSchedule($data);

            if ($success) {
                header("Location: " . BASE_URL . "index.php?accion=manage_schedules&status=created");
            } else {
                header("Location: " . BASE_URL . "index.php?accion=manage_schedules&error=create_failed");
            }
            exit;
        }
    }

    // Función para procesar la actualización de un horario
    /**
     * Actualiza un horario existente.
     * Recibe datos por POST.
     *
     * @return void Redirige con el estado de la actualización.
     */
    public function updateSchedule() {
        $this->checkAuth(['Administrador', 'Trabajador']);
        
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            
            $id = $_POST['edit_id_horario'];

            $data = [
                'fecha' => $_POST['edit_fecha'],
                'hora_inicio' => $_POST['edit_hora_inicio'],
                'hora_fin' => $_POST['edit_hora_fin'],
                'tipo_molida' => $_POST['edit_tipo_molida'],
                'id_producto' => $_POST['edit_id_producto'],
                'observaciones' => $_POST['edit_observaciones'] ?? null
            ];

            // Llama al modelo para actualizar
            $success = $this->model->updateSchedule($id, $data);

            if ($success) {
                header("Location: " . BASE_URL . "index.php?accion=manage_schedules&status=updated");
            } else {
                header("Location: " . BASE_URL . "index.php?accion=manage_schedules&error=update_failed");
            }
            exit;
        }
    }

    // Función para borrar un horario
    /**
     * Elimina un horario.
     *
     * @return void Redirige con el estado de la eliminación.
     */
    public function deleteSchedule() {
        $this->checkAuth(['Administrador', 'Trabajador']);
        
        if (isset($_GET['id'])) {
            $id = $_GET['id'];
            
            // modelo para borrar
            $success = $this->model->deleteSchedule($id);

            if ($success) {
                header("Location: " . BASE_URL . "index.php?accion=manage_schedules&status=deleted");
            } else {
                header("Location: ". BASE_URL . "index.php?accion=manage_schedules&error=delete_failed");
            }
            exit;
        }
    }
}
?>