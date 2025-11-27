<?php
// app/models/FaqModel.php

class FaqModel {

    private $db_connection;

    /**
     * Constructor del modelo.
     *
     * @param object $conexion Objeto de conexión a la base de datos.
     */
    public function __construct($conexion) {
        $this->db_connection = $conexion;
    }

    
    // FUNCIÓN QUE obtiene todas las preguntas visibles para vista pública y ordena alfabeticamente
    /**
     * Obtiene las preguntas frecuentes visibles para el público.
     *
     * @return array Lista de preguntas visibles.
     * @throws Exception Si hay error en la consulta.
     */
    public function getPublicFaqs() {
        // La DB usa TINYINT(1) para visible por eso se compara con 1
        $sql = "SELECT pregunta, respuesta 
                FROM faq 
                WHERE visible = 1
                ORDER BY pregunta ASC";
                
        $result = $this->db_connection->query($sql);
        if ($result) {
            return $result->fetch_all(MYSQLI_ASSOC);
        } else {
            throw new Exception($this->db_connection->error);
        }
    }

    
    // FUNCIÓN PARA OBTENER TODAS LAS PREGUNTAS VISIBLES O NO PARA EL PANEL DE ADMIN Y SE UNE 
    // CON administrador para obtener el nombre del autor
    /**
     * Obtiene todas las preguntas frecuentes (visibles y ocultas) para administración.
     * Incluye el nombre del administrador que la creó.
     *
     * @return array Lista completa de preguntas.
     * @throws Exception Si hay error en la consulta.
     */
    public function getAllFaqsAdmin() {
        $sql = "SELECT 
                    f.id_faq, 
                    f.pregunta, 
                    f.respuesta, 
                    f.visible, 
                    f.creado_por_admin,
                    a.nombre AS autor_nombre
                FROM 
                    faq f
                LEFT JOIN 
                    administrador a ON f.creado_por_admin = a.idAdmin
                ORDER BY 
                    f.pregunta ASC";
                
        $result = $this->db_connection->query($sql);
        if ($result) {
            return $result->fetch_all(MYSQLI_ASSOC);
        } else {
            throw new Exception($this->db_connection->error);
        }
    }

    
    // FUNCIÓN PARA CREAR UNA PREGUNTA FRECUENTE LA VISIBILIDAD POR DEFECTO ES 1(visible)
    /**
     * Crea una nueva pregunta frecuente.
     *
     * @param array $data Datos de la pregunta (pregunta, respuesta, creado_por_admin).
     * @return bool True si tuvo éxito.
     */
    public function createFaq($data) {
        $sql = "INSERT INTO faq (pregunta, respuesta, creado_por_admin) 
                VALUES (?, ?, ?)";
        
        $stmt = $this->db_connection->prepare($sql);
        $stmt->bind_param("ssi", 
            $data['pregunta'], 
            $data['respuesta'], 
            $data['creado_por_admin'] // ID del admin en sesión
        );
        return $stmt->execute();
    }

    
    //FUNCIÓN PARA ACTUALIZAR PREGUNTA Y RESPUESTA
    /**
     * Actualiza una pregunta frecuente existente.
     *
     * @param int $id ID de la pregunta.
     * @param array $data Nuevos datos (pregunta, respuesta).
     * @return bool True si tuvo éxito.
     */
    public function updateFaq($id, $data) {
        $sql = "UPDATE faq SET
                    pregunta = ?, 
                    respuesta = ?
                WHERE id_faq = ?";
        
        $stmt = $this->db_connection->prepare($sql);
        $stmt->bind_param("ssi", 
            $data['pregunta'], 
            $data['respuesta'],
            $id
        );
        return $stmt->execute();
    }

    
    // FUNCIÓN PARA CAMBIAR LA VISIBILIDAD DE UNA PREGUNTA DE 1 VISIBLE A 0 OCULTA
    /**
     * Alterna la visibilidad de una pregunta frecuente.
     *
     * @param int $id ID de la pregunta.
     * @return bool True si tuvo éxito.
     */
    public function toggleVisibility($id) {
        $sql = "UPDATE faq 
                SET visible = IF(visible = 1, 0, 1) 
                WHERE id_faq = ?";

        $stmt = $this->db_connection->prepare($sql);
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }

    // FUNCION ELIMINAR PREGUNTA FAQ
    /**
     * Elimina una pregunta frecuente.
     *
     * @param int $id ID de la pregunta.
     * @return bool True si tuvo éxito.
     */
    public function deleteFaq($id) {
        $sql = "DELETE FROM faq WHERE id_faq = ?";
        $stmt = $this->db_connection->prepare($sql);
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
}
?>