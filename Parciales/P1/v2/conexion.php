<?php
// 12. Crear una clase para la Conexión, con funciones de base de datos.
class Conexion {
    private $servidor = '127.0.0.1';
    private $nombre_bd = 'parcial_v2_db';
    private $usuario = 'root';
    private $contrasena = ''; // Asume una contraseña vacía
    private $pdo;

    public function __construct() {
        try {
            $this->pdo = new PDO("mysql:host={$this->servidor};dbname={$this->nombre_bd}", $this->usuario, $this->contrasena);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->pdo->exec("SET NAMES 'utf8'");
        } catch (PDOException $e) {
            // En un entorno de producción, esto debería registrarse en un archivo de log
            // y mostrar un mensaje de error genérico al usuario.
            error_log("Error de conexión a la BD: " . $e->getMessage());
            die("Error: No se pudo conectar a la base de datos. Por favor, intente más tarde.");
        }
    }

    public function getPdo() {
        return $this->pdo;
    }

    /**
     * Ejecuta una consulta SELECT y devuelve todos los resultados.
     * @param string $sql La consulta SQL a ejecutar.
     * @param array $parametros Un array de parámetros para la consulta preparada.
     * @return array Un array con los resultados de la consulta.
     */
    public function consultar($sql, $parametros = []) {
        try {
            $sentencia = $this->pdo->prepare($sql);
            $sentencia->execute($parametros);
            return $sentencia->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error de consulta: " . $e->getMessage());
            die("Error al realizar la consulta.");
        }
    }
}
?>