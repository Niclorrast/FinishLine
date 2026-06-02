<?php

class Usuario {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    // Registrar usuario
    public function registrar($nombre, $apellido, $telefono, $correo, $password) {
        try {
            // Primero insertamos en Autenticacion
            $stmt = $this->conn->prepare(
                "INSERT INTO Autenticacion (password_hash) VALUES (?)"
            );
            $password_hash = password_hash($password, PASSWORD_BCRYPT);
            $stmt->execute([$password_hash]);

            // Obtenemos el id generado
            $id_autenticacion = $this->conn->lastInsertId();

            // Luego insertamos en Usuario
            $stmt = $this->conn->prepare(
                "INSERT INTO Usuario (id_autenticacion, Nombre, Apellido, Telefono, Correo) 
                 VALUES (?, ?, ?, ?, ?)"
            );
            $stmt->execute([$id_autenticacion, $nombre, $apellido, $telefono, $correo]);

            return true;
        } catch (PDOException $e) {
            return false;
        }
    }

    // Login usuario
    public function login($correo, $password) {
        try {
            $stmt = $this->conn->prepare(
                "SELECT u.*, a.password_hash 
                 FROM Usuario u 
                 JOIN Autenticacion a ON u.id_autenticacion = a.id_autenticacion 
                 WHERE u.Correo = ?"
            );
            $stmt->execute([$correo]);
            $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($usuario && password_verify($password, $usuario['password_hash'])) {
                return $usuario;
            }
            return false;
        } catch (PDOException $e) {
            return false;
        }
    }

    // Verificar si el correo ya existe
    public function existeCorreo($correo) {
        $stmt = $this->conn->prepare(
            "SELECT COUNT(*) FROM Usuario WHERE Correo = ?"
        );
        $stmt->execute([$correo]);
        return $stmt->fetchColumn() > 0;
    }
    /* ─────────────────────────── USUARIOS ─────────────────────────── */

    public function getUsuarios(): array {
        $stmt = $this->conn->query(
            "SELECT id_usuario, Nombre, Apellido, Correo, Telefono, Rol
             FROM Usuario ORDER BY Rol, Nombre"
        );
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getEmpleados(): array {
        $stmt = $this->conn->query(
            "SELECT id_usuario, Nombre, Apellido, Correo, Telefono
         FROM Usuario WHERE Rol IN ('empleado','admin')
         ORDER BY Nombre"
        );
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function cambiarRol(int $id, string $nuevoRol): bool {
        if (!in_array($nuevoRol, ['admin','empleado','cliente'])) return false;
        $stmt = $this->conn->prepare("UPDATE Usuario SET Rol = ? WHERE id_usuario = ?");
        return $stmt->execute([$nuevoRol, $id]);
    }

    public function eliminarUsuario(int $id): bool {
        $stmt = $this->conn->prepare("SELECT id_autenticacion FROM Usuario WHERE id_usuario = ?");
        $stmt->execute([$id]);
        $usuario = $stmt->fetch();
        if (!$usuario) return false;
        $this->conn->prepare("DELETE FROM Usuario WHERE id_usuario = ?")->execute([$id]);
        $this->conn->prepare("DELETE FROM Autenticacion WHERE id_autenticacion = ?")->execute([$usuario['id_autenticacion']]);
        return true;
    }
    public function crearEmpleado(
        string $nombre,
        string $apellido,
        string $telefono,
        string $correo,
        string $password
    ): array {

        // Comprobar si el correo ya existe
        $stmt = $this->conn->prepare(
            "SELECT COUNT(*) FROM Usuario WHERE Correo = ?"
        );
        $stmt->execute([$correo]);
        if ($stmt->fetchColumn() > 0) {
            return ['ok' => false, 'msg' => 'Ya existe un usuario con ese correo.'];
        }

        try {
            // 1. Insertar en Autenticacion
            $hash = password_hash($password, PASSWORD_BCRYPT);
            $s1   = $this->conn->prepare(
                "INSERT INTO Autenticacion (password_hash) VALUES (?)"
            );
            $s1->execute([$hash]);
            $idAuth = $this->conn->lastInsertId();

            // 2. Insertar en Usuario con Rol = 'empleado'
            $s2 = $this->conn->prepare(
                "INSERT INTO Usuario
                 (id_autenticacion, Nombre, Apellido, Telefono, Correo, Rol)
             VALUES (?, ?, ?, ?, ?, 'empleado')"
            );
            $s2->execute([$idAuth, $nombre, $apellido, $telefono, $correo]);

            return ['ok' => true, 'msg' => "Empleado '$nombre $apellido' creado correctamente."];

        } catch (PDOException $e) {
            return ['ok' => false, 'msg' => 'Error al crear el empleado: ' . $e->getMessage()];
        }
    }
}
?>