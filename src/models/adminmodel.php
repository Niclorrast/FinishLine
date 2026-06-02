<?php

class AdminModel
{
    private PDO $conn;

    public function __construct(PDO $conn)
    {
        $this->conn = $conn;
    }

    // USUARIOS
    public function getUsuarios(): array
    {
        return $this->conn->query(
            "SELECT id_usuario, Nombre, Apellido, Correo, Telefono, Rol
             FROM Usuario ORDER BY Rol, Nombre"
        )->fetchAll();
    }

    public function getEmpleados(): array {
        return $this->conn->query(
            "SELECT id_usuario, Nombre, Apellido, Correo, Telefono
         FROM Usuario WHERE Rol IN ('empleado','admin')
         ORDER BY Nombre"
        )->fetchAll();
    }

    public function cambiarRol(int $id, string $rol): bool
    {
        if (!in_array($rol, ['admin', 'empleado', 'cliente'])) return false;
        $stmt = $this->conn->prepare("UPDATE Usuario SET Rol = ? WHERE id_usuario = ?");
        return $stmt->execute([$rol, $id]);
    }

    public function eliminarUsuario(int $id): bool
    {
        $stmt = $this->conn->prepare("SELECT id_autenticacion FROM Usuario WHERE id_usuario = ?");
        $stmt->execute([$id]);
        $u = $stmt->fetch();
        if (!$u) return false;
        $this->conn->prepare("DELETE FROM Usuario WHERE id_usuario = ?")->execute([$id]);
        $this->conn->prepare("DELETE FROM Autenticacion WHERE id_autenticacion = ?")->execute([$u['id_autenticacion']]);
        return true;
    }

    public function crearEmpleado(string $nombre, string $apellido, string $telefono, string $correo, string $password): array
    {
        $stmt = $this->conn->prepare("SELECT COUNT(*) FROM Usuario WHERE Correo = ?");
        $stmt->execute([$correo]);
        if ($stmt->fetchColumn() > 0) return ['ok' => false, 'msg' => 'Correo ya registrado.'];

        $hash = password_hash($password, PASSWORD_BCRYPT);
        $s1 = $this->conn->prepare("INSERT INTO Autenticacion (password_hash) VALUES (?)");
        $s1->execute([$hash]);
        $idAuth = $this->conn->lastInsertId();

        $s2 = $this->conn->prepare(
            "INSERT INTO Usuario (id_autenticacion, Nombre, Apellido, Telefono, Correo, Rol)
             VALUES (?, ?, ?, ?, ?, 'empleado')"
        );
        $s2->execute([$idAuth, $nombre, $apellido, $telefono, $correo]);
        return ['ok' => true, 'msg' => "Empleado '$nombre $apellido' creado."];
    }

    // TICKETS
    public function getTodosTickets(): array
    {
        return $this->conn->query(
            "SELECT t.*, u.Nombre, u.Apellido, u.Correo,
                    s.Nombre AS servicio,
                    e.Nombre AS empleado_nombre, e.Apellido AS empleado_apellido
             FROM Ticket t
             LEFT JOIN Usuario u   ON t.id_usuario  = u.id_usuario
             LEFT JOIN Servicios s ON t.id_servicio = s.id_servicio
             LEFT JOIN Usuario e   ON t.id_empleado = e.id_usuario
             ORDER BY t.id_ticket DESC"
        )->fetchAll();
    }

    public function getTicketsEmpleado(int $id): array
    {
        $stmt = $this->conn->prepare(
            "SELECT t.*, u.Nombre, u.Apellido, u.Correo, s.Nombre AS servicio
             FROM Ticket t
             LEFT JOIN Usuario u   ON t.id_usuario  = u.id_usuario
             LEFT JOIN Servicios s ON t.id_servicio = s.id_servicio
             WHERE t.id_empleado = ?
             ORDER BY t.id_ticket DESC"
        );
        $stmt->execute([$id]);
        return $stmt->fetchAll();
    }

    public function cambiarEstadoTicket(int $id, string $estado): bool
    {
        if (!in_array($estado, ['pendiente', 'en proceso', 'completado', 'cancelado'])) return false;
        $stmt = $this->conn->prepare("UPDATE Ticket SET estado = ? WHERE id_ticket = ?");
        return $stmt->execute([$estado, $id]);
    }

    public function responderTicket(int $id, int $idEmpleado, float $presupuesto, string $descTrabajo): bool
    {
        $stmt = $this->conn->prepare(
            "UPDATE Ticket SET id_empleado = ?, presupuesto = ?, descripcion_trabajo = ?, estado = 'en proceso'
             WHERE id_ticket = ?"
        );
        return $stmt->execute([$idEmpleado, $presupuesto, $descTrabajo, $id]);
    }

