<?php

include_once "app/models/NewsModel.php";

class NewsController extends BaseController {

    private $model;

    //  recibe la conexión e instancia el modelo
    /**
     * Constructor del controlador.
     *
     * @param object $conexion Objeto de conexión a la base de datos.
     */
    public function __construct($conexion) {
        $this->model = new NewsModel($conexion);
    }

    //FUNCIÓN PARA CREAR NOTICIA AVISO
    /**
     * Crea un nuevo aviso o noticia.
     * Recibe datos por POST.
     *
     * @return void Redirige con el estado de la creación.
     */
    public function createNews() {

        $this->checkAuth(['Administrador']);
        
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            
            // Recolecta los datos del formulario
            $data = [
                'titulo' => $_POST['titulo'],
                'contenido' => $_POST['contenido'],
                'fecha_publicacion' => date('Y-m-d'),
                // El ID del autor se toma de la sesión
                'autor_id_admin' => $_SESSION['usuario_id'] 
            ];

            $success = $this->model->createNews($data);

            if ($success) {
                header("Location: " . BASE_URL . "index.php?accion=manage_news&status=created");
            } else {
                header("Location: ". BASE_URL . "index.php?accion=manage_news&error=create_failed");
            }
            exit;
        }
    }

    //FUNCIÓN PARA ACTUALIZAR NOTICIA
    /**
     * Actualiza un aviso existente.
     * Recibe datos por POST.
     *
     * @return void Redirige con el estado de la actualización.
     */
    public function updateNews() {
        $this->checkAuth(['Administrador']);
        
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            
            $id = $_POST['edit_id_aviso'];

            $data = [
                'titulo' => $_POST['edit_titulo'],
                'contenido' => $_POST['edit_contenido'],
            ];

            $success = $this->model->updateNews($id, $data);

            if ($success) {
                header("Location: " . BASE_URL . "index.php?accion=manage_news&status=updated");
            } else {
                header("Location: " . BASE_URL . "index.php?accion=manage_news&error=update_failed");
            }
            exit;
        }
    }

    //FUNCIÓN PARA ELIMINAR NOTICIA
    /**
     * Elimina un aviso.
     *
     * @return void Redirige con el estado de la eliminación.
     */
    public function deleteNews() {
        $this->checkAuth(['Administrador']);
        
        if (isset($_GET['id'])) {
            $id = $_GET['id'];
            
            $success = $this->model->deleteNews($id);

            if ($success) {
                header("Location: " . BASE_URL . "index.php?accion=manage_news&status=deleted");
            } else {
                header("Location: ". BASE_URL . "index.php?accion=manage_news&error=delete_failed");
            }
            exit;
        } else {
            header("Location: ". BASE_URL . "index.php?accion=manage_news");
            exit;
        }
    }
}
?>