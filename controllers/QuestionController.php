<?php

include_once "app/models/QuestionModel.php";

class QuestionController extends BaseController {

    private $model;

    /**
     * Constructor del controlador.
     *
     * @param object $conexion Objeto de conexión a la base de datos.
     */
    public function __construct($conexion) {
        $this->model = new QuestionModel($conexion);
    }

    //FUNCIÓN PARA SUBIR UNA PREGUNTA
    /**
     * Envía una nueva pregunta de un cliente.
     * Recibe datos por POST.
     *
     * @return void Redirige con el estado del envío.
     */
    public function submitQuestion() {
        $this->checkAuth(['Cliente']);
        
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $pregunta_texto = $_POST['pregunta_texto'] ?? '';
            $id_cliente = $_SESSION['usuario_id']; 
            
            // Determina a dónde redirigir (al perfil, o de vuelta a la página de FAQ)
            $redirect_url = "index.php?accion=profile"; // URL por defecto
            if (isset($_POST['source']) && $_POST['source'] == 'faq') {
                $redirect_url = "index.php?accion=dudas"; // Redirige de vuelta a la página de FAQ
            }

            if (empty($pregunta_texto)) {
                 header("Location: " . BASE_URL . $redirect_url . "&error=pregunta_vacia");
                 exit;
            }
            $success = $this->model->createQuestion($id_cliente, $pregunta_texto);

            if ($success) {
                header("Location: " . BASE_URL . $redirect_url . "&status=pregunta_enviada");
            } else {
                header("Location: " . BASE_URL . $redirect_url . "&error=pregunta_fallida");
            }
            exit;
        }
    }

    // FUNCIÓN PARA RESPONDER UNA PREGUNTA COMO ADMIN O TRABAJADOR
    /**
     * Responde a una pregunta de un cliente.
     * Recibe datos por POST.
     *
     * @return void Redirige con el estado de la respuesta.
     */
    public function answerQuestion() {
        $this->checkAuth(['Administrador', 'Trabajador']);
        
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id_pregunta = $_POST['id_pregunta'];
            $respuesta_texto = $_POST['respuesta_texto'] ?? '';
            $responder_id = $_SESSION['usuario_id'];
            $responder_rol = $_SESSION['usuario_rol'];

            if (empty($respuesta_texto)) {
                 header("Location: " . BASE_URL . "index.php?accion=manage_questions&error=respuesta_vacia");
                 exit;
            }
            
            $success = $this->model->replyToQuestion($id_pregunta, $respuesta_texto, $responder_id, $responder_rol);

            if ($success) {
                header("Location: " . BASE_URL . "index.php?accion=manage_questions&status=answered");
            } else {
                header("Location: " . BASE_URL . "index.php?accion=manage_questions&error=answer_failed");
            }
            exit;
        }
    }

    
    // FUNCIÓN PARA PROCESAR LA ACTUALIZACIÓN DE RESPUESTA , Casi lo mismo que answerQuestion pero redirige con
    // mensaje diferente
    /**
     * Actualiza la respuesta a una pregunta.
     * Recibe datos por POST.
     *
     * @return void Redirige con el estado de la actualización.
     */
    public function updateAnswer() {
        $this->checkAuth(['Administrador', 'Trabajador']);
        
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            
            //  El ID viene del modal de edición
            $id_pregunta = $_POST['edit_id_pregunta']; 
            $respuesta_texto = $_POST['edit_respuesta_texto'] ?? '';
            $responder_id = $_SESSION['usuario_id'];
            $responder_rol = $_SESSION['usuario_rol'];

            if (empty($respuesta_texto)) {
                 header("Location: " . BASE_URL . "index.php?accion=manage_questions&error=respuesta_vacia");
                 exit;
            }
            
            // Reutilizamos la función del modelo ya que hace lo que necesitamos
            $success = $this->model->replyToQuestion($id_pregunta, $respuesta_texto, $responder_id, $responder_rol);

            if ($success) {
                //  Redirige con un nuevo estado 'updated'
                header("Location: " . BASE_URL . "index.php?accion=manage_questions&status=updated");
            } else {
                header("Location: " . BASE_URL . "index.php?accion=manage_questions&error=update_failed");
            }
            exit;
        }
    }

    // función de borrado del admin/trabajador
    /**
     * Elimina una pregunta (Admin/Trabajador).
     *
     * @return void Redirige con el estado de la eliminación.
     */
    public function deleteQuestion() {
        $this->checkAuth(['Administrador', 'Trabajador']);
        
        if (isset($_GET['id'])) {
            $id = $_GET['id'];
            $success = $this->model->deleteQuestion($id); // Usa la función de borrado simple

            if ($success) {
                header("Location: " . BASE_URL . "index.php?accion=manage_questions&status=deleted");
            } else {
                header("Location: ". BASE_URL . "index.php?accion=manage_questions&error=delete_failed");
            }
            exit;
        }
    }

    
    //FUNCIÓN PARA QUE UN CLIENTE BORRE SU PROPIA PREGUNTA
    /**
     * Elimina una pregunta propia del cliente.
     *
     * @return void Redirige con el estado de la eliminación.
     */
    public function clientDeleteQuestion() {
        // Solo los clientes pueden usar esta función
        $this->checkAuth(['Cliente']);

        if (isset($_GET['id'])) {
            $id_pregunta = $_GET['id'];
            //  Se obtiene el ID del cliente desde la sesión por seguridad
            $id_cliente = $_SESSION['usuario_id']; 

            $success = $this->model->deleteQuestionAsClient($id_pregunta, $id_cliente);

            if ($success) {
                header("Location: " . BASE_URL . "index.php?accion=profile&status=pregunta_eliminada");
            } else {
                header("Location: ". BASE_URL . "index.php?accion=profile&error=pregunta_no_eliminada");
            }
            exit;
        } else {
             header("Location: ". BASE_URL . "index.php?accion=profile");
            exit;
        }
    }
}
?>