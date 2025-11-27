<?php
// app/models/BackupModel.php

class BackupModel {

    private $db_host;
    private $db_user;
    private $db_pass;
    private $db_name;
    private $backup_dir;

    // El constructor toma los datos de la conexión
    /**
     * Constructor del modelo.
     * Configura la conexión y el directorio de respaldos.
     *
     * @param array $conexion_config Configuración de la base de datos.
     */
    public function __construct($conexion_config) {
        $this->db_host = $conexion_config['server'];
        $this->db_user = $conexion_config['user'];
        $this->db_pass = $conexion_config['password'];
        $this->db_name = $conexion_config['db'];

        // Define un directorio seguro para guardar los backups
        // Usa DIRECTORY_SEPARATOR para compatibilidad Mac/Linux/Windows
        $this->backup_dir = PROJECT_ROOT . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'backups';
        
        // Crea el directorio si no existe
        if (!file_exists($this->backup_dir)) {
            mkdir($this->backup_dir, 0755, true);
        }
    }

    
    // Se añade $admin_name como argumento
    // función para generar un nuevo respaldo .sql
    /**
     * Genera un respaldo de la base de datos.
     *
     * @param string $admin_name Nombre del administrador que genera el respaldo.
     * @return string|false Nombre del archivo generado o false si falla.
     */
    public function generateBackup($admin_name) {
        
        // Limpia nombre del admin para usarse en el nombre del archivo
        $safe_admin_name = preg_replace('/[^a-zA-Z0-9_-]/', '', $admin_name);

        // Genera un nombre de archivo único
        // Se añade el nombre del admin al nombre del archivo
        $fileName = 'backup_' . $this->db_name . '_' . date('Y-m-d_H-i-s') . '_por_' . $safe_admin_name . '.sql';
        $filePath = $this->backup_dir . DIRECTORY_SEPARATOR . $fileName;

        //  Ruta completa a mysqldump.exe (desde config.php)
        $mysqldump = MYSQL_BIN_PATH . 'mysqldump.exe';

        //Construye el comando de consola
        // Se añade --skip-lock-tables para evitar problemas de permisos
        $command = sprintf('"%s" -h %s -u %s %s %s > "%s"',
            $mysqldump,
            escapeshellarg($this->db_host),
            escapeshellarg($this->db_user),
            // Solo añade la contraseña si existe (evita errores)
            !empty($this->db_pass) ? '-p' . escapeshellarg($this->db_pass) : '',
            escapeshellarg($this->db_name),
            $filePath
        );

        // Ejecuta el comando en el servidor
        @exec($command, $output, $return_var);

        if ($return_var === 0 && file_exists($filePath)) {
            // retorna exito
            return $fileName;
        } else {
            // Fallo, elimina el archivo vacío si se creó
            if (file_exists($filePath)) {
                unlink($filePath);
            }
            return false;
        }
    }

    //Funcion listar todos los archivos de respaldo que existen
    /**
     * Lista todos los archivos de respaldo disponibles.
     *
     * @return array Lista de respaldos con metadatos.
     */
    public function listBackups() {
        $files = @scandir($this->backup_dir, SCANDIR_SORT_DESCENDING);
        $backups = [];

        if ($files) {
            foreach ($files as $file) {
                //  Filtra solo los archivos .sql
                if (pathinfo($file, PATHINFO_EXTENSION) == 'sql') {
                    $filePath = $this->backup_dir . DIRECTORY_SEPARATOR . $file;
                    
                    // Intenta extraer el nombre del creador del archivo
                    $creator_name = 'Desconocido';
                    // Divide el nombre por 'por_'
                    $parts = explode('_por_', $file);
                    if (count($parts) > 1) {
                        // Toma la última parte y quita '.sql'
                        $creator_name = str_replace('.sql', '', $parts[count($parts) - 1]);
                    }

                    $backups[] = [
                        'name' => $file,
                        'size' => filesize($filePath),
                        'date' => filemtime($filePath),
                        'creator_name' => $creator_name // Añade el nombre al array
                    ];
                }
            }
        }
        return $backups;
    }
   
