<?php
class Documento {
    private $conn;

    public function __construct($db) { $this->conn = $db; }

public function guardarDocumentoTicket($id_ticket, $nombre, $tipo, $contenido_binario) {
    try {
        // Crear carpeta en /uploads/tickets/{id_ticket}/   
        $carpeta = "/var/www/html/uploads/tickets/{$id_ticket}/";
        if (!is_dir($carpeta)) {
            mkdir($carpeta, 0755, true); // true = crea carpetas intermedias
        }

        // Guardar archivo en ruta
        $extension  = pathinfo($nombre, PATHINFO_EXTENSION);
        $nombre_unico = uniqid('foto_') . '.' . $extension;
        $ruta_disco   = $carpeta . $nombre_unico;
        $ruta_bd      = "/uploads/tickets/{$id_ticket}/{$nombre_unico}";

        if (!file_put_contents($ruta_disco, $contenido_binario)) {
            throw new Exception("No se pudo guardar el archivo en disco");
        }

        $this->conn->beginTransaction();

        // Guardar solo la ruta en BD
        $sqlDoc = "INSERT INTO documentos (nombre, tipo, fecha_subida, ruta) 
                   VALUES (?, ?, NOW(), ?)";
        $stmtDoc = $this->conn->prepare($sqlDoc);
        $stmtDoc->execute([$nombre, $tipo, $ruta_bd]);

        $id_documento = $this->conn->lastInsertId();

        //Vincular en tabla intermedia
        $sqlRel = "INSERT INTO documento_x_tickets (id_documento, id_ticket) 
                   VALUES (?, ?)";
        $stmtRel = $this->conn->prepare($sqlRel);
        $stmtRel->execute([$id_documento, $id_ticket]);

        $this->conn->commit();
        return $ruta_bd; // devuelve la ruta para usarla en el correo

    } catch (Exception $e) {
        if ($this->conn->inTransaction()) {
            $this->conn->rollBack();
        }
        error_log("Error al guardar documento: " . $e->getMessage());
        return false;
    }

}
}