<?php

class UserModel {
    private $conexion;

    // Constructor para inicializar la BD
    /**
     * Constructor del modelo.
     *
     * @param object $conexion Objeto de conexión a la base de datos.
     */
    public function __construct($conexion) {
        $this->conexion = $conexion;
    }

    //Buscar un usuario por correo en las 3 tablas y devuelve los datos del usuario y su rol
    /**
     * Busca un usuario por correo electrónico en todas las tablas de usuarios.
     *
     * @param string $correo Correo electrónico.
     * @return array|null Datos del usuario y su rol, o null si no se encuentra.
     */
    public function findUserByEmail($correo) {
        // Consulta unificada UNION se utiliza para buscar en las 3 tablas de roles
        // se estandariza el nombre de las columnas (id, nombre, correo, contrasena, rol)
        
        $sql = "
            (SELECT idCliente AS id, nombre, correo, contrasena, 'Cliente' AS rol 
             FROM cliente WHERE correo = ?)
            UNION
            (SELECT idTrabajador AS id, nombre, correo, contrasena, 'Trabajador' AS rol 
             FROM trabajador WHERE correo = ?)
            UNION
            (SELECT idAdmin AS id, nombre, correo, contrasena, 'Administrador' AS rol 
             FROM administrador WHERE correo = ?)
        ";
        
        // Preparar la consulta
        $statement = $this->conexion->prepare($sql);
        
        $statement->bind_param("sss", $correo, $correo, $correo);
        $statement->execute();
        
        $resultado = $statement->get_result();

        // Devolver la fila del usuario o null si no se encontró
        return $resultado->fetch_assoc();
    }

    
     // FUNCION QUE Registra un nuevo usuario en la tabla cliente
     /**
      * Registra un nuevo cliente.
      *
      * @param string $nombre Nombre.
      * @param string $apellidos Apellidos.
      * @param string $genero Género.
      * @param string $correo Correo electrónico.
      * @param string $pass_hash Hash de la contraseña.
      * @param string $telefono Teléfono.
      * @return bool True si tuvo éxito.
      */
    public function registerClient($nombre, $apellidos, $genero, $correo, $pass_hash, $telefono) {
        
        $sql = "INSERT INTO cliente (nombre, apellidos, genero, correo, contrasena, telefono) 
                VALUES (?, ?, ?, ?, ?, ?)";
        
        $statement = $this->conexion->prepare($sql);
        
        $statement->bind_param("ssssss", $nombre, $apellidos, $genero, $correo, $pass_hash, $telefono);

        // true si la ejecución fue exitosa y false si falló
        return $statement->execute();
    }

