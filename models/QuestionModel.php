<?php
// app/models/QuestionModel.php

class QuestionModel {

    private $db_connection;

    /**
     * Constructor del modelo.
     *
     * @param object $conexion Objeto de conexión a la base de datos.
     */
    public function __construct($conexion) {
        $this->db_connection = $conexion;
    }

    //FUNCIÓN PARA CREAR PREGUNTA
    /**
     * Crea una nueva pregunta de cliente.
     *
     * @param int $id_cliente ID del cliente.
     * @param string $pregunta_texto Texto de la pregunta.
     * @return bool True si tuvo éxito.
     */
    public function createQuestion($id_cliente, $pregunta_texto) {
        $sql = "INSERT INTO pregunta_cliente (id_cliente, pregunta_texto) 
                VALUES (?, ?)";
        $stmt = $this->db_connection->prepare($sql);
        $stmt->bind_param("is", $id_cliente, $pregunta_texto);
        return $stmt->execute();
    }

    //FUNCIÓN PARA OBTENER PREGUNTA POR ID DE CLIENTE
    /**
     * Obtiene las preguntas realizadas por un cliente específico.
     *
     * @param int $id_cliente ID del cliente.
     * @return array Lista de preguntas del cliente.
     */
    public function getQuestionsByClientId($id_cliente) {
        $sql = "SELECT * FROM pregunta_cliente 
                WHERE id_cliente = ? 
                ORDER BY fecha_pregunta DESC";
        $stmt = $this->db_connection->prepare($sql);
        $stmt->bind_param("i", $id_cliente);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }


    // FUNCION PARA OBTENER PREGUNTAS ADMIN
    /**
     * Obtiene todas las preguntas para administración.
     * Incluye nombres de cliente y respondedor.
     *
     * @return array Lista completa de preguntas.
     * @throws Exception Si hay error en la consulta.
     */
    public function getAllQuestionsAdmin() {
        $sql = "SELECT 
                    pq.id_pregunta, 
                    pq.pregunta_texto, 
                    pq.respuesta_texto,
                    pq.fecha_pregunta,
                    pq.fecha_respuesta,
                    pq.estado,
                    c.nombre AS cliente_nombre,
                    COALESCE(a.nombre, t.nombre) AS responder_name
                FROM 
                    pregunta_cliente pq
                JOIN 
                    cliente c ON pq.id_cliente = c.idCliente
                LEFT JOIN 
                    administrador a ON pq.id_admin_respuesta = a.idAdmin
                LEFT JOIN 
                    trabajador t ON pq.id_trabajador_respuesta = t.idTrabajador
                ORDER BY 
                    pq.estado ASC, pq.fecha_pregunta DESC";
        $result = $this->db_connection->query($sql);
        if ($result) {
            return $result->fetch_all(MYSQLI_ASSOC);
        } else {
            throw new Exception($this->db_connection->error);
        }
    }

    
    //FUNCIÓN QUE SE USA PARA RESPONDER PREGUNTAS, RESPONDER POR PRIMERA VEZ Y ACTUALIZAR RESPUESTA EXISTENTE
    /**
     * Responde o actualiza la respuesta a una pregunta.
     *
     * @param int $id_pregunta ID de la pregunta.
     * @param string $respuesta_texto Texto de la respuesta.
     * @param int $responder_id ID del usuario que responde.
     * @param string $responder_rol Rol del usuario que responde ('Administrador' o 'Trabajador').
     * @return bool True si tuvo éxito, false si el rol no es válido.
     */
    public function replyToQuestion($id_pregunta, $respuesta_texto, $responder_id, $responder_rol) {
        
        $admin_id = null;
        $worker_id = null;
        if ($responder_rol == 'Administrador') {
            $admin_id = $responder_id;
        } elseif ($responder_rol == 'Trabajador') {
            $worker_id = $responder_id;
        } else {
            return false; 
        }

        $sql = "UPDATE pregunta_cliente SET
                    respuesta_texto = ?, 
                    fecha_respuesta = NOW(), 
                    estado = 'respondida', 
                    id_admin_respuesta = ?, 
                    id_trabajador_respuesta = ?
                WHERE id_pregunta = ?";
        
        $stmt = $this->db_connection->prepare($sql);
        $stmt->bind_param("siii", 
            $respuesta_texto,
            $admin_id,
            $worker_id,
            $id_pregunta
        );
        return $stmt->execute();
    }

    // función de borrado del Admin/Trabajador
    /**
     * Elimina una pregunta (Admin/Trabajador).
     *
     * @param int $id_pregunta ID de la pregunta.
     * @return bool True si tuvo éxito.
     */
    public function deleteQuestion($id_pregunta) {
        $sql = "DELETE FROM pregunta_cliente WHERE id_pregunta = ?";
        $stmt = $this->db_connection->prepare($sql);
        $stmt->bind_param("i", $id_pregunta);
        return $stmt->execute();
    }

    
    //FUNCIÓN PARA BORRAR PREGUNTA si ID de pregunta y ID cliente coinciden por si el cliente la quiere borrar
    /**
     * Elimina una pregunta propia del cliente.
     *
     * @param int $id_pregunta ID de la pregunta.
     * @param int $id_cliente ID del cliente.
     * @return bool True si tuvo éxito.
     */
    public function deleteQuestionAsClient($id_pregunta, $id_cliente) {
        $sql = "DELETE FROM pregunta_cliente 
                WHERE id_pregunta = ? AND id_cliente = ?";
        
        $stmt = $this->db_connection->prepare($sql);
        $stmt->bind_param("ii", $id_pregunta, $id_cliente);
        
        return $stmt->execute();
    }

    
    //FUNCIÓN PARA OBTENER TODAS LAS PREGUNTA PÚBLICAS PENDIENTES Y RESPONDIDAS se une a cliente para obtener el nombre
    /**
     * Obtiene preguntas públicas para mostrar (pendientes y respondidas).
     *
     * @return array Lista de preguntas.
     * @throws Exception Si hay error en la consulta.
     */
    public function getPublicQuestionsAndAnswers() {
        $sql = "SELECT 
                    pq.pregunta_texto, 
                    pq.respuesta_texto,
                    pq.fecha_pregunta,
                    pq.fecha_respuesta,
                    pq.estado,
                    c.nombre AS cliente_nombre
                FROM 
                    pregunta_cliente pq
                JOIN 
                    cliente c ON pq.id_cliente = c.idCliente
                ORDER BY 
                    pq.estado ASC, pq.fecha_pregunta DESC";
        
        $result = $this->db_connection->query($sql);
        if ($result) {
            return $result->fetch_all(MYSQLI_ASSOC);
        } else {
            throw new Exception($this->db_connection->error);
        }
    }

    
    //FUNCION PARA OBTENER CONTEO DE PREGUNTAS PENDIENTES SOLAMENTE CUENTA
    /**
     * Cuenta el número de preguntas pendientes.
     *
     * @return int Número de preguntas pendientes.
     */
    public function getPendingQuestionsCount() {
        $sql = "SELECT COUNT(id_pregunta) AS total 
                FROM pregunta_cliente 
                WHERE estado = 'pendiente'";
                
        $result = $this->db_connection->query($sql);
        $row = $result->fetch_assoc();
        return $row['total'] ?? 0;
    }
    
}
?>