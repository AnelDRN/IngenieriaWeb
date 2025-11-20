<?php
// Modelo/conexion.php

class DB {
    private static $instance = NULL;
    private $pdo; // Propiedad para mantener la conexión PDO

    private static $host = 'localhost'; // Cambia esto por tu host
    private static $dbName = 'crud_db'; // Cambia esto por tu nombre de BD
    private static $user = 'root'; // Cambia esto por tu usuario
    private static $pass = 'BDCore'; // Cambia esto por tu contraseña

    // El constructor ahora inicializa la conexión PDO y la asigna a $this->pdo
    private function __construct() {
        $pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
        $pdo_options[PDO::ATTR_DEFAULT_FETCH_MODE] = PDO::FETCH_ASSOC; // Default fetch mode to associative array
        $pdo_options[PDO::ATTR_EMULATE_PREPARES] = false; // Disable emulation for security and consistency

        try {
            $this->pdo = new PDO(
                'mysql:host=' . self::$host . ';dbname=' . self::$dbName,
                self::$user,
                self::$pass,
                $pdo_options
            );
            $this->pdo->exec("SET CHARACTER SET utf8");
        } catch (PDOException $e) {
            // Manejo de errores de conexión para depuración
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'message' => 'Error en la base de datos: ' . $e->getMessage()
            ]);
            die();
        }
    }

    private function __clone() {} // Previene la clonación de la instancia

    // getInstance ahora devuelve una instancia de la clase DB, no el objeto PDO directamente
    public static function getInstance() {
        if (!isset(self::$instance)) {
            self::$instance = new DB(); // Crea una nueva instancia de DB
        }
        return self::$instance;
    }

    /**
     * Método para inserciones seguras con sentencias preparadas.
     * Retorna el ID del último registro insertado.
     */
    public function insertSeguro($sql, $params = []) {
        $stmt = $this->pdo->prepare($sql); // Usa la conexión PDO interna
        $stmt->execute($params);
        return $this->pdo->lastInsertId();
    }

    /**
     * Método para actualizaciones seguras con sentencias preparadas.
     * Retorna el número de filas afectadas.
     */
    public function updateSeguro($sql, $params = []) {
        $stmt = $this->pdo->prepare($sql); // Usa la conexión PDO interna
        $stmt->execute($params);
        return $stmt->rowCount();
    }

    /**
     * Método genérico para ejecutar consultas (SELECT, INSERT, UPDATE, DELETE).
     * Para SELECT, retorna el PDOStatement para permitir fetchAll/fetch.
     * Para INSERT/UPDATE/DELETE, retorna el número de filas afectadas.
     */
    public function query($sql, $params = []) {
        $stmt = $this->pdo->prepare($sql); // Usa la conexión PDO interna
        $stmt->execute($params);

        $command = strtoupper(substr(trim($sql), 0, 6)); // Obtiene los primeros 6 caracteres para identificar el tipo de comando

        if ($command === 'SELECT') {
            return $stmt; // Para SELECT, devuelve el PDOStatement
        }
        return $stmt->rowCount(); // Para otros, devuelve el número de filas afectadas
    }
}
?>