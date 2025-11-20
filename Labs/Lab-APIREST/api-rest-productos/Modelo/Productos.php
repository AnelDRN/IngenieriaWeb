<?php
require_once 'conexion.php';

class Producto {
    public $id;
    public $codigo;
    public $producto;
    public $precio;
    public $cantidad;

    // Método POST - Guardar
    public function guardar() {
        $db = DB::getInstance();
        $sql = "INSERT INTO productos (codigo, producto, precio, cantidad) 
                VALUES (?, ?, ?, ?)";
        $params = [$this->codigo, $this->producto, $this->precio, $this->cantidad];
        return $db->insertSeguro($sql, $params);
    }

    /**
     * Método GET - Listar todos los productos
     * @return array Array de productos
     */
    public static function listarTodos() {
        $db = DB::getInstance();
        $sql = "SELECT * FROM productos ORDER BY id DESC";
        // Devuelve un array de objetos asociativos
        return $db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Método GET - Buscar un producto por su ID
     * @param int $id ID del producto
     * @return array|false Un array asociativo con los datos del producto o false si no se encuentra
     */
    public static function buscarPorId($id) {
        $db = DB::getInstance();
        $sql = "SELECT * FROM productos WHERE id = ?";
        // Devuelve un único resultado como array asociativo
        return $db->query($sql, [$id])->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Método PUT - Actualizar un producto existente
     * @return bool True si la actualización fue exitosa, false en caso contrario
     */
    public function editar() {
        $db = DB::getInstance();
        $sql = "UPDATE productos 
                SET codigo = ?, producto = ?, precio = ?, cantidad = ? 
                WHERE id = ?";
        $params = [$this->codigo, $this->producto, $this->precio, 
                   $this->cantidad, $this->id];
        return $db->updateSeguro($sql, $params);
    }

    /**
     * Método DELETE - Eliminar un producto por su ID (Opcional)
     * @param int $id ID del producto a eliminar
     * @return bool True si la eliminación fue exitosa, false en caso contrario
     */
    public static function eliminar($id) {
        $db = DB::getInstance();
        $sql = "DELETE FROM productos WHERE id = ?";
        return $db->query($sql, [$id]);
    }
}
?>