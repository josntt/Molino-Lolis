<?php
// app/controllers/FaqController.php

include_once "app/models/FaqModel.php";

class FaqController extends BaseController {

    private $model;

    // El constructor recibe la conexión e instancia el modelo
    /**
     * Constructor del controlador.
     *
     * @param object $conexion Objeto de conexión a la base de datos.
     */
    public function __construct($conexion) {
        $this->model = new FaqModel($conexion);
    }

    
    //FUNCIÓN PARA CREAR NUEVA PREGUNTA FAQ SOLO ADMINS
    /**
     * Crea una nueva pregunta frecuente.
     * Recibe datos por POST.
     *
     * @return void Redirige con el estado de la creación.
     */
    public function createFaq() {
        $this->checkAuth(['Administrador']);
        
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            
            $data = [
                'pregunta' => $_POST['pregunta'],
                'respuesta' => $_POST['respuesta'],
                'creado_por_admin' => $_SESSION['usuario_id'] 
            ];

            $success = $this->model->createFaq($data);

            if ($success) {
                header("Location: " . BASE_URL . "index.php?accion=manage_faq&status=created");
            } else {
                header("Location: ". BASE_URL . "index.php?accion=manage_faq&error=create_failed");
            }
            exit;
        }
    }

    
    // FUNCIÓN PARA ACTUALIZAR UNA PREGUNTA SOLO ADMIN
    /**
     * Actualiza una pregunta frecuente existente.
     * Recibe datos por POST.
     *
     * @return void Redirige con el estado de la actualización.
     */
    public function updateFaq() {
        $this->checkAuth(['Administrador']);
        
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            
            $id = $_POST['edit_id_faq'];

            $data = [
                'pregunta' => $_POST['edit_pregunta'],
                'respuesta' => $_POST['edit_respuesta']
            ];

            $success = $this->model->updateFaq($id, $data);

            if ($success) {
                header("Location: " . BASE_URL . "index.php?accion=manage_faq&status=updated");
            } else {
                header("Location: " . BASE_URL . "index.php?accion=manage_faq&error=update_failed");
            }
            exit;
        }
    }

    
    //FUNCIÓN PARA HACER VISIBLE UNA PREGUNTA O OCULTARLA DE FAQ
    /**
     * Cambia la visibilidad de una pregunta frecuente.
     *
     * @return void Redirige con el estado de la operación.
     */
    public function toggleFaqVisibility() {
        $this->checkAuth(['Administrador']);
        
        if (isset($_GET['id'])) {
            $id = $_GET['id'];
            $success = $this->model->toggleVisibility($id);

            if ($success) {
                header("Location: " . BASE_URL . "index.php?accion=manage_faq&status=toggled");
            } else {
                header("Location: ". BASE_URL . "index.php?accion=manage_faq&error=toggle_failed");
            }
            exit;
        } else {
            header("Location: ". BASE_URL . "index.php?accion=manage_faq");
            exit;
        }
    }


    
    // FUNCIÓN PARA ELIMINAR UNA PREGUNTA DE FAQ SOLO ADMIN
    /**
     * Elimina una pregunta frecuente.
     *
     * @return void Redirige con el estado de la eliminación.
     */
    public function deleteFaq() {
        $this->checkAuth(['Administrador']);
        
        if (isset($_GET['id'])) {
            $id = $_GET['id'];
            
            $success = $this->model->deleteFaq($id);

            if ($success) {
                header("Location: " . BASE_URL . "index.php?accion=manage_faq&status=deleted");
            } else {
                header("Location: ". BASE_URL . "index.php?accion=manage_faq&error=delete_failed");
            }
            exit;
        } else {
            header("Location: ". BASE_URL . "index.php?accion=manage_faq");
            exit;
        }
    }
}
?>