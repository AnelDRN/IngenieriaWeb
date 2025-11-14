<?php
// Modelo/conexion.php

class DB {
    private static $instance = NULL;
    private static $host = 'localhost'; // Cambia esto por tu host
    private static $dbName = 'crud_bd'; // Cambia esto por tu nombre de BD
    private static $user = 'root'; // Cambia esto por tu usuario
    private static $pass = 'BDCore'; // Cambia esto por tu contraseña

    private function __construct() {}
    private function __clone() {}

    public static function getInstance() {
        if (!isset(self::$instance)) {
            $pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
            try {
                self::$instance = new PDO(
                    'mysql:host=' . self::$host . ';dbname=' . self::$dbName, 
                    self::$user, 
                    self::$pass, 
                    $pdo_options
                );
                self::$instance->exec("SET CHARACTER SET utf8");
            } catch (PDOException $e) {
                // En un entorno de producción, no muestres detalles del error.
                // Loguea el error y muestra un mensaje genérico.
                http_response_code(500);
                echo json_encode([
                    'success' => false,
                    'message' => 'Error de conexión a la base de datos.'
                ]);
                die(); // Detener ejecución
            }
        }
        return self::$instance;
    }

    /**
     * Método para inserciones seguras con sentencias preparadas.
     * Retorna el ID del último registro insertado.
     */
    public function insertSeguro($sql, $params = []) {
        $stmt = self::getInstance()->prepare($sql);
        $stmt->execute($params);
        return self::getInstance()->lastInsertId();
    }

    /**
     * Método para actualizaciones seguras con sentencias preparadas.
     * Retorna el número de filas afectadas.
     */
    public function updateSeguro($sql, $params = []) {
        $stmt = self::getInstance()->prepare($sql);
        $stmt->execute($params);
        return $stmt->rowCount();
    }

    /**
     * Método genérico para consultas (SELECT, DELETE, etc.).
     * Para SELECT, retorna un array de resultados.
     * Para DELETE, puede retornar el número de filas afectadas.
     */
    public function query($sql, $params = []) {
        $stmt = self::getInstance()->prepare($sql);
        $stmt->execute($params);
        
        // Si es un SELECT, retorna los resultados
        if (strpos(strtoupper(trim($sql)), 'SELECT') === 0) {
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
        
        // Para otras queries (como DELETE), retorna el conteo de filas
        return $stmt->rowCount();
    }
}
?>