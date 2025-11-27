<?php
class ContactModel {

    private $db_connection;
    // Definimos el ID fijo de la fila de contacto
    private $contact_row_id = 1;

    /**
     * Constructor del modelo.
     *
     * @param object $conexion Objeto de conexión a la base de datos.
     */
    public function __construct($conexion) {
        $this->db_connection = $conexion;
    }

    //FUNCIÓN PARA OBTENER LA INFO DE CONTACTO BUSCANDO SIEMPRE LA FILA CON ID 1
    /**
     * Obtiene la información de contacto.
     *
     * @return array|null Datos de contacto o null si no existe.
     */
    public function getContactInfo() {
        $sql = "SELECT * FROM contacto WHERE id_contacto = ?";
        $stmt = $this->db_connection->prepare($sql);
        $stmt->bind_param("i", $this->contact_row_id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        // Devuelve la fila de datos o null si no existe
        return $result->fetch_assoc();
    }


    //FUNCIÓN PARA CREAR LA FILA DE INFO DE CONTACTO POR PRIMERA VEZ
    /**
     * Crea la información de contacto inicial.
     *
     * @param array $data Datos de contacto.
     * @return bool True si tuvo éxito.
     */
    public function createContactInfo($data) {
        // inserta explícitamente el id_contacto = 1
        $sql = "INSERT INTO contacto 
                    (id_contacto, telefono, direccion, correo_contacto, url_facebook, actualizado_por_admin) 
                VALUES (?, ?, ?, ?, ?, ?)";
        
        $stmt = $this->db_connection->prepare($sql);
        
        $facebook_url = !empty($data['url_facebook']) ? $data['url_facebook'] : null;

        $stmt->bind_param("issssi", 
            $this->contact_row_id, // Se fija el ID
            $data['telefono'],
            $data['direccion'],
            $data['correo_contacto'],
            $facebook_url,
            $data['admin_id']
        );
        
        return $stmt->execute();
    }

    // FUNCIÓN PARA ACTUALIZAR 
    /**
     * Actualiza la información de contacto.
     *
     * @param array $data Nuevos datos de contacto.
     * @return bool True si tuvo éxito.
     */
    public function updateContactInfo($data) {
        $sql = "UPDATE contacto SET 
                    telefono = ?, 
                    direccion = ?, 
                    correo_contacto = ?, 
                    url_facebook = ?, 
                    actualizado_por_admin = ?
                WHERE id_contacto = ?";
        
        $stmt = $this->db_connection->prepare($sql);
        
        $facebook_url = !empty($data['url_facebook']) ? $data['url_facebook'] : null;

        $stmt->bind_param("ssssii", 
            $data['telefono'],
            $data['direccion'],
            $data['correo_contacto'],
            $facebook_url,
            $data['admin_id'], // ID admin que hizo el cambio
            $this->contact_row_id
        );
        
        return $stmt->execute();
    }
}
?>