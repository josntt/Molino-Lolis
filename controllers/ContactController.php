<?php

include_once "app/models/ContactModel.php";

class ContactController extends BaseController {

    private $model;

    /**
     * Constructor del controlador.
     *
     * @param object $conexion Objeto de conexión a la base de datos.
     */
    public function __construct($conexion) {
        $this->model = new ContactModel($conexion);
    }

    
    // FUNCION PARA ACTUALIZAR LA INFO DE CONTACTO
    /**
     * Actualiza o crea la información de contacto.
     * Recibe datos por POST y actualiza la base de datos.
     *
     * @return void Redirige con el estado de la actualización.
     */
    public function updateContact() {

        $this->checkAuth(['Administrador']);
        
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            
            //  Recolecta los datos del formulario
            $data = [
                'telefono' => $_POST['telefono'],
                'direccion' => $_POST['direccion'],
                'correo_contacto' => $_POST['correo_contacto'],
                'url_facebook' => $_POST['url_facebook'] ?? null,
                'admin_id' => $_SESSION['usuario_id'] // Se guarda el ID del admin
            ];

            // LÓGICA "UPSERT" 
            // Primero verificamos si la fila de contacto ya existe
            $existingContact = $this->model->getContactInfo();
            $success = false;

            if ($existingContact) {
                //  Si existe la actualizamos
                $success = $this->model->updateContactInfo($data);
            } else {
                // Si no existe la creamos (primera vez)
                $success = $this->model->createContactInfo($data);
            }

            if ($success) {
                // Redirige con mensaje de éxito
                header("Location: " . BASE_URL . "index.php?accion=manage_contact&status=updated");
            } else {
                header("Location: " . BASE_URL . "index.php?accion=manage_contact&error=update_failed");
            }
            exit;
        }
    }
}
?>