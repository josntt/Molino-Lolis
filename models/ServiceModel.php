<?php
// app/models/ServiceModel.php

class ServiceModel {

    private $db_connection;

    // COMENTARIO NUEVO: El constructor recibe la conexión de la base de datos
    /**
     * Constructor del modelo.
     *
     * @param object $conexion Objeto de conexión a la base de datos.
     */
    public function __construct($conexion) {
        $this->db_connection = $conexion;
    }

    //FUNCIÓN PARA OBTENER SERVICIOS PARA PANEL ADMINISTRATIVO ORDENANDO POR TIPO Y LUEGO NOMBRE
    /**
     * Obtiene todos los servicios para administración.
     *
     * @return array Lista completa de servicios.
     * @throws Exception Si hay error en la consulta.
     */
    public function getAllServicesAdmin() {
        $sql = "SELECT * FROM servicios_ofrecidos 
                ORDER BY tipo, nombre_servicio ASC";
                
        $result = $this->db_connection->query($sql);
        if ($result) {
            return $result->fetch_all(MYSQLI_ASSOC);
        } else {
            // una excepción si la consulta falla
            throw new Exception($this->db_connection->error);
        }
    }

    
    //FUNCION PARA OBTENER TODOS LOS SERVICIOS PARA LA VISTA PUBLICA
    //BASICAMENTE LO MISMO QUE EL DE ADMIN PERO PUBLICO
    /**
     * Obtiene los servicios activos para el público.
     *
     * @return array Lista de servicios activos.
     * @throws Exception Si hay error en la consulta.
     */
    public function getActiveServicesPublic() {
        // Esta consulta es idéntica a la del admin por ahora,
        // ya que la tabla servicios_ofrecidos no tiene una columna estado
        $sql = "SELECT * FROM servicios_ofrecidos 
                ORDER BY tipo, nombre_servicio ASC";
                
        $result = $this->db_connection->query($sql);
        if ($result) {
            return $result->fetch_all(MYSQLI_ASSOC);
        } else {
            throw new Exception($this->db_connection->error);
        }
    }

    
    //FUNCIÓN PARA CREAR UN SERVICIO $data es un array asociativo con todos los campos del formulario
    /**
     * Crea un nuevo servicio.
     *
     * @param array $data Datos del servicio.
     * @return bool True si tuvo éxito.
     */
    public function createService($data) {
        // Se usan '?' para las consultas preparadas
        $sql = "INSERT INTO servicios_ofrecidos 
                    (tipo, nombre_servicio, descripcion, horario_inicio, horario_fin, dias_disponibles) 
                VALUES (?, ?, ?, ?, ?, ?)";
        
        $stmt = $this->db_connection->prepare($sql);
        
        // Se asignan null si los campos de tiempo/días vienen vacíos
        $horario_inicio = !empty($data['horario_inicio']) ? $data['horario_inicio'] : null;
        $horario_fin = !empty($data['horario_fin']) ? $data['horario_fin'] : null;
        $dias_disponibles = !empty($data['dias_disponibles']) ? $data['dias_disponibles'] : null;

        $stmt->bind_param("ssssss", 
            $data['tipo'], 
            $data['nombre_servicio'], 
            $data['descripcion'],
            $horario_inicio,
            $horario_fin,
            $dias_disponibles
        );
        return $stmt->execute();
    }

    
    //FUNCIÓN PARA ACTUALIZAR UN SERVICIO YA EXISTENTE POR SU ID
    /**
     * Actualiza un servicio existente.
     *
     * @param int $id ID del servicio.
     * @param array $data Nuevos datos.
     * @return bool True si tuvo éxito.
     */
    public function updateService($id, $data) {
        $sql = "UPDATE servicios_ofrecidos SET
                    tipo = ?, 
                    nombre_servicio = ?, 
                    descripcion = ?, 
                    horario_inicio = ?, 
                    horario_fin = ?, 
                    dias_disponibles = ?
                WHERE id_servicio = ?";
        
        $stmt = $this->db_connection->prepare($sql);

        // Asignación de valores (similar a create)
        $horario_inicio = !empty($data['horario_inicio']) ? $data['horario_inicio'] : null;
        $horario_fin = !empty($data['horario_fin']) ? $data['horario_fin'] : null;
        $dias_disponibles = !empty($data['dias_disponibles']) ? $data['dias_disponibles'] : null;

        $stmt->bind_param("ssssssi", 
            $data['tipo'], 
            $data['nombre_servicio'], 
            $data['descripcion'],
            $horario_inicio,
            $horario_fin,
            $dias_disponibles,
            $id
        );
        return $stmt->execute();
    }

    //FUNCION PARA ELIMINAR UN SERVICIO POR SU ID
    /**
     * Elimina un servicio.
     *
     * @param int $id ID del servicio.
     * @return bool True si tuvo éxito.
     */
    public function deleteService($id) {
        $sql = "DELETE FROM servicios_ofrecidos WHERE id_servicio = ?";
        $stmt = $this->db_connection->prepare($sql);
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
}
?>