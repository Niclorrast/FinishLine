<?php
class Servicio {
    private $db;
    private $table = "Servicios";

    public function __construct($pdo) {
        $this->db = $pdo;
    }

    /**
     * Obtiene todos los servicios para listarlos o llenar selects
     */
    public function obtenerTodos() {
        try {
            $sql = "SELECT id_servicio, Nombre, Descripcion FROM " . $this->table;
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            
            // Retorna un array asociativo con todos los resultados
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error en Servicio::obtenerTodos -> " . $e->getMessage());
            return [];
        }
    }

    /**
     * Busca la informacion de un servicio especifico por su ID
     */
    public function buscarPorId($id) {
        try {
            $sql = "SELECT * FROM " . $this->table . " WHERE id_servicio = :id LIMIT 1";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error en Servicio::buscarPorId -> " . $e->getMessage());
            return null;
        }
    }
}
?>