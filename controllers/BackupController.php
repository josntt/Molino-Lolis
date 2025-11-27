<?php
// app/controllers/BackupController.php

include_once "app/models/BackupModel.php";

class BackupController extends BaseController {

    private $model;

    /**
     * Constructor del controlador.
     * Inicializa el modelo BackupModel con la configuración de la base de datos.
     *
     * @param object $conexion Conexión a la base de datos (no utilizada directamente aquí, se usa global).
     */
    public function __construct($conexion) {
        // config de la BD al modelo
        global $server, $user, $password, $db;
        $db_config = [
            'server' => $server,
            'user' => $user,
            'password' => $password,
            'db' => $db
        ];
        $this->model = new BackupModel($db_config);
    }

   
    // FUNCIÓN PROCESO DE CREAR NUEVO RESPALDO
    /**
     * Crea un nuevo respaldo de la base de datos.
     * Verifica permisos de administrador y redirige según el resultado.
     *
     * @return void Redirige a la página de gestión de respaldos.
     */
    public function createBackup() {
        $this->checkAuth(['Administrador']);
        
        // nombre del admin desde la sesión
        $admin_name = $_SESSION['usuario_nombre'] ?? 'Admin';
        
        $fileName = $this->model->generateBackup($admin_name);

        if ($fileName) {
            header("Location: " . BASE_URL . "index.php?accion=manage_backups&status=created&file=" . urlencode($fileName));
        } else {
            header("Location: " . BASE_URL . "index.php?accion=manage_backups&error=failed");
        }
        exit;
    }

    
    // FUNCIÓN PROCESO DESCARGA DE RESPALDO
    /**
     * Descarga un archivo de respaldo existente.
     * Verifica permisos y la existencia del archivo.
     *
     * @return void Inicia la descarga o redirige en caso de error.
     */
    public function downloadBackup() {
        $this->checkAuth(['Administrador']);
        
        $fileName = $_GET['file'] ?? '';

        if (empty($fileName) || !$this->model->downloadBackup($fileName)) {
            // Si el archivo no existe o no es seguro, falla
            header("Location: " . BASE_URL . "index.php?accion=manage_backups&error=file_not_found");
        }
        exit;
    }

   
    // FUNCIÓN ELIMINAR respaldo creado
    /**
     * Elimina un archivo de respaldo.
     * Verifica permisos y elimina el archivo físico.
     *
     * @return void Redirige a la página de gestión con el estado de la operación.
     */
    public function deleteBackup() {
        $this->checkAuth(['Administrador']);
        
        $fileName = $_GET['file'] ?? '';

        if (empty($fileName) || !$this->model->deleteBackup($fileName)) {
            header("Location: " . BASE_URL . "index.php?accion=manage_backups&error=delete_failed");
        } else {
            header("Location: " . BASE_URL . "index.php?accion=manage_backups&status=deleted");
        }
        exit;
    }

    
    // FUNCIÓN PARA RESTAURAR UN ARCHIVO EXISTENTE EN EL SISTEMA
    /**
     * Restaura la base de datos desde un archivo de respaldo existente en el servidor.
     *
     * @return void Redirige con el estado de la restauración.
     */
    public function restoreBackup() {
        $this->checkAuth(['Administrador']);
        
        $fileName = $_GET['file'] ?? '';

        if (empty($fileName)) {
            header("Location: " . BASE_URL . "index.php?accion=manage_backups&error=file_not_found");
            exit;
        }

        $success = $this->model->restoreBackupFromFile($fileName);

        if ($success) {
            header("Location: " . BASE_URL . "index.php?accion=manage_backups&status=restored");
        } else {
            header("Location: " . BASE_URL . "index.php?accion=manage_backups&error=restore_failed");
        }
        exit;
    }

    
    // FUNCION PARA PROCESAR SUBIR Y RESTAURAR UN ARCHIVO en .sql
    /**
     * Sube un archivo .sql y restaura la base de datos con él.
     *
     * @return void Redirige con el resultado de la operación.
     */
    public function uploadAndRestoreBackup() {
        $this->checkAuth(['Administrador']);

        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['backup_file'])) {
            
            $fileData = $_FILES['backup_file'];
            $result = $this->model->processUploadedBackup($fileData);

            if ($result === true) {
                // Éxito total
                header("Location: " . BASE_URL . "index.php?accion=manage_backups&status=restored");
            } else {
                // Redirige con el código de error específico
                header("Location: " . BASE_URL . "index.php?accion=manage_backups&error=" . $result);
            }
            exit;

        } else {
            // Si no se subió ningún archivo
            header("Location: " . BASE_URL . "index.php?accion=manage_backups&error=upload_failed");
            exit;
        }
    }
}
?>