    // FUNCION QUE Registra un nuevo usuario en la tabla trabajador
    /**
     * Registra un nuevo trabajador.
     *
     * @param string $nombre Nombre.
     * @param string $apellidos Apellidos.
     * @param string $genero Género.
     * @param string $correo Correo electrónico.
     * @param string $pass_hash Hash de la contraseña.
     * @param string $telefono Teléfono.
     * @param string $puesto Puesto de trabajo.
     * @return bool True si tuvo éxito.
     */
    public function createTrabajador($nombre, $apellidos, $genero, $correo, $pass_hash, $telefono, $puesto) {
        
        $sql = "INSERT INTO trabajador (nombre, apellidos, genero, correo, contrasena, telefono, puesto) 
                VALUES (?, ?, ?, ?, ?, ?, ?)";
        
        $statement = $this->conexion->prepare($sql);
        
        $statement->bind_param("sssssss", $nombre, $apellidos, $genero, $correo, $pass_hash, $telefono, $puesto);

        // true si la ejecución fue exitosa y false si falló
        return $statement->execute();
    }


    
    // FUNCION PARA OBTENER LOS DATOS DE UN USUARIO POR ID Y TIPO(TABLA)
    /**
     * Obtiene los datos completos de un usuario específico.
     *
     * @param int $id ID del usuario.
     * @param string $type Tipo de usuario ('cliente', 'trabajador', 'administrador').
     * @return array|null Datos del usuario o null si no existe.
     */
    public function getUserData($id, $type) {
        //Determinamos la tabla y la llave primaria correcta
        $table_name = '';
        $pk_field = '';

        switch ($type) {
            case 'cliente':
                $table_name = 'cliente';
                $pk_field = 'idCliente';
                break;
            case 'trabajador':
                $table_name = 'trabajador';
                $pk_field = 'idTrabajador';
                break;
            case 'administrador':
                $table_name = 'administrador';
                $pk_field = 'idAdmin';
                break;
            default:
                return null; // Tipo de usuario no válido
        }

        $sql = "SELECT * FROM $table_name WHERE $pk_field = ?";
        $statement = $this->conexion->prepare($sql);
        $statement->bind_param("i", $id);
        $statement->execute();
        $resultado = $statement->get_result();
        
        return $resultado->fetch_assoc();
    }

    
    // FUNCION QUE OBTIENE TODOS LOS USUARIOS DE UN TIPO( TABLA )
    // FUNCION DE AYUDA PARA manage_users.php
    /**
     * Obtiene todos los usuarios de un tipo específico.
     *
     * @param string $type Tipo de usuario ('cliente', 'trabajador').
     * @return array Lista de usuarios.
     * @throws Exception Si hay error en la consulta.
     */
    public function getUsers($type) {
        $table_name = '';
        $order_by = '';

         // Validamos el nombre de la tabla para evitar inyección SQL
        if ($type == 'cliente') {
            $table_name = 'cliente';
            $order_by = 'fecha_registro DESC';
        } elseif ($type == 'trabajador') {
            $table_name = 'trabajador';
            $order_by = 'nombre ASC';
        } else {
            return []; // Tipo no válido
        }

        $sql = "SELECT * FROM $table_name ORDER BY $order_by";
        $result = $this->conexion->query($sql);
        
        if ($result) {
            return $result->fetch_all(MYSQLI_ASSOC);
        } else {
             // Lanzamos una excepción si la consulta falla
            throw new Exception($this->conexion->error);
        }
    }

    
    // FUNCION QUE ACTUALIZA UN USUARIO POR ID, TIPO Y DATOS
    /**
     * Actualiza los datos de un usuario.
     *
     * @param int $id ID del usuario.
     * @param string $type Tipo de usuario.
     * @param array $data Nuevos datos.
     * @return bool True si tuvo éxito, false si falla.
     */
    public function updateUser($id, $type, $data) {
        $table_name = '';
        $pk_field = '';

        // Campos base comunes a todas las tablas
        $sql_set = "nombre = ?, apellidos = ?, correo = ?, telefono = ?";
        $params = [
            $data['nombre'],
            $data['apellidos'],
            $data['correo'],
            $data['telefono']
        ];
        $types = "ssss"; 

        // Determinamos tabla, PK y añadimos campos específicos
        switch ($type) {
            case 'cliente':
                $table_name = 'cliente';
                $pk_field = 'idCliente';
                $sql_set .= ", genero = ?";
                $params[] = $data['genero'];
                $types .= "s";
                break;
            case 'trabajador':
                $table_name = 'trabajador';
                $pk_field = 'idTrabajador';
                $sql_set .= ", genero = ?, puesto = ?";
                $params[] = $data['genero'];
                $params[] = $data['puesto'];
                $types .= "ss";
                break;
            case 'administrador':
                $table_name = 'administrador';
                $pk_field = 'idAdmin';
                // No hay campos extra
                break;
            default:
                return false;
        }

        // Añadimos el ID al final de los parámetros para el WHERE
        $params[] = $id;
        $types .= "i"; // El ID es integer

        $sql = "UPDATE $table_name SET $sql_set WHERE $pk_field = ?";
        
        $statement = $this->conexion->prepare($sql);
        // Usamos '...' (splat operator) para pasar el array de parámetros
        $statement->bind_param($types, ...$params); 
        
        return $statement->execute();
    }

    // FUNCION QUE ELIMINA POR ID Y TIPO
    /**
     * Elimina un usuario.
     *
     * @param int $id ID del usuario.
     * @param string $type Tipo de usuario.
     * @return bool True si tuvo éxito, false si falla o no permitido.
     */
    public function deleteUser($id, $type) {
        $table_name = '';
        $pk_field = '';

        switch ($type) {
            case 'cliente':
                $table_name = 'cliente';
                $pk_field = 'idCliente';
                break;
            case 'trabajador':
                $table_name = 'trabajador';
                $pk_field = 'idTrabajador';
                break;
            // no borrar admin está en el controlador
            case 'administrador': 
                return false; // Doble seguridad
            default:
                return false;
        }

        $sql = "DELETE FROM $table_name WHERE $pk_field = ?";
        $statement = $this->conexion->prepare($sql);
        $statement->bind_param("i", $id);
        
        return $statement->execute();
    }

    
    //FUNCION DE CONTEO DE CLIENTES REGISTRADOS EN ULTIMOS 30 DIAS
    /**
     * Cuenta los nuevos clientes registrados en los últimos 30 días.
     *
     * @return int Número de nuevos clientes.
     */
    public function getNewClientsCount() {
        $sql = "SELECT COUNT(idCliente) AS total 
                FROM cliente 
                WHERE fecha_registro >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)";
                
        $result = $this->conexion->query($sql);
        $row = $result->fetch_assoc();
        return $row['total'] ?? 0;
    }
}
?>