<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

require_once '../Modelo/conexion.php';
require_once '../Modelo/Productos.php';

$method = $_SERVER['REQUEST_METHOD'];

// Centralizar con SWITCH según método HTTP
switch ($method) {
    case 'POST':
        registrarProducto();
        break;
    case 'GET':
        obtenerProductos();
        break;
    case 'PUT':
        actualizarProducto();
        break;
    case 'DELETE':
        eliminarProducto();
        break;
    default:
        http_response_code(405); // Método no permitido
        echo json_encode(['success' => false, 'message' => 'Método no permitido']);
}

// POST - Registrar un nuevo producto
function registrarProducto() {
    $data = json_decode(file_get_contents("php://input"), true);

    if (!isset($data['codigo']) || !isset($data['producto']) || !isset($data['precio']) || !isset($data['cantidad'])) {
        http_response_code(400); // Bad Request
        echo json_encode(['success' => false, 'message' => 'Datos incompletos. Se requiere: codigo, producto, precio y cantidad.']);
        return;
    }

    try {
        $producto = new Producto();
        $producto->codigo = $data['codigo'];
        $producto->producto = $data['producto'];
        $producto->precio = $data['precio'];
        $producto->cantidad = $data['cantidad'];

        if ($producto->guardar()) {
            http_response_code(201); // Creado
            echo json_encode(['success' => true, 'message' => 'Producto registrado exitosamente']);
        } else {
            http_response_code(500); // Internal Server Error
            echo json_encode(['success' => false, 'message' => 'Error al registrar el producto']);
        }
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Error en el servidor: ' . $e->getMessage()]);
    }
}

// GET - Obtener uno o todos los productos
function obtenerProductos() {
    try {
        if (isset($_GET['id'])) {
            $id = intval($_GET['id']);
            $producto = Producto::buscarPorId($id);

            if ($producto) {
                http_response_code(200); // OK
                echo json_encode(['success' => true, 'data' => $producto]);
            } else {
                http_response_code(404); // No encontrado
                echo json_encode(['success' => false, 'message' => 'Producto no encontrado']);
            }
        } else {
            $productos = Producto::listarTodos();
            http_response_code(200); // OK
            echo json_encode(['success' => true, 'data' => $productos]);
        }
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Error en el servidor: ' . $e->getMessage()]);
    }
}

// PUT - Actualizar un producto existente
function actualizarProducto() {
    // Para PUT, el ID vendrá en la URL
    if (!isset($_GET['id'])) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'El ID del producto es requerido en la URL.']);
        return;
    }
    
    $id = intval($_GET['id']);
    $data = json_decode(file_get_contents("php://input"), true);

    // Validar datos de entrada
    if (!isset($data['codigo']) || !isset($data['producto']) || !isset($data['precio']) || !isset($data['cantidad'])) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Datos incompletos para la actualización.']);
        return;
    }

    try {
        // Verificar si el producto existe antes de actualizar
        if (!Producto::buscarPorId($id)) {
            http_response_code(404);
            echo json_encode(['success' => false, 'message' => 'Producto no encontrado.']);
            return;
        }

        $producto = new Producto();
        $producto->id = $id;
        $producto->codigo = $data['codigo'];
        $producto->producto = $data['producto'];
        $producto->precio = $data['precio'];
        $producto->cantidad = $data['cantidad'];

        if ($producto->editar()) {
            http_response_code(200); // OK
            echo json_encode(['success' => true, 'message' => 'Producto actualizado exitosamente']);
        } else {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Error al actualizar el producto.']);
        }
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Error en el servidor: ' . $e->getMessage()]);
    }
}

// DELETE - Eliminar un producto (opcional)
function eliminarProducto() {
    if (!isset($_GET['id'])) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'El ID del producto es requerido en la URL.']);
        return;
    }

    $id = intval($_GET['id']);

    try {
        if (Producto::eliminar($id)) {
            http_response_code(200); // OK
            echo json_encode(['success' => true, 'message' => 'Producto eliminado exitosamente']);
        } else {
            http_response_code(404); // No encontrado
            echo json_encode(['success' => false, 'message' => 'Producto no encontrado o ya fue eliminado.']);
        }
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Error en el servidor: ' . $e->getMessage()]);
    }
}
?>