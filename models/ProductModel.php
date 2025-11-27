<?php
class ProductModel {

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

    // FUNCION PARA OBTENER TODOS LOS PRODUCTOS ACTIVOS PARA EL FRONTEND
    /**
     * Obtiene los productos activos para el público.
     *
     * @return array Lista de productos activos.
     * @throws Exception Si hay error en la consulta.
     */
    public function getActiveProducts() {
        $sql = "SELECT id_producto, nombre, descripcion, imagen 
                FROM productos 
                WHERE estado = 'activo' 
                ORDER BY nombre ASC";
                
        $result = $this->conexion->query($sql);
        if ($result) {
            return $result->fetch_all(MYSQLI_ASSOC);
        } else {
            throw new Exception($this->conexion->error);
        }
    }

    // FUNCION PARA OBTENER TODOS LOS PRODUCTOS PARA ADMIN
    /**
     * Obtiene todos los productos para administración.
     *
     * @return array Lista completa de productos.
     * @throws Exception Si hay error en la consulta.
     */
    public function getAllProductsAdmin() {
        $sql = "SELECT id_producto, nombre, descripcion, imagen, estado, fecha_creacion
                FROM productos 
                ORDER BY nombre ASC";
                
        $result = $this->conexion->query($sql);
        if ($result) {
            return $result->fetch_all(MYSQLI_ASSOC);
        } else {
            throw new Exception($this->conexion->error);
        }
    }

    // FUNCION PARA OBTENER UN PRODUCTO POR SU ID
    /**
     * Obtiene un producto por su ID.
     *
     * @param int $id ID del producto.
     * @return array|null Datos del producto o null si no existe.
     */
    public function getProductById($id) {
        $sql = "SELECT * FROM productos WHERE id_producto = ?";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    // FUNCION PARA CREAR UN NUEVO PRODUCTO
    /**
     * Crea un nuevo producto.
     *
     * @param array $data Datos del producto.
     * @return bool True si tuvo éxito.
     */
    public function createProduct($data) {
        $sql = "INSERT INTO productos (nombre, descripcion, imagen) 
                VALUES (?, ?, ?)";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("sss", 
            $data['nombre'], 
            $data['descripcion'], 
            $data['imagen']
        );
        return $stmt->execute();
    }

    //  ACTUALIZA un producto existente
    /**
     * Actualiza un producto existente.
     *
     * @param int $id ID del producto.
     * @param array $data Nuevos datos.
     * @return bool True si tuvo éxito.
     */
    public function updateProduct($id, $data) {
        // Construimos la consulta dinámicamente
        $sql_set = "nombre = ?, descripcion = ?, estado = ?";
        $params = [
            $data['nombre'],
            $data['descripcion'],
            $data['estado']
        ];
        $types = "sss";

        //Solo añadimos la imagen al UPDATE si se proporcionó una nueva
        if (isset($data['imagen'])) {
            $sql_set .= ", imagen = ?";
            $params[] = $data['imagen'];
            $types .= "s";
        }

        // Añadimos el ID al final para el WHERE
        $params[] = $id;
        $types .= "i";

        $sql = "UPDATE productos SET $sql_set WHERE id_producto = ?";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param($types, ...$params);

        return $stmt->execute();
    }

    /**
     * Alterna el estado (activo/inactivo) de un producto.
     *
     * @param int $id ID del producto.
     * @return bool True si tuvo éxito.
     */
    public function toggleStatus($id) {
    // Esta consulta SQL usa IF() para alternar el estado en un solo paso.
    $sql = "UPDATE productos 
            SET estado = IF(estado = 'activo', 'inactivo', 'activo') 
            WHERE id_producto = ?";

    $stmt = $this->conexion->prepare($sql);
    $stmt->bind_param("i", $id);
    return $stmt->execute();
    }

    // Eliminar un producto por su id
    /**
     * Elimina un producto.
     *
     * @param int $id ID del producto.
     * @return bool True si tuvo éxito.
     */
    public function deleteProduct($id) {
        $sql = "DELETE FROM productos WHERE id_producto = ?";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
}
?>