<?php
class Database {
    private $host = '127.0.0.1';
    private $db_name = 'parcial_v1_db';
    private $username = 'root';
    private $password = 'BDCore'; // Asume una contraseña vacía para XAMPP/WAMPP por defecto
    private $conn;

    public function connect() {
        $this->conn = null;

        try {
            $this->conn = new PDO('mysql:host=' . $this->host . ';dbname=' . $this->db_name, $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->conn->exec("SET CHARACTER SET utf8");
        } catch(PDOException $e) {
            echo 'Error de Conexión: ' . $e->getMessage();
        }

        return $this->conn;
    }

    public function getConnection() {
        if ($this->conn) {
            return $this->conn;
        }
        return $this->connect();
    }

    public function fetchAll($query, $params = []) {
        try {
            $stmt = $this->connect()->prepare($query);
            $stmt->execute($params);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            // En una aplicación real, manejarías este error de forma más elegante
            die("Error al ejecutar la consulta: " . $e->getMessage());
        }
    }
}
?>