    // NOTAS
    public function getNotasTicket(int $id): array
    {
        $stmt = $this->conn->prepare(
            "SELECT n.*, u.Nombre, u.Apellido FROM Nota_X_Ticket n
             LEFT JOIN Usuario u ON n.id_usuario = u.id_usuario
             WHERE n.id_ticket = ? ORDER BY n.fecha ASC"
        );
        $stmt->execute([$id]);
        return $stmt->fetchAll();
    }

    public function guardarNota(int $idTicket, int $idUsuario, string $nota): bool
    {
        $stmt = $this->conn->prepare(
            "INSERT INTO Nota_X_Ticket (id_ticket, id_usuario, nota) VALUES (?, ?, ?)"
        );
        return $stmt->execute([$idTicket, $idUsuario, $nota]);
    }

    // RESPUESTAS TICKET
    public function getRespuestasTicket(int $id): array
    {
        $stmt = $this->conn->prepare(
            "SELECT * FROM Respuesta_x_ticket WHERE id_ticket = ? ORDER BY fecha_respuesta DESC"
        );
        $stmt->execute([$id]);
        return $stmt->fetchAll();
    }

    public function guardarRespuestaTicket(int $idTicket, string $respuesta): bool
    {
        $stmt = $this->conn->prepare(
            "INSERT INTO Respuesta_x_ticket (id_ticket, respuesta) VALUES (?, ?)"
        );
        return $stmt->execute([$idTicket, $respuesta]);
    }

    public function getDocumentosTicket(int $id): array
    {
        $stmt = $this->conn->prepare(
            "SELECT d.id_documento, d.nombre, d.tipo, d.ruta
             FROM documentos d
             INNER JOIN documento_x_tickets dt ON d.id_documento = dt.id_documento
             WHERE dt.id_ticket = ?
             ORDER BY d.fecha_subida ASC"
        );
        $stmt->execute([$id]);
        return $stmt->fetchAll();
    }

    // SOLICITUDES
    public function getSolicitudes(): array
    {
        return $this->conn->query("SELECT * FROM Solicitudes ORDER BY fecha_envio DESC")->fetchAll();
    }

    public function getRespuestasSolicitud(int $id): array
    {
        $stmt = $this->conn->prepare(
            "SELECT * FROM Respuesta_x_solicitud WHERE id_solicitud = ? ORDER BY fecha_respuesta DESC"
        );
        $stmt->execute([$id]);
        return $stmt->fetchAll();
    }

    public function guardarRespuestaSolicitud(int $idSolicitud, string $respuesta): bool {
        $stmt = $this->conn->prepare(
            "SELECT COUNT(*) FROM Respuesta_x_solicitud WHERE id_solicitud = ?"
        );
        $stmt->execute([$idSolicitud]);
        if ($stmt->fetchColumn() > 0) return false;

        $stmt = $this->conn->prepare(
            "INSERT INTO Respuesta_x_solicitud (id_solicitud, respuesta) VALUES (?, ?)"
        );
        $ok = $stmt->execute([$idSolicitud, $respuesta]);

        if ($ok) {
            $this->conn->prepare(
                "UPDATE Solicitudes SET estado = 'respondida' WHERE id_solicitud = ?"
            )->execute([$idSolicitud]);
        }

        return $ok;
    }

    // STATS
    public function getStats(): array
    {
        return [
            'total_usuarios' => $this->conn->query("SELECT COUNT(*) FROM Usuario")->fetchColumn(),
            'total_clientes' => $this->conn->query("SELECT COUNT(*) FROM Usuario WHERE Rol='cliente'")->fetchColumn(),
            'total_empleados' => $this->conn->query("SELECT COUNT(*) FROM Usuario WHERE Rol='empleado'")->fetchColumn(),
            'total_tickets' => $this->conn->query("SELECT COUNT(*) FROM Ticket")->fetchColumn(),
            'tickets_pendientes' => $this->conn->query("SELECT COUNT(*) FROM Ticket WHERE estado='pendiente'")->fetchColumn(),
            'tickets_en_proceso' => $this->conn->query("SELECT COUNT(*) FROM Ticket WHERE estado='en proceso'")->fetchColumn(),
        ];
    }

    public function getStatsEmpleado(int $id): array
    {
        $s1 = $this->conn->prepare("SELECT COUNT(*) FROM Ticket WHERE id_empleado = ?");
        $s2 = $this->conn->prepare("SELECT COUNT(*) FROM Ticket WHERE id_empleado = ? AND estado = 'en proceso'");
        $s3 = $this->conn->prepare("SELECT COUNT(*) FROM Ticket WHERE id_empleado = ? AND estado = 'completado'");
        $s1->execute([$id]);
        $s2->execute([$id]);
        $s3->execute([$id]);
        return [
            'mis_tickets' => $s1->fetchColumn(),
            'en_proceso' => $s2->fetchColumn(),
            'completados' => $s3->fetchColumn(),
        ];
    }
}