<?php

class SolicitudModel
{
    private $conn;
    private string $table = 'Solicitudes';

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    public function crear(array $data): int
    {
        $sql = "INSERT INTO {$this->table}
                (nombre, correo, asunto, mensaje, fecha_envio, estado)
            VALUES
                (:nombre, :correo, :asunto, :mensaje, NOW(), 'pendiente')";

        $stmt = $this->conn->prepare($sql);

        $stmt->bindValue(':nombre',  $data['nombre'],  PDO::PARAM_STR);
        $stmt->bindValue(':correo',  $data['correo'],  PDO::PARAM_STR);
        $stmt->bindValue(':asunto',  $data['asunto'],  PDO::PARAM_STR);
        $stmt->bindValue(':mensaje', $data['mensaje'], PDO::PARAM_STR);

        $stmt->execute();
        return (int) $this->conn->lastInsertId();
    }




}