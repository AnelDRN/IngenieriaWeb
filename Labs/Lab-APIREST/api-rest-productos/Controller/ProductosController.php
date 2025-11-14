<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

require_once '../Modelo/conexion.php';
require_once '../Modelo/Productos.php';

// Obtener el método HTTP
$method = $_SERVER['REQUEST_METHOD'];

// Centralizar con SWITCH según método HTTP
switch($method) {
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
        // Opcional, pero es buena práctica tenerlo
        eliminarProducto();
        break;
        
    default:
        http_response_code(405); // Método no permitido
        echo json_encode([
            'success' => false,
            'message' => 'Método no permitido'
        ]);
}

// FUNCIÓN POST - Registrar producto (EJEMPLO GUIADO)
function registrarProducto() {
    $data = json_decode(file_get_contents("php://input"), true);
    
    if(!isset($data['codigo']) || !isset($data['producto']) || 
       !isset($data['precio']) || !isset($data['cantidad'])) {
        http_response_code(400); // Bad Request
        echo json_encode(['success' => false, 'message' => 'Datos incompletos']);
        return;
    }
    
    try {
        $producto = new Producto();
        $producto->codigo = $data['codigo'];
        $producto->producto = $data['producto'];
        $producto->precio = $data['precio'];
        $producto->cantidad = $data['cantidad'];
        
        if($producto->guardar()) {
            http_response_code(201); // Creado
            echo json_encode(['success' => true, 'message' => 'Producto registrado exitosamente']);
        } else {
            http_response_code(500); // Internal Server Error
            echo json_encode(['success' => false, 'message' => 'Error al registrar producto']);
        }
    } catch(Exception $e) {
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
    }
}

// FUNCIÓN GET - Listar productos (IMPLEMENTADO)
function obtenerProductos() {
    try {
        if(isset($_GET['id'])) {
            $id = intval($_GET['id']);
            $producto = Producto::buscarPorId($id);
            
            if($producto) {
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
    } catch(Exception $e) {
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
    }
}

// FUNCIÓN PUT - Actualizar producto (IMPLEMENTADO)
function actualizarProducto() {
    $data = json_decode(file_get_contents("php://input"), true);
    
    // El ID para PUT puede venir en la URL o en el cuerpo. La guía sugiere en el cuerpo.
    $id = isset($_GET['id']) ? intval($_GET['id']) : (isset($data['id']) ? intval($data['id']) : null);

    if($id === null) {
        http_response_code(400); // Bad Request
        echo json_encode(['success' => false, 'message' => 'ID del producto es requerido']);
        return;
    }

    // Validar que el producto exista antes de actualizar
    if (!Producto::buscarPorId($id)) {
        http_response_code(404); // No encontrado
        echo json_encode(['success' => false, 'message' => 'Producto no encontrado']);
        return;
    }
    
    // Validar datos completos para la actualización
    if(!isset($data['codigo']) || !isset($data['producto']) || 
       !isset($data['precio']) || !isset($data['cantidad'])) {
        http_response_code(400); // Bad Request
        echo json_encode(['success' => false, 'message' => 'Datos incompletos para la actualización']);
        return;
    }

    try {
        $producto = new Producto();
        $producto->id = $id;
        $producto->codigo = $data['codigo'];
        $producto->producto = $data['producto'];
        $producto->precio = $data['precio'];
        $producto->cantidad = $data['cantidad'];
        
        if($producto->editar()) {
            http_response_code(200); // OK
            echo json_encode(['success' => true, 'message' => 'Producto actualizado exitosamente']);
        } else {
            // Esto podría pasar si los datos son idénticos y la BD no reporta filas afectadas
            http_response_code(200); // O 304 Not Modified, pero 200 es más simple
            echo json_encode(['success' => true, 'message' => 'No se realizaron cambios en el producto']);
        }
        
    } catch(Exception $e) {
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
    }
}

// FUNCIÓN DELETE - Eliminar producto (OPCIONAL IMPLEMENTADO)
function eliminarProducto() {
    // El ID para DELETE usualmente viene en la URL
    if(!isset($_GET['id'])) {
        http_response_code(400); // Bad Request
        echo json_encode(['success' => false, 'message' => 'ID del producto es requerido']);
        return;
    }

    $id = intval($_GET['id']);

    try {
        if(Producto::eliminar($id)) {
            http_response_code(200); // OK
            echo json_encode(['success' => true, 'message' => 'Producto eliminado exitosamente']);
        } else {
            http_response_code(404); // No encontrado
            echo json_encode(['success' => false, 'message' => 'Producto no encontrado o ya fue eliminado']);
        }
    } catch(Exception $e) {
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
    }
}
?>