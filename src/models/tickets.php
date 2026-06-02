<?php
class Ticket {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function crear(int $id_usuario, int $id_servicio, string $modelo, string $descripcion, string $matricula): int {
        try {
            // He quitado una '?' y la columna de la foto que ahora va en 'documentos'
            $stmt = $this->conn->prepare(
                "INSERT INTO Ticket (id_usuario, id_servicio, modelo_auto, descripcion, matricula, estado, fecha_inicio)
                 VALUES (?, ?, ?, ?, ?, 'pendiente', NOW())"
            );

            // IMPORTANTE: Ejecutar con los datos en el orden correcto
            if ($stmt->execute([$id_usuario, $id_servicio, $modelo, $descripcion ,$matricula])) {
                return (int)$this->conn->lastInsertId();
            }

            return 0;

        } catch (PDOException $e) {
            error_log("Error en Ticket::crear -> " . $e->getMessage());
            return 0;
        }
    }


    public function getUltimoId(): int {
        return (int)$this->conn->lastInsertId();
    }
}