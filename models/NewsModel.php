<?php

class NewsModel {

    private $db_connection;

    //CONSTRUCTOR PARA bd
    /**
     * Constructor del modelo.
     *
     * @param object $conexion Objeto de conexión a la base de datos.
     */
    public function __construct($conexion) {
        $this->db_connection = $conexion;
    }

    
    // FUNCIÓN PARA OBTENER TODOS LOS AVISOS PARA VISTA PUBLICA MOSTRANDO LOS MAS RECIENTES PRIMERO
    /**
     * Obtiene los avisos públicos ordenados por fecha.
     *
     * @return array Lista de avisos.
     * @throws Exception Si hay error en la consulta.
     */
    public function getPublicNews() {
        $sql = "SELECT titulo, contenido, fecha_publicacion 
                FROM avisos 
                ORDER BY fecha_publicacion DESC";
                
        $result = $this->db_connection->query($sql);
        if ($result) {
            return $result->fetch_all(MYSQLI_ASSOC);
        } else {
            throw new Exception($this->db_connection->error);
        }
    }

    //FUNCIÓN PARA OBTENER TODOS LOS AVISOS PARA PANEL DE ADMIN
    //USO DE JOIN con tabla administrador para obtener el nombre del autor
    /**
     * Obtiene todos los avisos para administración.
     * Incluye el nombre del autor.
     *
     * @return array Lista completa de avisos.
     * @throws Exception Si hay error en la consulta.
     */
    public function getAllNewsAdmin() {
        $sql = "SELECT 
                    av.id_aviso, 
                    av.titulo, 
                    av.contenido, 
                    av.fecha_publicacion, 
                    av.autor_id_admin,
                    admin.nombre AS autor_nombre
                FROM 
                    avisos av
                LEFT JOIN 
                    administrador admin ON av.autor_id_admin = admin.idAdmin
                ORDER BY 
                    av.fecha_publicacion DESC";
                
        $result = $this->db_connection->query($sql);
        if ($result) {
            return $result->fetch_all(MYSQLI_ASSOC);
        } else {
            throw new Exception($this->db_connection->error);
        }
    }

    // FUNCIÓN PARA CREAR UN AVISO NUEVO
    /**
     * Crea un nuevo aviso.
     *
     * @param array $data Datos del aviso (titulo, contenido, fecha, autor).
     * @return bool True si tuvo éxito.
     */
    public function createNews($data) {
        $sql = "INSERT INTO avisos (titulo, contenido, fecha_publicacion, autor_id_admin) 
                VALUES (?, ?, ?, ?)";
        
        $stmt = $this->db_connection->prepare($sql);
        $stmt->bind_param("sssi", 
            $data['titulo'], 
            $data['contenido'], 
            $data['fecha_publicacion'],
            $data['autor_id_admin'] // ID del admin en sesión
        );
        return $stmt->execute();
    }

    
    //FUNCION PARA ACTUALIZAR NOTICIAS
    /**
     * Actualiza un aviso existente.
     *
     * @param int $id ID del aviso.
     * @param array $data Nuevos datos (titulo, contenido).
     * @return bool True si tuvo éxito.
     */
    public function updateNews($id, $data) {
        
        $sql = "UPDATE avisos SET
                    titulo = ?, 
                    contenido = ?
                WHERE id_aviso = ?";
        
        $stmt = $this->db_connection->prepare($sql);
        
        $stmt->bind_param("ssi", 
            $data['titulo'], 
            $data['contenido'],
            $id
        );
        // ---------------------------

        return $stmt->execute();
    }

    
    //FUNCIÓN QUE ELIMINA AVISO POR ID
    /**
     * Elimina un aviso.
     *
     * @param int $id ID del aviso.
     * @return bool True si tuvo éxito.
     */
    public function deleteNews($id) {
        $sql = "DELETE FROM avisos WHERE id_aviso = ?";
        $stmt = $this->db_connection->prepare($sql);
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
}
?>