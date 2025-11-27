<?php
// app/models/ReportModel.php

class ReportModel {

    private $db_connection;

    /**
     * Constructor del modelo.
     *
     * @param object $conexion Objeto de conexión a la base de datos.
     */
    public function __construct($conexion) {
        $this->db_connection = $conexion;
    }

    
    // FUNCIÓN PARA OBTENER CONTEO DE HORARIOS CREADOS EN EL MES ACTUAL Basado en fecha de creación del horario
    /**
     * Obtiene el número de horarios creados en el mes actual.
     *
     * @return int Total de horarios.
     */
    public function getCurrentMonthScheduleCount() {
        // YEAR(CURDATE()) y MONTH(CURDATE()) obtienen el año y mes actual
        $sql = "SELECT COUNT(id_horario) AS total 
                FROM horarios_molida 
                WHERE YEAR(fecha) = YEAR(CURDATE()) AND MONTH(fecha) = MONTH(CURDATE())";
                
        $result = $this->db_connection->query($sql);
        $row = $result->fetch_assoc();
        return $row['total'] ?? 0;
    }

    
    // FUNCIÓN PARA OBTENER LISTA DE PRODUCTOS MÁS PROGRAMADOS PARA MES ESPECIFICO Usa GROUP BY, COUNT y ORDER BY
    /**
     * Obtiene los productos más programados en un mes y año específicos.
     *
     * @param int $year Año.
     * @param int $month Mes.
     * @return array Lista de productos con conteo de horarios.
     */
    public function getMostProgrammedProducts($year, $month) {
        $sql = "SELECT 
                    p.nombre, 
                    COUNT(h.id_horario) AS total_horarios
                FROM 
                    horarios_molida h
                JOIN 
                    productos p ON h.id_producto = p.id_producto
                WHERE 
                    YEAR(h.fecha) = ? AND MONTH(h.fecha) = ?
                GROUP BY 
                    p.id_producto, p.nombre
                ORDER BY 
                    total_horarios DESC";
        
        $stmt = $this->db_connection->prepare($sql);
        $stmt->bind_param("ii", $year, $month);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    
    // FUNCIÓN QUE OBTIENE EL DETALLE DE HORARIOS PARA UN REPORTE MENSUAL
    /**
     * Obtiene el detalle de horarios para el reporte mensual.
     *
     * @param int $year Año.
     * @param int $month Mes.
     * @return array Lista detallada de horarios.
     */
    public function getMonthlyScheduleDetails($year, $month) {
        $sql = "SELECT 
                    h.fecha, 
                    h.hora_inicio, 
                    h.hora_fin, 
                    p.nombre AS producto_nombre
                FROM 
                    horarios_molida h
                JOIN 
                    productos p ON h.id_producto = p.id_producto
                WHERE 
                    YEAR(h.fecha) = ? AND MONTH(h.fecha) = ?
                ORDER BY 
                    h.fecha ASC, h.hora_inicio ASC";
        
        $stmt = $this->db_connection->prepare($sql);
        $stmt->bind_param("ii", $year, $month);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }
    
    
    // FUNCIÓN PARA OBTENER EL DETALLE DE HORARIOS PARA REPORTE SEMANAL
    /**
     * Obtiene el detalle de horarios para el reporte semanal.
     *
     * @param string $startDate Fecha de inicio (Y-m-d).
     * @param string $endDate Fecha de fin (Y-m-d).
     * @return array Lista detallada de horarios.
     */
    public function getWeeklyScheduleDetails($startDate, $endDate) {
        $sql = "SELECT 
                    h.fecha, 
                    h.hora_inicio, 
                    h.hora_fin, 
                    p.nombre AS producto_nombre
                FROM 
                    horarios_molida h
                JOIN 
                    productos p ON h.id_producto = p.id_producto
                WHERE 
                    h.fecha BETWEEN ? AND ?
                ORDER BY 
                    h.fecha ASC, h.hora_inicio ASC";
        
        $stmt = $this->db_connection->prepare($sql);
        $stmt->bind_param("ss", $startDate, $endDate);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

   
    
    //Funcion para obtener todos los cliente para el reporte ordenando por fecha de registro
    /**
     * Obtiene todos los clientes ordenados por fecha de registro.
     *
     * @return array Lista de clientes.
     * @throws Exception Si hay error en la consulta.
     */
    public function getAllClients() {
        $sql = "SELECT nombre, apellidos, correo, telefono, fecha_registro
                FROM cliente
                ORDER BY fecha_registro DESC";
        
        $result = $this->db_connection->query($sql);
        if ($result) {
            return $result->fetch_all(MYSQLI_ASSOC);
        } else {
            throw new Exception($this->db_connection->error);
        }
    }
    
    
    // Obtiene el conteo de clientes registrados por mes y año para la gráfica de crecimiento
    /**
     * Obtiene el conteo de registros de clientes agrupados por mes y año.
     *
     * @return array Lista de conteos por mes/año.
     * @throws Exception Si hay error en la consulta.
     */
    public function getClientRegistrationsByMonth() {
        $sql = "SELECT 
                    YEAR(fecha_registro) AS year,
                    MONTH(fecha_registro) AS month,
                    COUNT(idCliente) AS total
                FROM cliente
                GROUP BY 1, 2
                ORDER BY 1 ASC, 2 ASC";
        
        $result = $this->db_connection->query($sql);
        if ($result) {
            return $result->fetch_all(MYSQLI_ASSOC);
        } else {
            throw new Exception($this->db_connection->error);
        }
    }
}
?>