<?php
// Modelo/conexion.php

/**
 * Clase DB
 *
 * Gestiona la conexión a la base de datos utilizando el patrón Singleton
 * para garantizar una única instancia de la conexión.
 * Proporciona métodos seguros para operaciones CRUD.
 */
class DB {
    private static $instance = null;
    private $conn;

    private $host = 'localhost';
    private $user = 'root';
    private $pass = 'BDCore'; // Cambia esto a tu contraseña si es necesario
    private $name = 'crud_db'; // La guía especifica 'productosdb', pero mantengo la anterior para no romper tu entorno actual. Puedes cambiarla si lo deseas.

    /**
     * Constructor privado para prevenir la instanciación directa.
     */
    private function __construct() {
        $dsn = 'mysql:host=' . $this->host . ';dbname=' . $this->name . ';charset=utf8mb4';
        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];

        try {
            $this->conn = new PDO($dsn, $this->user, $this->pass, $options);
        } catch (PDOException $e) {
            // En un caso real, loggearíamos el error. Para el lab, lo lanzamos.
            throw new PDOException($e->getMessage(), (int)$e->getCode());
        }
    }

    /**
     * Obtiene la instancia única de la clase DB (Patrón Singleton).
     * @return DB La instancia de la clase.
     */
    public static function getInstance() {
        if (self::$instance == null) {
            self::$instance = new DB();
        }
        return self::$instance;
    }

    /**
     * Ejecuta una consulta de tipo SELECT.
     * @param string $sql La consulta SQL.
     * @param array $params Los parámetros para la consulta preparada.
     * @return array Un array con los resultados.
     */
    public function query($sql, $params = []) {
        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    /**
     * Ejecuta una consulta de tipo INSERT.
     * @param string $sql La consulta SQL.
     * @param array $params Los parámetros para la consulta preparada.
     * @return bool True si la inserción fue exitosa, false en caso contrario.
     */
    public function insertSeguro($sql, $params) {
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute($params);
    }

    /**
     * Ejecuta una consulta de tipo UPDATE.
     * @param string $sql La consulta SQL.
     * @param array $params Los parámetros para la consulta preparada.
     * @return bool True si la actualización fue exitosa, false en caso contrario.
     */
    public function updateSeguro($sql, $params) {
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute($params);
    }
    
    /**
     * Método para obtener la conexión PDO directamente si es necesario.
     * No es parte de la guía, pero puede ser útil.
     */
    public function getConnection() {
        return $this->conn;
    }
}