<?php
require_once 'conexion.php';

class Producto {
    public $id;
    public $codigo;
    public $producto;
    public $precio;
    public $cantidad;
    
    // Método POST - Guardar (ya implementado en la guía)
    public function guardar() {
        $db = DB::getInstance();
        $sql = "INSERT INTO productos (codigo, producto, precio, cantidad) 
                VALUES (?, ?, ?, ?)";
        $params = [$this->codigo, $this->producto, $this->precio, $this->cantidad];
        // Usamos el método insertSeguro que retorna el ID, pero la guía espera un booleano.
        // Si lastInsertId() > 0, la inserción fue exitosa.
        return $db->insertSeguro($sql, $params) > 0;
    }
    
    // Método GET - Listar todos (IMPLEMENTADO)
    public static function listarTodos() {
        $db = DB::getInstance();
        $sql = "SELECT * FROM productos ORDER BY id DESC";
        // El método query ya retorna un array de resultados.
        return $db->query($sql);
    }
    
    // Método GET - Buscar por ID (IMPLEMENTADO)
    public static function buscarPorId($id) {
        $db = DB::getInstance();
        $sql = "SELECT * FROM productos WHERE id = ?";
        $resultado = $db->query($sql, [$id]);
        // query() devuelve un array de filas. Como buscamos por ID, solo debe haber una.
        // Devolvemos la primera fila, o `false` si no hay resultados.
        return $resultado ? $resultado[0] : false;
    }
    
    // Método PUT - Actualizar (IMPLEMENTADO)
    public function editar() {
        $db = DB::getInstance();
        $sql = "UPDATE productos 
                SET codigo = ?, producto = ?, precio = ?, cantidad = ? 
                WHERE id = ?";
        $params = [$this->codigo, $this->producto, $this->precio, 
                   $this->cantidad, $this->id];
        // updateSeguro retorna el número de filas afectadas.
        // Si es > 0, la actualización fue exitosa.
        return $db->updateSeguro($sql, $params) > 0;
    }
    
    // Método DELETE - Eliminar (OPCIONAL)
    public static function eliminar($id) {
        $db = DB::getInstance();
        $sql = "DELETE FROM productos WHERE id = ?";
        // query() para DELETE retorna el número de filas afectadas.
        return $db->query($sql, [$id]) > 0;
    }
}
?>