<?php

date_default_timezone_set('Europe/Madrid');

class Database {
    private string $host;
    private string $db_name;
    private string $username;
    private string $password;
    private string $charset = 'utf8mb4';

    private ?PDO $conn = null;

    //Contructor con las claves a la base de datos
    public function __construct() {
        $this->host     = 'db';
        $this->db_name  = getenv('MYSQL_DATABASE');
        $this->username = 'root';
        $this->password = getenv('MYSQL_ROOT_PASSWORD');
    }
    //Conexion PDO para la bbdd
    public function  getConnection(): PDO {
        if ($this->conn !== null) {
            return $this->conn;
        }

        $dsn = "mysql:host={$this->host};dbname={$this->db_name};charset={$this->charset}";

        try {
            $this->conn = new PDO($dsn, $this->username, $this->password);
            $this->conn->exec("SET time_zone = 'Europe/Madrid'");
            $this->conn->setAttribute(PDO::ATTR_ERRMODE,            PDO::ERRMODE_EXCEPTION);
            $this->conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            $this->conn->setAttribute(PDO::ATTR_EMULATE_PREPARES,   false);
        } catch (PDOException $e) {
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'message' => 'Error de conexión: ' . $e->getMessage()
            ]);
            exit;
        }

        return $this->conn;
    }
}