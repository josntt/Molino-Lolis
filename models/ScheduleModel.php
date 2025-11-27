<?php
class ScheduleModel {

    private $db_connection;

    // recibe la conexión de la base de datos
    /**
     * Constructor del modelo.
     *
     * @param object $conexion Objeto de conexión a la base de datos.
     */
    public function __construct($conexion) {
        $this->db_connection = $conexion;
    }

    //FUNCIÓN PARA OBTENER HORARIOS PARA PANEL DE ADMIN, realiza un JOIN con productos y un
    // COALESCE en las tablas de admin/trabajador para obtener los nombres legibles
    /**
     * Obtiene todos los horarios para administración.
     * Incluye nombres de productos y creadores.
     *
     * @return array Lista completa de horarios.
     * @throws Exception Si hay error en la consulta.
     */
    public function getAllSchedulesAdmin() {
        $sql = "SELECT 
                    h.id_horario, 
                    h.fecha, 
                    h.hora_inicio, 
                    h.hora_fin, 
                    h.tipo_molida, 
                    h.observaciones,
                    h.id_producto,
                    p.nombre AS product_name, 
                    COALESCE(a.nombre, t.nombre) AS creator_name
                FROM 
                    horarios_molida h
                LEFT JOIN 
                    productos p ON h.id_producto = p.id_producto
                LEFT JOIN 
                    administrador a ON h.id_admin_creador = a.idAdmin
                LEFT JOIN 
                    trabajador t ON h.id_trabajador_creador = t.idTrabajador
                ORDER BY 
                    h.fecha DESC, h.hora_inicio DESC";
                
        $result = $this->db_connection->query($sql);
        if ($result) {
            return $result->fetch_all(MYSQLI_ASSOC);
        } else {
            // Lanza una excepción si la consulta falla
            throw new Exception($this->db_connection->error);
        }
    }

    // FUNCIÓN PARA OBTENER LOS HORARIOS PÚBLICOS MOSTRANDO SOLO LOS HORARIOS DESDE FECHA ACTUAL EN ADELANTE
    /**
     * Obtiene los horarios activos para el público (futuros).
     *
     * @return array Lista de horarios futuros.
     * @throws Exception Si hay error en la consulta.
     */
    public function getActiveSchedulesPublic() {
        $sql = "SELECT 
                    h.fecha, 
                    h.hora_inicio, 
                    h.hora_fin, 
                    h.tipo_molida, 
                    p.nombre AS product_name
                FROM 
                    horarios_molida h
                JOIN 
                    productos p ON h.id_producto = p.id_producto
                WHERE 
                    h.fecha >= CURDATE()
                ORDER BY 
                    h.fecha ASC, h.hora_inicio ASC";
        
        $result = $this->db_connection->query($sql);
        if ($result) {
            return $result->fetch_all(MYSQLI_ASSOC);
        } else {
            throw new Exception($this->db_connection->error);
        }
    }

    // FUNCIÓN QUE Obtiene un horario específico por su ID
    /**
     * Obtiene un horario por su ID.
     *
     * @param int $id ID del horario.
     * @return array|null Datos del horario o null si no existe.
     */
    public function getScheduleById($id) {
        $sql = "SELECT * FROM horarios_molida WHERE id_horario = ?";
        $stmt = $this->db_connection->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }


    //FUNCIÓN QUE CREA UN NUEVO REGISTRO DE HORARIO 
    // CON $data que es un array asociativo con todos los campos
    /**
     * Crea un nuevo horario.
     *
     * @param array $data Datos del horario.
     * @return bool True si tuvo éxito.
     */
    public function createSchedule($data) {
        $sql = "INSERT INTO horarios_molida 
                    (fecha, hora_inicio, hora_fin, tipo_molida, id_producto, observaciones, id_admin_creador, id_trabajador_creador) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        
        $stmt = $this->db_connection->prepare($sql);
        $stmt->bind_param("ssssisii", 
            $data['fecha'], 
            $data['hora_inicio'], 
            $data['hora_fin'], 
            $data['tipo_molida'], 
            $data['id_producto'], 
            $data['observaciones'],
            $data['id_admin_creador'],      
            $data['id_trabajador_creador']  
        );
        return $stmt->execute();
    }


    //FUNCIÓN PARA ACTUALIZAR REGISTRO DE HORARIO
    /**
     * Actualiza un horario existente.
     *
     * @param int $id ID del horario.
     * @param array $data Nuevos datos.
     * @return bool True si tuvo éxito.
     */
    public function updateSchedule($id, $data) {
        $sql = "UPDATE horarios_molida SET
                    fecha = ?, 
                    hora_inicio = ?, 
                    hora_fin = ?, 
                    tipo_molida = ?, 
                    id_producto = ?, 
                    observaciones = ?
                WHERE id_horario = ?";
        
        $stmt = $this->db_connection->prepare($sql);
        $stmt->bind_param("ssssisi", 
            $data['fecha'], 
            $data['hora_inicio'], 
            $data['hora_fin'], 
            $data['tipo_molida'], 
            $data['id_producto'], 
            $data['observaciones'],
            $id
        );
        return $stmt->execute();
    }

    // Elimina un horario por su ID
    /**
     * Elimina un horario.
     *
     * @param int $id ID del horario.
     * @return bool True si tuvo éxito.
     */
    public function deleteSchedule($id) {
        $sql = "DELETE FROM horarios_molida WHERE id_horario = ?";
        $stmt = $this->db_connection->prepare($sql);
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
}
?>