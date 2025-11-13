<?php
// Modelo/Productos.php

/**
 * Incluir la clase DB para la interacción con la base de datos.
 */
require_once 'conexion.php';

/**
 * Clase Producto
 * 
 * Representa la entidad 'producto' y encapsula la lógica de negocio
 * para las operaciones CRUD.
 */
class Producto {
    // Propiedades de la clase que coinciden con los campos de la tabla
    public $id;
    public $codigo;
    public $producto;
    public $precio;
    public $cantidad;

    /**
     * Constructor para inicializar un objeto Producto.
     * @param array $data Datos para inicializar el producto.
     */
    public function __construct($data = []) {
        $this->id = $data['id'] ?? null;
        $this->codigo = $data['codigo'] ?? null;
        $this->producto = $data['producto'] ?? null;
        $this->precio = $data['precio'] ?? null;
        $this->cantidad = $data['cantidad'] ?? null;
    }

    /**
     * Valida los campos obligatorios del producto.
     * @return array Un array de mensajes de error. Vacío si no hay errores.
     */
    public function validar() {
        $errors = [];
        if (empty($this->codigo)) {
            $errors[] = 'El campo Código es obligatorio.';
        }
        if (empty($this->producto)) {
            $errors[] = 'El campo Producto es obligatorio.';
        }
        if (!isset($this->precio) || $this->precio === '') {
            $errors[] = 'El campo Precio es obligatorio.';
        } elseif (!is_numeric($this->precio) || $this->precio < 0) {
            $errors[] = 'El precio debe ser un número positivo.';
        }
        if (!isset($this->cantidad) || $this->cantidad === '') {
            $errors[] = 'El campo Cantidad es obligatorio.';
        } elseif (!is_numeric($this->cantidad) || $this->cantidad < 0) {
            $errors[] = 'La cantidad debe ser un número entero positivo.';
        }
        return $errors;
    }

    /**
     * Guarda un nuevo producto en la base de datos.
     * @return bool True si se guardó correctamente, false en caso contrario.
     */
    public function guardar() {
        $db = DB::getInstance();
        $sql = "INSERT INTO productos (codigo, producto, precio, cantidad) VALUES (?, ?, ?, ?)";
        $params = [$this->codigo, $this->producto, $this->precio, $this->cantidad];
        return $db->insertSeguro($sql, $params);
    }

    /**
     * Actualiza un producto existente en la base de datos.
     * @return bool True si se actualizó correctamente, false en caso contrario.
     */
    public function editar() {
        $db = DB::getInstance();
        $sql = "UPDATE productos SET codigo = ?, producto = ?, precio = ?, cantidad = ? WHERE id = ?";
        $params = [$this->codigo, $this->producto, $this->precio, $this->cantidad, $this->id];
        return $db->updateSeguro($sql, $params);
    }

    /**
     * Busca un producto por su código.
     * @param string $codigo El código del producto a buscar.
     * @return array|false Los datos del producto o false si no se encuentra.
     */
    public static function buscar($codigo) {
        $db = DB::getInstance();
        $sql = "SELECT * FROM productos WHERE codigo = ? LIMIT 1";
        $result = $db->query($sql, [$codigo]);
        return !empty($result) ? $result[0] : false;
    }

    /**
     * Lista todos los productos de la base de datos.
     * No está en la guía explícitamente, pero es necesario para la funcionalidad de la tabla.
     * @return array Un array con todos los productos.
     */
    public static function listarTodos() {
        $db = DB::getInstance();
        $sql = "SELECT * FROM productos ORDER BY id DESC";
        return $db->query($sql);
    }

    /**
     * Verifica si un código de producto ya existe en la base de datos.
     * @param string $codigo El código a verificar.
     * @param int|null $excludeId ID del producto a excluir de la búsqueda (para ediciones).
     * @return bool True si el código existe, false en caso contrario.
     */
    public static function codigoExiste($codigo, $excludeId = null) {
        $db = DB::getInstance();
        $sql = "SELECT id FROM productos WHERE codigo = ?";
        $params = [$codigo];
        if ($excludeId !== null) {
            $sql .= " AND id != ?";
            $params[] = $excludeId;
        }
        $result = $db->query($sql, $params);
        return !empty($result);
    }
}