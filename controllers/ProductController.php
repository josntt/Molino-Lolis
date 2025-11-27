<?php
//  Incluimos el modelo que maneja los datos de productos
include_once "app/models/ProductModel.php";

// La clase ahora HEREDA de BaseController para function checkAuth()
// Solo Admin y Trabajador pueden gestionar productos
class ProductController extends BaseController {

    private $model;

    // El constructor recibe la conexión e instancia el modelo
    /**
     * Constructor del controlador.
     *
     * @param object $conexion Objeto de conexión a la base de datos.
     */
    public function __construct($conexion) {
        

        $this->model = new ProductModel($conexion);
    }

    //FUNCION PARA SUBIR IMAGENES Y QUE NOS DEVUELVA LA RUTA DE LA IMAGEN O NULO
    /**
     * Maneja la subida de imágenes para productos.
     * Verifica errores y tipo de archivo.
     *
     * @param string $file_input_name Nombre del campo input file en el formulario.
     * @return string|null Ruta del archivo subido o null si falló.
     */
    private function handleImageUpload($file_input_name) {
        // Si alguien no es Admin o Trabajador, el script se detiene 
        
        $this->checkAuth(['Administrador', 'Trabajador']);
        // Verifica si se subió un archivo y si no hay errores
        if (isset($_FILES[$file_input_name]) && $_FILES[$file_input_name]['error'] == UPLOAD_ERR_OK) {
            
            // Primero definimos la carpeta de destino
            $target_dir = "public/media/products/";
            // Creamos un nombre de archivo único para evitar sobreescrituras
            $file_name = uniqid() . '-' . basename($_FILES[$file_input_name]["name"]);
            $target_path = $target_dir . $file_name;

            //Verifica que sea una imagen real (un simple check)
            $check = getimagesize($_FILES[$file_input_name]["tmp_name"]);
            if($check === false) {
                return null; // nulo si no es una imagen
            }

            // mover el archivo a su destino
            if (move_uploaded_file($_FILES[$file_input_name]["tmp_name"], $target_path)) {
                return $target_path; // Devolvemos la ruta para guardarla en la BD
            }
        }
        // Si no se subió un archivo nuevo es null
        return null;
    }

    //FUNCION PARA CREAR NUEVO PRODUCTO

    /**
     * Crea un nuevo producto.
     * Recibe datos por POST e imagen.
     *
     * @return void Redirige con el estado de la creación.
     */
    public function createProduct() {
        
        $this->checkAuth(['Administrador', 'Trabajador']);
        //  Verificamos que sea una petición POST
        
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            
        // Manejamos la subida de la imagen primero
            $image_path = $this->handleImageUpload('create_imagen');
            
        // Recolecta los datos del formulario
            $data = [
                'nombre' => $_POST['create_nombre'],
                'descripcion' => $_POST['create_descripcion'],
                'imagen' => $image_path // Puede ser null si no se subió imagen
            ];

         //Llama al modelo para crear
            $success = $this->model->createProduct($data);

            if ($success) {
                header("Location: " . BASE_URL . "index.php?accion=manage_products&status=created");
            } else {
                header("Location: " . BASE_URL . "index.php?accion=manage_products&error=create_failed");
            }
            exit;
        }
    }

    //FUNCION PARA ACTUALIZAR EL PRODUCTO
    /**
     * Actualiza un producto existente.
     * Recibe datos por POST y maneja reemplazo de imagen.
     *
     * @return void Redirige con el estado de la actualización.
     */
    public function updateProduct() {
        
        $this->checkAuth(['Administrador', 'Trabajador']);
        
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            
            $id = $_POST['edit_id_producto'];

            // subida de una NUEVA imagen
            $new_image_path = $this->handleImageUpload('edit_imagen');
            $old_image_path = null; // Variable para guardar la ruta de la imagen antigua

            // Recolectamos los datos
            $data = [
                'nombre' => $_POST['edit_nombre'],
                'descripcion' => $_POST['edit_descripcion'],
                'estado' => $_POST['edit_estado']
            ];

            //Decidimos si actualizamos la imagen y solo actualizamos imagen si se subió una nueva
            if ($new_image_path !== null) {
                // Obtenemos los datos del producto ANTIGUO
                $old_product = $this->model->getProductById($id);
                $old_image_path = $old_product['imagen'] ?? null;
                
                $data['imagen'] = $new_image_path;
            }

            // Llama al modelo para actualizar
            $success = $this->model->updateProduct($id, $data);

            // Si BD se actualizó Y subimos una imagen nueva Y existía una antigua entonces
            if ($success && $new_image_path !== null && $old_image_path !== null) {
                // Borra la imagen antigua del servidor con unlink
                if (file_exists($old_image_path)) {
                    unlink($old_image_path);
                }
            }

            if ($success) {
                header("Location: " . BASE_URL . "index.php?accion=manage_products&status=updated");
            } else {
                header("Location: " . BASE_URL . "index.php?accion=manage_products&error=update_failed");
            }
            exit;
        }
    }

    
    // FUNCION PARA ACTIVAR O DESACTIVAR UN PRODUCTO ACTIVO/INACTIVO
    /**
     * Cambia el estado (activo/inactivo) de un producto.
     *
     * @return void Redirige con el estado de la operación.
     */
    public function toggleProductStatus() {
        
        $this->checkAuth(['Administrador', 'Trabajador']);
        // Obtenemos el ID desde la URL
        if (isset($_GET['id'])) {
            $id = $_GET['id'];
            
            //  Llamamos al modelo para que haga el cambio
            $success = $this->model->toggleStatus($id);

            //  Redirigimos de vuelta a la página de gestión
            if ($success) {
                // Opcional: un status diferente para saber que funcionó
                header("Location: " . BASE_URL . "index.php?accion=manage_products&status=toggled");
            } else {
                header("Location: ". BASE_URL . "index.php?accion=manage_products&error=toggle_failed");
            }
            exit;
        } else {
            // Si no se proporcionó un ID simplemente regresamos
            header("Location: ". BASE_URL . "index.php?accion=manage_products");
            exit;
        }
    }

    //FUNCION PARA BORRAR UN PRODUCTO
    /**
     * Elimina un producto y su imagen asociada.
     *
     * @return void Redirige con el estado de la eliminación.
     */
    public function deleteProduct() {
        // Si alguien no es Admin o Trabajador, el script se detiene 
        // antes de que una función (create, update, delete) pueda ser llamada
        $this->checkAuth(['Administrador', 'Trabajador']);
        
        // Obtenemos el ID desde la URL
        if (isset($_GET['id'])) {
            $id = $_GET['id'];
            
            //  Obtenemos los datos del producto ANTES de borrarlo de la BD
            $product = $this->model->getProductById($id);
            $image_path = $product['imagen'] ?? null;
            
        

            // Llamamos al modelo para borrar de la BD
            $success = $this->model->deleteProduct($id);

            // Si se borró de la BD Y tenía una imagen
            if ($success && $image_path !== null) {
                // Borra archivo físico
                 if (file_exists($image_path)) {
                    unlink($image_path);
                 }
            }

            if ($success) {
                header("Location: " . BASE_URL . "index.php?accion=manage_products&status=deleted");
            } else {
                header("Location: ". BASE_URL . "index.php?accion=manage_products&error=delete_failed");
            }
            exit;
        }
    }
}
?>