    // Función para obtener la ruta segura de un archivo y verificar que exista previene ataques de Directory Transversal
    /**
     * Obtiene la ruta segura de un archivo de respaldo.
     * Previene ataques de Directory Traversal.
     *
     * @param string $fileName Nombre del archivo.
     * @return string|null Ruta absoluta o null si no es válida.
     */
    private function getSafeFilePath($fileName) {
        // basename() elimina cualquier ".." o "/" del nombre
        $safeName = basename($fileName);
        $filePath = $this->backup_dir . DIRECTORY_SEPARATOR . $safeName;

        if ($safeName == $fileName && file_exists($filePath)) {
            return $filePath;
        }
        return null;
    }

   
    // FUNCIÓN PROPORCIONA ARCHIVO PARA DESCARGAR
    /**
     * Descarga un archivo de respaldo.
     *
     * @param string $fileName Nombre del archivo.
     * @return void|false Termina el script tras la descarga o retorna false.
     */
    public function downloadBackup($fileName) {
        $filePath = $this->getSafeFilePath($fileName);
        
        if ($filePath) {
            //  Establece cabeceras HTTP para forzar la descarga
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="' . basename($filePath) . '"');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($filePath));
            //  Lee el archivo y lo envía al navegador
            readfile($filePath);
            exit;
        }
        return false;
    }

   // función eliminar una copia de seguridad
    /**
     * Elimina un archivo de respaldo.
     *
     * @param string $fileName Nombre del archivo.
     * @return bool True si se eliminó, false si falló.
     */
    public function deleteBackup($fileName) {
        $filePath = $this->getSafeFilePath($fileName);
        
        if ($filePath) {
            return unlink($filePath);
        }
        return false;
    }

    /**
     * Lógica central de restauración.
     * Ejecuta el comando 'mysql.exe' para importar un archivo .sql
     * @param string $filePath Ruta ABSOLUTA al archivo .sql (ya sea uno guardado o uno temporal)
     * @return bool True si la restauración tuvo éxito, False si falló.
     */
    private function restoreDatabase($filePath) {
        // Ruta al ejecutable 'mysql.exe'
        $mysql = MYSQL_BIN_PATH . 'mysql.exe';

        // Construye el comando de restauración
        $command = sprintf('"%s" -h %s -u %s %s %s < "%s"',
            $mysql,
            escapeshellarg($this->db_host),
            escapeshellarg($this->db_user),
            !empty($this->db_pass) ? '-p' . escapeshellarg($this->db_pass) : '',
            escapeshellarg($this->db_name),
            $filePath
        );

        @exec($command, $output, $return_var);

        // Retorna true solo si el comando se ejecutó sin errores (código 0)
        return $return_var === 0;
    }

    
    // Funcion restaura desde un archivo existente en la lista 
    // verificando la seguirdad del archivo antes de restaurar
    /**
     * Restaura la base de datos desde un archivo existente.
     *
     * @param string $fileName Nombre del archivo.
     * @return bool True si tuvo éxito, false si falló.
     */
    public function restoreBackupFromFile($fileName) {
        $filePath = $this->getSafeFilePath($fileName);
        
        if ($filePath) {
            return $this->restoreDatabase($filePath);
        }
        return false;
    }

    
    // funcion para procesar un archivo sql subido y restaurar 
    // devuelve codigos de estado(string) o true si tiene exito
    /**
     * Procesa un archivo SQL subido y restaura la base de datos.
     *
     * @param array $fileData Datos del archivo subido ($_FILES).
     * @return bool|string True si tuvo éxito, string de error si falló.
     */
    public function processUploadedBackup($fileData) {
        // Validaciones de seguridad
        if ($fileData['error'] !== UPLOAD_ERR_OK) {
            return 'upload_error';
        }
        
        $fileName = $fileData['name'];
        $fileTmpPath = $fileData['tmp_name'];

        // Valida que sea un archivo .sql
        if (pathinfo($fileName, PATHINFO_EXTENSION) != 'sql') {
            return 'invalid_type';
        }

        // Intenta restaurar desde la ruta temporal del archivo subido
        $success = $this->restoreDatabase($fileTmpPath);

        // Borra el archivo temporal después de usarlo
        @unlink($fileTmpPath);

        return $success;
    }
}
?>